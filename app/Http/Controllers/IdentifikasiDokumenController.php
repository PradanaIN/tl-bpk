<?php

namespace App\Http\Controllers;

use App\Models\Rekomendasi;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;

class IdentifikasiDokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tindak_lanjut = TindakLanjut::whereNotNull('dokumen_tindak_lanjut')
            ->where('dokumen_tindak_lanjut', '!=', 'Belum Diunggah!')
            ->where('status_tindak_lanjut', '!=', 'Proses')
            ->get();

        return view('livewire.identifikasi.index', [
            'title' => 'Identifikasi Tindak Lanjut',
            'tindak_lanjut' => $tindak_lanjut,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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

        return view('livewire.identifikasi.show', [
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

        $tindakLanjut->update([
            'rekomendasi_id' => $tindakLanjut->rekomendasi_id,
            'tindak_lanjut' => $tindakLanjut->tindak_lanjut,
            'unit_kerja' => $tindakLanjut->unit_kerja,
            'tim_pemantauan' => $tindakLanjut->tim_pemantauan,
            'tenggat_waktu' => $tindakLanjut->tenggat_waktu,
            'dokumen_tindak_lanjut' => $tindakLanjut->dokumen_tindak_lanjut,
            'detail_dokumen_tindak_lanjut' => $tindakLanjut->detail_dokumen_tindak_lanjut,
            // 'upload_by' => auth()->user()->name,
            'upload_by' => $tindakLanjut->upload_by,
            'upload_at' => $tindakLanjut->upload_at,
            'status_tindak_lanjut' => $request->status_tindak_lanjut,
            'status_tindak_lanjut_at' => now(),
            'status_tindak_lanjut_by' => auth()->user()->nama,
            'catatan_tindak_lanjut' => $request->catatan_tindak_lanjut,
        ]);

        return redirect('/identifikasi/' . $tindakLanjut->id)->with('update', 'Update Status Berhasil!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TindakLanjut $tindakLanjut)
    {
        //
    }
}
