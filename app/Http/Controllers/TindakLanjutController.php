<?php

namespace App\Http\Controllers;

use App\Models\TindakLanjut;
use Illuminate\Http\Request;
use App\Models\Rekomendasi;

class TindakLanjutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('livewire.kelola-tindak-lanjut.index', [
            'title' => 'Kelola Tindak Lanjut',
            'tindak_lanjut' => TindakLanjut::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TindakLanjut $tindakLanjut)
    {
        $rekomendasi = Rekomendasi::find($tindakLanjut->rekomendasi_id);
        return view('livewire.kelola-tindak-lanjut.show', [
            'title' => 'Detail Tindak Lanjut',
            'tindak_lanjut' => $tindakLanjut,
            'rekomendasi' => $rekomendasi,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TindakLanjut $tindakLanjut)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TindakLanjut $tindakLanjut)
    {
        $file = $request->file('dokumen_tindak_lanjut');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/tindak_lanjut'), $fileName);

        $tindakLanjut->update([
            'rekomendasi_id' => $tindakLanjut->rekomendasi_id,
            'tindak_lanjut' => $tindakLanjut->tindak_lanjut,
            'unit_kerja' => $tindakLanjut->unit_kerja,
            'tim_pemantauan' => $tindakLanjut->tim_pemantauan,
            'dokumen_tindak_lanjut' => $fileName,
            'catatan_tindak_lanjut' => $request->catatan_tindak_lanjut,
            // 'upload_by' => auth()->user()->name,
            'upload_by' => 'admin',
            'upload_at' => now(),
            'status' => 'Selesai',
        ]);

        return redirect('/kelola-tindak-lanjut/' . $tindakLanjut->id)->with('update', 'Upload Berhasil!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TindakLanjut $tindakLanjut)
    {
        //
    }
}
