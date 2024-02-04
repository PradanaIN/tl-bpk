<?php

namespace App\Http\Controllers;

use App\Models\Rekomendasi;
use Illuminate\Http\Request;

class RekomendasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('livewire.kelola-rekomendasi.index', [
            'rekomendasi' => Rekomendasi::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('livewire.kelola-rekomendasi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'pemeriksaan' => 'required',
            'jenis_pemeriksaan' => 'required',
            'tahun_pemeriksaan' => 'required',
            'hasil_pemeriksaan' => 'required',
            'jenis_temuan' => 'required',
            'uraian_temuan' => 'required',
            'rekomendasi' => 'required',
            'catatan_rekomendasi' => 'required',
            'tindak_lanjut' => 'required',
            'unit_kerja' => 'required',
            'tim_pemantauan' => 'required',
            'tenggat_waktu' => 'required',
        ]);

        Rekomendasi::create($validatedData);

        return redirect('/kelola-rekomendasi');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rekomendasi $rekomendasi)
    {
        return view('livewire.kelola-rekomendasi.show', [
            'rekomendasi' => $rekomendasi,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rekomendasi $rekomendasi)
    {
        return view('livewire.kelola-rekomendasi.edit', [
            'rekomendasi' => $rekomendasi,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rekomendasi $rekomendasi)
    {
        $validatedData = $request->validate([
            'pemeriksaan' => 'required',
            'jenis_pemeriksaan' => 'required',
            'tahun_pemeriksaan' => 'required',
            'hasil_pemeriksaan' => 'required',
            'jenis_temuan' => 'required',
            'uraian_temuan' => 'required',
            'rekomendasi' => 'required',
            'catatan_rekomendasi' => 'required',
            'tindak_lanjut' => 'required',
            'unit_kerja' => 'required',
            'tim_pemantauan' => 'required',
            'tenggat_waktu' => 'required',
        ]);

        $rekomendasi->update($validatedData);

        return redirect('/kelola-rekomendasi');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rekomendasi $rekomendasi)
    {
        Rekomendasi::destroy($rekomendasi->id);

        return redirect('/kelola-rekomendasi');
    }
}
