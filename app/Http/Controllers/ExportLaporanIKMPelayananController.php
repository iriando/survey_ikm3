<?php

namespace App\Http\Controllers;

use App\Models\RespondenPelayanan;
use Illuminate\Http\Request;
use App\Models\NilaiPersepsiIkm;
use Illuminate\Support\Facades\DB;
use App\Models\Pertanyaanikmpelayanan;
use App\Models\Pilihan_jawabanikmpelayanan;
use PhpOffice\PhpWord\TemplateProcessor;

class ExportLaporanIKMPelayananController extends Controller
{
    public function export(Request $request)
    {
        $tanggalMulai   = $request->input('tanggalMulai');
        $tanggalAkhir   = $request->input('tanggalAkhir');
        $jenisLayanan   = $request->input('jenisLayanan');

        $templatePath = storage_path('app/templates/laporan_ikm_pelayanan.docx');
        $templateProcessor = new TemplateProcessor($templatePath);

        // Jumlah responden
        $jumlah_responden = RespondenPelayanan::whereNotNull('j_layanan')
            ->whereHas('jawabansurvey')
            ->whereBetween('created_at', [$tanggalMulai, $tanggalAkhir])
            ->when($jenisLayanan, fn ($q) => $q->where('j_layanan', $jenisLayanan))
            ->count();

        $ikm = $this->getIkm($tanggalMulai, $tanggalAkhir, $jenisLayanan);

        $templateProcessor->setValue('tanggal_mulai', \Carbon\Carbon::parse($tanggalMulai)->translatedFormat('d F Y'));
        $templateProcessor->setValue('tanggal_akhir', \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d F Y'));
        $templateProcessor->setValue('jumlah_responden', $jumlah_responden);
        $templateProcessor->setValue('ikm', $ikm);

        $pertanyaan = Pertanyaanikmpelayanan::with('unsur')->get();
        $jumlah_unsur = $pertanyaan->count();
        $bobot = $jumlah_unsur > 0 ? round(1 / $jumlah_unsur, 4) : 0;
        $templateProcessor->setValue('jumlah_unsur', $jumlah_unsur);
        $templateProcessor->setValue('bobot', $bobot);

        $templateProcessor->cloneRow('no', $jumlah_unsur);
        foreach ($pertanyaan as $index => $p) {
            $i = $index + 1;
            $templateProcessor->setValue("no#{$i}", $i);
            $templateProcessor->setValue("nama_unsur#{$i}", $p->unsur->nama_unsur ?? '-');
        }

        $kelompokPilihan = Pilihan_jawabanikmpelayanan::all()
            ->groupBy(fn($item) => strtoupper(trim($item->mutu)))
            ->map(fn($items) => $items->pluck('teks_pilihan')->implode(', '));

        $rows = [];
        $no = 1;
        foreach ($kelompokPilihan as $mutu => $pilihan) {
            $rows[] = [
                'no' => $no++,
                'mutu' => $mutu,
                'pilihan' => $pilihan,
            ];
        }
        $templateProcessor->cloneRowAndSetValues('no', $rows);

        // Tabel responden + total, rata-rata, skm
        $dataResponden = $this->getDataRespondenFixed($tanggalMulai, $tanggalAkhir, $jenisLayanan);

        $totals = [];
        $counts = [];
        $maxUnsur = 10;
        foreach ($dataResponden as $row) {
            for ($i = 1; $i <= $maxUnsur; $i++) {
                $key = 'U' . $i;
                $val = (float)($row[$key] ?? 0);
                $totals[$key] = ($totals[$key] ?? 0) + $val;
                $counts[$key] = ($counts[$key] ?? 0) + 1;
            }
        }

        $dataResponden[] = array_merge(['id_biodata' => 'Jumlah nilai perparameter'], $totals);

        $avgRow = ['id_biodata' => 'Nilai Rata-rata perparameter (NRR)'];
        for ($i = 1; $i <= $maxUnsur; $i++) {
            $key = 'U' . $i;
            $avgRow[$key] = ($counts[$key] ?? 0) > 0 ? round($totals[$key] / $counts[$key], 2) : 0;
        }
        $dataResponden[] = $avgRow;

        $skmRow = ['id_biodata' => 'Nilai SKM perparameter'];
        $skmData = $this->getSkmPerParameter($tanggalMulai, $tanggalAkhir, $jenisLayanan)
            ->pluck('skm', 'kd_unsurikmpelayanan');
        for ($i = 1; $i <= $maxUnsur; $i++) {
            $kd = 'U' . $i;
            $skmRow[$kd] = isset($skmData[$kd]) ? $skmData[$kd] : 0;
        }
        $dataResponden[] = $skmRow;

        $templateProcessor->cloneRowAndSetValues('id_biodata', $dataResponden);

        $laporanPath = storage_path('app/temp/laporan_ikm_pelayanan_' . time() . '.docx');
        $templateProcessor->saveAs($laporanPath);

        return response()->download($laporanPath)->deleteFileAfterSend(true);
    }

    public function getDataRespondenFixed($tanggalMulai, $tanggalAkhir, $jenisLayanan = null)
    {
        $pertanyaan = Pertanyaanikmpelayanan::with('unsur')->get();
        $kd_unsur_list = $pertanyaan->pluck('unsur.kd_unsur')->unique()->values();

        $query = DB::table('responden_ikm_Pelayanans')
            ->join('respondenpelayanans', 'responden_ikm_pelayanans.id_biodata', '=', 'respondenpelayanans.id')
            ->whereNotNull('respondenpelayanans.j_layanan')
            ->whereBetween('respondenpelayanans.created_at', [$tanggalMulai, $tanggalAkhir]);

        if ($jenisLayanan) {
            $query->where('respondenpelayanans.j_layanan', $jenisLayanan);
        }

        $query->selectRaw('respondenpelayanans.id AS id_biodata, ' . $kd_unsur_list->map(function ($kd) {
            return "MAX(CASE WHEN kd_unsurikmpelayanan = '{$kd}' THEN skor END) AS `{$kd}`";
        })->implode(', '))
        ->groupBy('respondenpelayanans.id');

        $result = [];
        foreach ($query->get() as $row) {
            $data = ['id_biodata' => $row->id_biodata];
            for ($i = 1; $i <= 10; $i++) {
                $kd = 'U' . $i;
                $data[$kd] = property_exists($row, $kd) ? (float)$row->$kd : 0;
            }
            $result[] = $data;
        }
        return $result;
    }

    public function getSkmPerParameter($tanggalMulai, $tanggalAkhir, $jenisLayanan = null)
    {
        $totalParameter = Pertanyaanikmpelayanan::count();
        $bobot = 1 / $totalParameter;

        $query = DB::table('responden_ikm_pelayanans')
            ->join('respondenpelayanans', 'responden_ikm_pelayanans.id_biodata', '=', 'respondenpelayanans.id')
            ->whereNotNull('respondenpelayanans.j_layanan')
            ->whereBetween('respondenpelayanans.created_at', [$tanggalMulai, $tanggalAkhir]);

        if ($jenisLayanan) {
            $query->where('respondenpelayanans.j_layanan', $jenisLayanan);
        }

        return $query->select('kd_unsurikmpelayanan', DB::raw("FORMAT(SUM(skor) / COUNT(skor) * $bobot, 2) as skm"))
            ->groupBy('kd_unsurikmpelayanan')
            ->get();
    }

    public function getIkm($tanggalMulai, $tanggalAkhir, $jenisLayanan = null)
    {
        $totalParameter = Pertanyaanikmpelayanan::count();
        $totalNp = NilaiPersepsiIkm::count();
        if ($totalParameter === 0 || $totalNp === 0) return 0;

        $konversi = 100 / $totalNp;
        $bobot = 1 / $totalParameter;

        $query = DB::table('responden_ikm_pelayanans')
            ->join('respondenpelayanans', 'responden_ikm_pelayanans.id_biodata', '=', 'respondenpelayanans.id')
            ->whereNotNull('respondenpelayanans.j_layanan')
            ->whereBetween('respondenpelayanans.created_at', [$tanggalMulai, $tanggalAkhir]);

        if ($jenisLayanan) {
            $query->where('respondenpelayanans.j_layanan', $jenisLayanan);
        }

        $totalSkor = $query->sum('skor');
        $totalResponden = $query->distinct('id_biodata')->count('id_biodata');

        if ($totalResponden === 0) {
            return 0;
        }

        $ikm = ($totalSkor / $totalResponden) * $bobot * $konversi;

        return round($ikm, 2);
    }
}
