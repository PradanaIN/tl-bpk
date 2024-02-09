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
    public function store(StoreTindakLanjutRequest $request)
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

        $validatedData = $request->validate([
            'dokumen_tindak_lanjut' => 'required',
            'detail_dokumen_tindak_lanjut' => 'required',
        ]);

        $tindakLanjut->update($validatedData);

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
