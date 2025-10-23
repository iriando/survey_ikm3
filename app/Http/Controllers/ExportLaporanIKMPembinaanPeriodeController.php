<?php

namespace App\Http\Controllers;

use App\Models\Unsur;
use App\Models\Pilihan_jawaban;
use App\Models\Responden;
use App\Models\Pertanyaan;
use App\Models\Kegiatan;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;

class ExportLaporanIKMPembinaanPeriodeController extends Controller
{
    public function export()
    {
        $tanggalMulai = request()->query('tanggalMulai');
        $tanggalAkhir = request()->query('tanggalAkhir');

        if (!$tanggalMulai || !$tanggalAkhir) {
            abort(400, 'Tanggal mulai dan tanggal akhir harus diisi.');
        }

        // Ambil responden dalam periode
        $respondens = Responden::whereNotNull('kegiatan')
            ->whereBetween(DB::raw('DATE(created_at)'), [$tanggalMulai, $tanggalAkhir])
            ->whereHas('jawabansurvey')
            ->get();

        $jumlah_responden = $respondens->count();
        $ikm = $this->getIkm($tanggalMulai, $tanggalAkhir);

        // Ambil daftar kegiatan unik dalam periode
        $kegiatanList = $respondens->pluck('kegiatan')->unique()->values();
        $kegiatanRows = [];
        foreach ($kegiatanList as $index => $nama) {
            $kegiatanRows[] = [
                'no_kegiatan' => $index + 1,
                'nama_kegiatan' => $nama,
            ];
        }

        // Template
        $templatePath = storage_path('app/templates/laporan_ikm_pembinaan_periode.docx');
        $templateProcessor = new TemplateProcessor($templatePath);

        $templateProcessor->setValue('tanggal_mulai', Carbon::parse($tanggalMulai)->translatedFormat('d F Y'));
        $templateProcessor->setValue('tanggal_akhir', Carbon::parse($tanggalAkhir)->translatedFormat('d F Y'));
        $templateProcessor->setValue('jumlah_responden', $jumlah_responden);
        $templateProcessor->setValue('ikm', $ikm);

        // Clone daftar kegiatan di periode
        if (count($kegiatanRows) > 0) {
            $templateProcessor->cloneRowAndSetValues('no_kegiatan', $kegiatanRows);
        }

        // Unsur
        $unsurs = Unsur::orderBy('kd_unsur')->get();
        $jumlah_unsur = $unsurs->count();
        $templateProcessor->setValue('jumlah_unsur', $jumlah_unsur);
        $templateProcessor->cloneRow('no', $jumlah_unsur);

        $bobot = $jumlah_unsur > 0 ? round(1 / $jumlah_unsur, 4) : 0;
        $templateProcessor->setValue('bobot', $bobot);

        foreach ($unsurs as $index => $unsur) {
            $i = $index + 1;
            $templateProcessor->setValue("no#{$i}", $i);
            $templateProcessor->setValue("nama_unsur#{$i}", $unsur->nama_unsur);
        }

        // Pilihan jawaban
        $kelompokPilihan = Pilihan_jawaban::all()
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

        // Data responden (dengan total, rata-rata, SKM)
        $dataResponden = $this->getDataRespondenFixed($tanggalMulai, $tanggalAkhir);

        $totals = [];
        $counts = [];
        $maxUnsur = 10;
        foreach ($dataResponden as $row) {
            for ($i = 1; $i <= $maxUnsur; $i++) {
                $key = 'P' . $i;
                $val = (float)($row[$key] ?? 0);
                $totals[$key] = ($totals[$key] ?? 0) + $val;
                $counts[$key] = ($counts[$key] ?? 0) + 1;
            }
        }

        $dataResponden[] = array_merge(['id_biodata' => 'Jumlah nilai perparameter'], $totals);

        $avgRow = ['id_biodata' => 'Nilai Rata-rata perparameter (NRR)'];
        for ($i = 1; $i <= $maxUnsur; $i++) {
            $key = 'P' . $i;
            $avgRow[$key] = ($counts[$key] ?? 0) > 0 ? round($totals[$key] / $counts[$key], 2) : 0;
        }
        $dataResponden[] = $avgRow;

        $skmRow = ['id_biodata' => 'Nilai SKM perparameter'];
        $skmData = $this->getSkmPerParameter($tanggalMulai, $tanggalAkhir)->pluck('skm', 'kd_unsurikmpembinaan');
        for ($i = 1; $i <= $maxUnsur; $i++) {
            $kd = 'P' . $i;
            $skmRow[$kd] = isset($skmData[$kd]) ? $skmData[$kd] : 0;
        }
        $dataResponden[] = $skmRow;

        $templateProcessor->cloneRowAndSetValues('id_biodata', $dataResponden);

        // Simpan & download
        $laporanPath = storage_path('app/temp/laporan_ikm_pembinaan_periode_' . time() . '.docx');
        $templateProcessor->saveAs($laporanPath);

        return response()->download($laporanPath)->deleteFileAfterSend(true);
    }

