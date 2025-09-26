<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Instansi;
use App\Models\Kegiatan;
use App\Models\Layanantu;
use App\Models\Responden;
use App\Models\Narasumber;
use App\Models\Pertanyaan;
use App\Models\RespondenIkm;
use Illuminate\Http\Request;
use App\Models\Pertanyaanikmtu;
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
        $kegiatans = Kegiatan::with('narasumbers')
            ->where('status', 1)
            ->orderBy('n_kegiatan')
            ->get();
        $instansi = Instansi::all();
        $narasumbers = Narasumber::all();
        return view('skmpembinaan.biodata', compact('kegiatans', 'instansi', 'narasumbers'));
    }

    public function createskmtu()
    {
        $layanans = Layanantu::orderBy('j_layanan')->get();
        $instansi = Instansi::all();
        return view('skmtu.biodata', compact('layanans', 'instansi'));
    }

    public function storeskmpelayanan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'usia' => 'required|string|',
            'gender' => 'required|string',
            'nohp' => 'required|string|max:255',
            'pendidikan' => 'required|string',
            'pekerjaan' => 'required|string',
            'instansi' => 'string|nullable',
            'j_layanan' => 'required|string',
            'jabatan' => 'string|nullable',
        ]);

        $responden = Responden::create($request->all());

        return redirect()->route('skmpelayanan.skm', ['id' => $responden->id])->with('success', 'Data berhasil disimpan!');
    }

    public function storeskmpembinaan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'usia' => 'required|string',
            'gender' => 'required|string',
            'nohp' => 'required|string|max:255',
            'pendidikan' => 'required|string',
            'pekerjaan' => 'required|string',
            'instansi' => 'string|nullable',
            'kegiatan' => 'required|string',
            'jabatan' => 'string|nullable',
        ]);

        $responden = Responden::create($request->all());

        return redirect()
                ->route('skmpembinaan.skm', ['id' => $responden->id, 'narasumber_id' => $request->narasumber_id,])
                ->with('success', 'Data berhasil disimpan!');
    }

    public function storeskmtu(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'usia' => 'required|string|',
            'gender' => 'required|string',
            'nohp' => 'required|string|max:255',
            'pendidikan' => 'required|string',
            'pekerjaan' => 'required|string',
            'instansi' => 'string|nullable',
            'j_layanantu' => 'required|string',
            'jabatan' => 'string|nullable',
        ]);

        $responden = Responden::create($request->all());

        return redirect()->route('skmtu.skm', ['id' => $responden->id])->with('success', 'Data berhasil disimpan!');
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

    public function skmtu($id)
    {
        $responden = Responden::findOrFail($id);
        $pertanyaans = Pertanyaanikmtu::with('pilihanJawabans', 'unsur')->get();

        return view('skmtu.skm', compact('responden', 'pertanyaans'));
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

        return redirect()->route('kritik-saran.form', $id);
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

        return redirect()->route('kritik-saran.form', $id);
    }

    public function submitskmtu(Request $request, $id)
    {
        $request->validate([
            'jawaban' => 'required|array',
        ]);

        foreach ($request->jawaban as $kd_unsur => $skor) {
            RespondenIkm::create([
                'id_biodata' => $id,
                'kd_unsurikmtu' => $kd_unsur,
                'skor' => $skor,
            ]);
        }

        return redirect()->route('kritik-saran.form', $id);
    }

    public function kritiksaran($id)
    {
        $responden = Responden::findOrFail($id);
        return view('kritik-saran', compact('responden'));
    }

    public function submitkritiksaran(Request $request, $id)
    {
        $request->validate([
            'kritik_saran' => 'nullable|string|max:1000',
        ]);

        $responden = Responden::findOrFail($id);
        $responden->kritik_saran = $request->input('kritik_saran');
        $responden->save();

        return redirect()->route('terima-kasih');
    }
}
