<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Instansi;
use App\Models\Kegiatan;
use App\Models\Responden;
use App\Models\Narasumber;
use App\Models\Pertanyaan;
use App\Models\RespondenIkm;
use Illuminate\Http\Request;
use App\Models\Pertanyaanikmpelayanan;

class RespondenController extends Controller
{

    public function index()
    {
        //
    }

    public function createskmpelayanan()
    {
        $layanans = Layanan::orderBy('j_layanan')->get();
        $instansi = Instansi::all();
        return view('skmpelayanan.biodata', compact('layanans', 'instansi'));
    }

    public function createskmpembinaan()
    {
        $kegiatans = Kegiatan::with('narasumbers')->orderBy('n_kegiatan')->get();
        $instansi = Instansi::all();
        $narasumbers = Narasumber::all();
        return view('skmpembinaan.biodata', compact('kegiatans', 'instansi', 'narasumbers'));
    }

    public function storeskmpelayanan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'usia' => 'required|integer|min:1',
            'gender' => 'required|string',
            'nohp' => 'required|string|max:255',
            'pendidikan' => 'required|string',
            'pekerjaan' => 'required|string',
            'instansi' => 'required|string',
            'j_layanan' => 'string',
        ]);

        $responden = Responden::create($request->all());

        return redirect()->route('skmpelayanan.skm', ['id' => $responden->id])->with('success', 'Data berhasil disimpan!');
    }

    public function storeskmpembinaan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'usia' => 'required|integer|min:1',
            'gender' => 'required|string',
            'nohp' => 'required|string|max:255',
            'pendidikan' => 'required|string',
            'pekerjaan' => 'required|string',
            'instansi' => 'required|string',
            'kegiatan' => 'string',
        ]);

        $responden = Responden::create($request->all());

        return redirect()
                ->route('skmpembinaan.skm', ['id' => $responden->id, 'narasumber_id' => $request->narasumber_id,])
                ->with('success', 'Data berhasil disimpan!');
    }

    public function skmpembinaan($id)
    {
        $responden = Responden::findOrFail($id);
        $pertanyaans = Pertanyaan::with('pilihanJawabans', 'unsur')->get();
        return view('skmpembinaan.skm', compact('responden', 'pertanyaans'));
    }

    public function skmpelayanan($id)
    {
        $responden = Responden::findOrFail($id);
        $pertanyaans = Pertanyaanikmpelayanan::with('pilihanJawabans', 'unsur')->get();

        return view('skmpelayanan.skm', compact('responden', 'pertanyaans'));
    }

    public function submitskmpembinaan(Request $request, $id)
    {
        $request->validate([
            'jawaban' => 'required|array',
            'narasumber_id' => 'required|exists:narasumbers,id',
        ]);

        foreach ($request->jawaban as $kd_unsur => $skor) {
            RespondenIkm::create([
                'id_biodata' => $id,
                'kd_unsurikmpembinaan' => $kd_unsur,
                'narasumber_id' => $request->narasumber_id,
                'skor' => $skor,
            ]);
        }

        return redirect()->route('terima-kasih');
    }

    // Menyimpan Jawaban Survei
    public function submitskmpelayanan(Request $request, $id)
    {
        $request->validate([
            'jawaban' => 'required|array',
        ]);

        foreach ($request->jawaban as $kd_unsur => $skor) {
            RespondenIkm::create([
                'id_biodata' => $id,
                'kd_unsurikmpelayanan' => $kd_unsur,
                'skor' => $skor,
            ]);
        }

        return redirect()->route('terima-kasih');
    }
}
