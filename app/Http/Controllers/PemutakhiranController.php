<?php

namespace App\Http\Controllers;

use App\Models\Kamus;
use App\Models\UnitKerja;
use App\Models\Rekomendasi;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;

class PemutakhiranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pemutakhiran.index', [
            'title' => 'Pemutakhiran Status Rekomendasi',
            // hanya menmpilkan rekomendasi yang semua tindak lanjutnya memiliki status_tindak_lanjut = sesuai
            'rekomendasi' => Rekomendasi::whereHas('tindakLanjut', function ($query) {
                $query->where('status_tindak_lanjut', 'Sesuai');})->get(),
            'kamus_pemeriksaan' => Kamus::where('jenis', 'Pemeriksaan')->get(),
            'TindakLanjut' => TindakLanjut::all(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rekomendasi $rekomendasi)
    {

        $rekomendasi = Rekomendasi::with('tindakLanjut')->find($rekomendasi->id);

        return view('pemutakhiran.show', [
            'title' => 'Detail Rekomendasi',
            'rekomendasi' => $rekomendasi,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rekomendasi $rekomendasi)
    {

        $rekomendasi->update([
            'status_rekomendasi' => $request->status_rekomendasi,
            'catatan_pemutakhiran' => $request->catatan_pemutakhiran,
            'pemutakhiran_by' => auth()->user()->nama,
            'pemutakhiran_at' => now(),
        ]);

        return redirect('/pemutakhiran-status/'.$rekomendasi->id)->with('update', 'Rekomendasi Berhasil Dimutakhirkan!');
    }

}
