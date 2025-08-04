<?php

namespace App\Http\Controllers;

use App\Models\Responden;
use Illuminate\Http\Request;
use App\Models\Pilihan_jawaban;
use App\Models\NilaiPersepsiIkm;
use Illuminate\Support\Facades\DB;
use App\Models\Pertanyaanikmpelayanan;
use App\Models\Pilihan_jawabanikmpelayanan;
use PhpOffice\PhpWord\TemplateProcessor;

class ExportLaporanIkmPelayananController extends Controller
{
    public function export(Request $request)
    {
        $tanggalMulai = $request->input('tanggalMulai');
        $tanggalAkhir = $request->input('tanggalAkhir');

        $templatePath = storage_path('app/templates/laporan_ikm_pelayanan.docx');
        $templateProcessor = new TemplateProcessor($templatePath);

        $jumlah_responden = Responden::whereNotNull('j_layanan')
            ->whereBetween('created_at', [$tanggalMulai, $tanggalAkhir])
            ->count();
        $ikm = $this->getIkm($tanggalMulai, $tanggalAkhir);

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
        $dataResponden = $this->getDataRespondenFixed($tanggalMulai, $tanggalAkhir);

        $totals = [];
        $counts = [];
        $maxUnsur = 10;
        foreach ($dataResponden as $row) {
            for ($i = 1; $i <= $maxUnsur; $i++) {
                $key = 'U' . $i;
                $val = (int)($row[$key] ?? 0);
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
        $skmData = $this->getSkmPerParameter($tanggalMulai, $tanggalAkhir)->pluck('skm', 'kd_unsurikmpelayanan');
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

    public function getDataRespondenFixed($tanggalMulai, $tanggalAkhir)
    {
        $pertanyaan = Pertanyaanikmpelayanan::with('unsur')->get();
        $kd_unsur_list = $pertanyaan->pluck('unsur.kd_unsur')->unique()->values();

        $query = DB::table('responden_ikms')
            ->join('respondens', 'responden_ikms.id_biodata', '=', 'respondens.id')
            ->whereNotNull('respondens.j_layanan')
            ->whereBetween('respondens.created_at', [$tanggalMulai, $tanggalAkhir])
            ->selectRaw('respondens.id AS id_biodata, ' . $kd_unsur_list->map(function ($kd) {
                return "MAX(CASE WHEN kd_unsurikmpelayanan = '{$kd}' THEN skor END) AS `{$kd}`";
            })->implode(', '))
            ->groupBy('respondens.id')
            ->get();

        $result = [];
        foreach ($query as $row) {
            $data = ['id_biodata' => $row->id_biodata];
            for ($i = 1; $i <= 10; $i++) {
                $kd = 'U' . $i;
                $data[$kd] = property_exists($row, $kd) ? (int)$row->$kd : 0;
            }
            $result[] = $data;
        }
        // dd($result);
        // die;
        return $result;
    }

    public function getSkmPerParameter($tanggalMulai, $tanggalAkhir)
    {
        $totalParameter = Pertanyaanikmpelayanan::count();
        $bobot = 1 / $totalParameter;

        return DB::table('responden_ikms')
            ->join('respondens', 'responden_ikms.id_biodata', '=', 'respondens.id')
            ->whereNotNull('respondens.j_layanan')
            ->whereBetween('respondens.created_at', [$tanggalMulai, $tanggalAkhir])
            ->select('kd_unsurikmpelayanan', DB::raw("FORMAT(SUM(skor) / COUNT(skor) * $bobot, 2) as skm"))
            ->groupBy('kd_unsurikmpelayanan')
            ->get();
    }

    public function getIkm($tanggalMulai, $tanggalAkhir)
    {
        $totalParameter = Pertanyaanikmpelayanan::count();
        $totalNp = NilaiPersepsiIkm::count();
        if ($totalParameter === 0 || $totalNp === 0) return 0;

        $konversi = 100 / $totalNp;
        $bobot = 1 / $totalParameter;

        $query = DB::table('responden_ikms')
            ->join('respondens', 'responden_ikms.id_biodata', '=', 'respondens.id')
            ->whereNotNull('respondens.j_layanan')
            ->whereBetween('respondens.created_at', [$tanggalMulai, $tanggalAkhir]);

        $totalSkor = $query->sum('skor');
        $totalResponden = $query->distinct('id_biodata')->count('id_biodata');

        if ($totalResponden === 0) return 0;

        return round(($totalSkor / $totalResponden) * $bobot * $konversi, 2);
    }
}
