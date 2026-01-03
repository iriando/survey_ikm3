<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Instansi;
use App\Models\Kegiatan;
use App\Models\Layanantu;
use App\Models\RespondenPembinaan;
use App\Models\RespondenPelayanan;
use App\Models\Narasumber;
use App\Models\Pertanyaan;
use App\Models\RespondenIkmPelayanan;
use App\Models\RespondenIkmPembinaan;
use Illuminate\Http\Request;
use App\Models\Pertanyaanikmtu;
use App\Models\Pertanyaanikmpelayanan;
use App\Models\Pertanyaanikmpembinaan;
use App\Models\Pilihan_jawabanikmpembinaan;
use App\Models\Pilihan_jawabanikmpelayanan;

class RespondenController extends Controller
{

    public function index()
    {
        //
    }

    public function indextu()
    {
        return view('skmtu.welcome');
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

        $responden = RespondenPelayanan::create($request->all());

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

        $responden = RespondenPembinaan::create($request->all());

        return redirect()
                ->route('skmpembinaan.skm', ['id' => $responden->id, 'narasumber_id' => $request->narasumber_id,])
                ->with('success', 'Data berhasil disimpan!');
    }

    public function storeskmtu(Request $request)
    {
        $validated =$request->validate([
            'nama' => 'required|string|max:255',
            'j_layanantu' => 'required|string',
        ]);

        $responden = Responden::create([
            'nama' => $validated['nama'],
            'j_layanantu' => $validated['j_layanantu'],
            'usia' => 0,
            'gender' => 0,
            'nohp' => 0,
            'pendidikan' => 0,
            'pekerjaan' => 'Pegawai negeri sipil',
        ]);

        return redirect()->route('skmtu.skm', ['id' => $responden->id])->with('success', 'Data berhasil disimpan!');
    }

    public function skmpembinaan($id)
    {
        $responden = RespondenPembinaan::findOrFail($id);
        $sudahIsi = RespondenIkmPembinaan::where('id_biodata', $id)
            ->whereNotNull('kd_unsurikmpembinaan')
            ->exists();
        if ($sudahIsi) {
            return redirect()->route('kritik-saranpembinaan.form', $id)
                ->with('warning', 'Anda sudah pernah mengisi survei pembinaan ini.');
        }
        $pertanyaans = Pertanyaanikmpembinaan::with('pilihanJawabans', 'unsur')->get();
        return view('skmpembinaan.skm', compact('responden', 'pertanyaans'));
    }

    public function skmpelayanan($id)
    {
        $responden = RespondenPelayanan::findOrFail($id);
        $sudahIsi = RespondenIkmPelayanan::where('id_biodata', $id)
            ->whereNotNull('kd_unsurikmpelayanan')
            ->exists();
        if ($sudahIsi) {
            return redirect()->route('kritik-saranpelayanan.form', $id)
                ->with('warning', 'Anda sudah pernah mengisi survei pelayanan ini.');
        }
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
        $sudahIsi = RespondenIkmPembinaan::where('id_biodata', $id)
            ->whereNotNull('kd_unsurikmpembinaan')
            ->exists();
        if ($sudahIsi) {
            return redirect()->route('kritik-saranpembinaan.form', $id)
                ->with('warning', 'Data survei Anda sudah tersimpan sebelumnya.');
        }
        foreach ($request->jawaban as $kd_unsur => $pilihan_id) {
            $pilihan = Pilihan_jawabanikmpembinaan::find($pilihan_id);
            RespondenIkmPembinaan::create([
                'id_biodata' => $id,
                'kd_unsurikmpembinaan' => $kd_unsur,
                'narasumber_id' => $request->narasumber_id,
                'skor' => $pilihan?->bobot ?? 0,
            ]);
        }
        return redirect()->route('kritik-saranpembinaan.form', $id);
    }

    // Menyimpan Jawaban Survei
    public function submitskmpelayanan(Request $request, $id)
    {
        $request->validate([
            'jawaban' => 'required|array',
        ]);
        $sudahIsi = RespondenIkmPelayanan::where('id_biodata', $id)
            ->whereNotNull('kd_unsurikmpelayanan')
            ->exists();

        if ($sudahIsi) {
            return redirect()->route('kritik-saranpelayanan.form', $id)
                ->with('warning', 'Data survei Anda sudah tersimpan sebelumnya.');
        }
        foreach ($request->jawaban as $kd_unsur => $pilihan_id) {
            $pilihan = Pilihan_jawabanikmpelayanan::find($pilihan_id);
            RespondenIkmPelayanan::create([
                'id_biodata' => $id,
                'kd_unsurikmpelayanan' => $kd_unsur,
                'skor' => $pilihan?->bobot ?? 0,
            ]);
        }
        return redirect()->route('kritik-saranpelayanan.form', $id);
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

    public function kritiksaranrespelayanan($id)
    {
        $responden = RespondenPelayanan::findOrFail($id);
        return view('skmpelayanan.kritik-saran', compact('responden'));
    }

    public function submitkritiksaranrespelayanan(Request $request, $id)
    {
        $request->validate([
            'kritik_saran' => 'nullable|string|max:1000',
        ]);

        $responden = RespondenPelayanan::findOrFail($id);
        $responden->kritik_saran = $request->input('kritik_saran');
        $responden->save();

        return redirect()->route('terima-kasih');
    }

    public function kritiksaranrespembinaan($id)
    {
        $responden = RespondenPembinaan::findOrFail($id);
        return view('skmpembinaan.kritik-saran', compact('responden'));
    }

    public function submitkritiksaranrespembinaan(Request $request, $id)
    {
        $request->validate([
            'kritik_saran' => 'nullable|string|max:1000',
        ]);

        $responden = RespondenPembinaan::findOrFail($id);
        $responden->kritik_saran = $request->input('kritik_saran');
        $responden->save();

        return redirect()->route('terima-kasih');
    }
}
