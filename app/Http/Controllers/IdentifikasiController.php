<?php

namespace App\Http\Controllers;

use App\Models\Rekomendasi;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;

class IdentifikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tindak_lanjut = TindakLanjut::whereNotNull('bukti_tindak_lanjut')
            ->where('bukti_tindak_lanjut', '!=', 'Belum Diunggah!')
            ->get();

        return view('identifikasi.index', [
            'title' => 'Identifikasi Tindak Lanjut',
            'tindak_lanjut' => $tindak_lanjut,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(TindakLanjut $tindakLanjut)
    {
        $rekomendasi = Rekomendasi::find($tindakLanjut->rekomendasi_id);

        return view('identifikasi.show', [
            'title' => 'Detail Tindak Lanjut',
            'tindak_lanjut' => $tindakLanjut,
            'rekomendasi' => $rekomendasi,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TindakLanjut $tindakLanjut)
    {

        $tindakLanjut->update([
            'status_tindak_lanjut' => $request->status_tindak_lanjut,
            'status_tindak_lanjut_at' => now(),
            'status_tindak_lanjut_by' => auth()->user()->nama,
            'catatan_tindak_lanjut' => $request->catatan_tindak_lanjut,
        ]);

        return redirect('/identifikasi/' . $tindakLanjut->id)->with('update', 'Update Status Berhasil!');
    }

}