    private function getDataRespondenFixed($tanggalMulai, $tanggalAkhir)
    {
        $pertanyaan = Pertanyaan::with('unsur')->get();
        $kd_unsur_list = $pertanyaan->pluck('unsur.kd_unsur')->unique()->values();

        $query = DB::table('responden_ikms')
            ->join('respondens', 'responden_ikms.id_biodata', '=', 'respondens.id')
            ->whereNotNull('respondens.kegiatan')
            ->whereBetween(DB::raw('DATE(responden_ikms.updated_at)'), [$tanggalMulai, $tanggalAkhir])
            ->selectRaw('respondens.id AS id_biodata, ' . $kd_unsur_list->map(function ($kd) {
                return "MAX(CASE WHEN kd_unsurikmpembinaan = '{$kd}' THEN skor END) AS `{$kd}`";
            })->implode(', '))
            ->groupBy('respondens.id')
            ->get();

        $result = [];
        foreach ($query as $row) {
            $data = ['id_biodata' => $row->id_biodata];
            for ($i = 1; $i <= 10; $i++) {
                $kd = 'P' . $i;
                $data[$kd] = property_exists($row, $kd) ? (float)$row->$kd : 0;
            }
            $result[] = $data;
        }

        return $result;
    }

    private function getSkmPerParameter($tanggalMulai, $tanggalAkhir)
    {
        $totalParameter = Pertanyaan::count();
        $bobot = 1 / $totalParameter;

        return DB::table('responden_ikms')
            ->join('respondens', 'responden_ikms.id_biodata', '=', 'respondens.id')
            ->whereBetween(DB::raw('DATE(responden_ikms.updated_at)'), [$tanggalMulai, $tanggalAkhir])
            ->select('kd_unsurikmpembinaan', DB::raw("FORMAT(SUM(skor) / COUNT(skor) * $bobot, 2) as skm"))
            ->groupBy('kd_unsurikmpembinaan')
            ->get();
    }

    private function getIkm($tanggalMulai, $tanggalAkhir)
    {
        $totalParameter = Pertanyaan::count();
        $totalNp = \App\Models\NilaiPersepsiIkm::count();

        if ($totalParameter === 0 || $totalNp === 0) {
            return null;
        }

        $konversi = 100 / $totalNp;
        $bobot = 1 / $totalParameter;

        $query = DB::table('responden_ikms')
            ->join('respondens', 'responden_ikms.id_biodata', '=', 'respondens.id')
            ->whereBetween(DB::raw('DATE(responden_ikms.updated_at)'), [$tanggalMulai, $tanggalAkhir]);

        $totalSkor = $query->sum('skor');
        $totalResponden = $query->distinct('id_biodata')->count('id_biodata');

        if ($totalResponden === 0) {
            return 0;
        }

        return round(($totalSkor / $totalResponden) * $bobot * $konversi, 2);
    }
}
