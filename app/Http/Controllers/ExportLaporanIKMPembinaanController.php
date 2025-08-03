<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Unsur;
use App\Models\Pilihan_jawaban;
use App\Models\Kegiatan;
use App\Models\Responden;
use App\Models\RespondenIkm;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpWord\TemplateProcessor;

class ExportLaporanIkmPembinaanController extends Controller
{
    public function export($kegiatanNama)
    {
        // Cari kegiatan berdasarkan nama
        $kegiatan = \App\Models\Kegiatan::where('n_kegiatan', $kegiatanNama)->firstOrFail();

        // Cari responden berdasarkan nama kegiatan juga
        $respondens = \App\Models\Responden::where('kegiatan', $kegiatanNama)->get();
        $jumlah_responden = $respondens->count();

        $ids = $respondens->pluck('id');

        $rata_skor = \App\Models\RespondenIkm::whereIn('id_biodata', $ids)
                        ->whereNotNull('kd_unsurikmpembinaan')
                        ->avg('skor');

        $rata_skor = round($rata_skor, 2);

        $kategori = match (true) {
            $rata_skor >= 80 => 'Sangat Baik',
            $rata_skor >= 60 => 'Baik',
            $rata_skor >= 40 => 'Cukup',
            default => 'Kurang',
        };

        $tanggal_kegiatan = \Carbon\Carbon::parse($kegiatan->created_at)->translatedFormat('d F Y');

        $templatePath = storage_path('app/templates/laporan_ikm_pembinaan.docx');
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        $templateProcessor->setValue('nama_kegiatan', $kegiatan->n_kegiatan);
        $templateProcessor->setValue('tanggal_kegiatan', $tanggal_kegiatan);
        $templateProcessor->setValue('jumlah_responden', $jumlah_responden);
        // $templateProcessor->setValue('rata_skor', $rata_skor);
        // $templateProcessor->setValue('kategori', $kategori);
        $unsurs = Unsur::orderBy('kd_unsur')->get();
        $jumlah_unsur = $unsurs->count();
        $templateProcessor->setValue('jumlah_unsur', $jumlah_unsur);
        // clonerow untuk tampilkan tabel unsur
        $templateProcessor->cloneRow('no', $unsurs->count());
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

        // Clonerow untuk tampilkan pilihan jawaban dan mutu
        $templateProcessor->cloneRowAndSetValues('no', $rows);

        $outputPath = storage_path('app/temp/laporan_ikm_pembinaan_' . time() . '.docx');
        $templateProcessor->saveAs($outputPath);

        return response()->download($outputPath)->deleteFileAfterSend(true);
    }
}
