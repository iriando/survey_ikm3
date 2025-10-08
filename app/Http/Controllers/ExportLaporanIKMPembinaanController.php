<?php

namespace App\Http\Controllers;

use App\Models\Unsur;
use App\Models\Pilihan_jawaban;
use App\Models\Kegiatan;
use App\Models\Responden;
use App\Models\Pertanyaan;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\TemplateProcessor;

class ExportLaporanIkmPembinaanController extends Controller
{
    public function export($kegiatanNama)
    {
        $kegiatan = Kegiatan::where('n_kegiatan', $kegiatanNama)->firstOrFail();
        $respondens = Responden::where('kegiatan', $kegiatanNama)->whereHas('jawabansurvey')->get();
        $jumlah_responden = $respondens->count();
        $tanggal_kegiatan = \Carbon\Carbon::parse($kegiatan->created_at)->translatedFormat('d F Y');
        $ikm = $this->getIkm($kegiatanNama);

        $templatePath = storage_path('app/templates/laporan_ikm_pembinaan_kegiatan.docx');
        $templateProcessor = new TemplateProcessor($templatePath);
        $templateProcessor->setValue('nama_kegiatan', $kegiatan->n_kegiatan);
        $templateProcessor->setValue('tanggal_kegiatan', $tanggal_kegiatan);
        $templateProcessor->setValue('jumlah_responden', $jumlah_responden);
        $templateProcessor->setValue('ikm', $ikm);

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

        $dataResponden = $this->getDataRespondenFixed($kegiatanNama);

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
        $skmData = $this->getSkmPerParameter($kegiatanNama)->pluck('skm', 'kd_unsurikmpembinaan');
        for ($i = 1; $i <= $maxUnsur; $i++) {
            $kd = 'P' . $i;
            $skmRow[$kd] = isset($skmData[$kd]) ? $skmData[$kd] : 0;
        }
        $dataResponden[] = $skmRow;

        $templateProcessor->cloneRowAndSetValues('id_biodata', $dataResponden);

        $laporanPath = storage_path('app/temp/laporan_ikm_pembinaan_' . time() . '.docx');
        $templateProcessor->saveAs($laporanPath);

        return response()->download($laporanPath)->deleteFileAfterSend(true);
    }

    public function getDataRespondenFixed($kegiatanNama)
    {
        $pertanyaan = Pertanyaan::with('unsur')->get();
        $kd_unsur_list = $pertanyaan->pluck('unsur.kd_unsur')->unique()->values();

        $query = DB::table('responden_ikms')
            ->join('respondens', 'responden_ikms.id_biodata', '=', 'respondens.id')
            ->where('respondens.kegiatan', $kegiatanNama)
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

    public function getSkmPerParameter($kegiatanNama)
    {
        $totalParameter = Pertanyaan::count();
        $bobot = 1 / $totalParameter;

        $query = DB::table('responden_ikms')
            ->whereNotNull('kegiatan')
            ->join('respondens', 'responden_ikms.id_biodata', '=', 'respondens.id')
            ->select('kd_unsurikmpembinaan', DB::raw("FORMAT(SUM(skor) / COUNT(skor) * $bobot, 2) as skm"))
            ->where('respondens.kegiatan', $kegiatanNama)
            ->groupBy('kd_unsurikmpembinaan')
            ->get();

        return $query;
    }

    public function getIkm($kegiatanNama)
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
            ->whereNotNull('respondens.kegiatan');

        if ($kegiatanNama) {
            $query->where('respondens.kegiatan', $kegiatanNama);
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
