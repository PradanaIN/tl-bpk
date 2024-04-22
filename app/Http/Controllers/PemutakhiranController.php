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
        return view('livewire.pemutakhiran.index', [
            'title' => 'Pemutakhiran Status',
            'rekomendasi' => Rekomendasi::all(),
            'kamus_pemeriksaan' => Kamus::where('jenis', 'Pemeriksaan')->get(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rekomendasi $rekomendasi)
    {

        $rekomendasi = Rekomendasi::with('tindakLanjut')->find($rekomendasi->id);

        return view('livewire.pemutakhiran.show', [
            'title' => 'Detail Rekomendasi',
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
            'status_rekomendasi' => 'required'
        ]);

        $rekomendasi->update($validatedData);

        foreach ($request->tindak_lanjut as $key => $tindak_lanjut) {
            if ($request->id[$key] == null) {
                TindakLanjut::create([
                    'rekomendasi_id' => $rekomendasi->id,
                    'tindak_lanjut' => $tindak_lanjut,
                    'unit_kerja' => $request->unit_kerja[$key],
                    'tim_pemantauan' => $request->tim_pemantauan[$key],
                    'tenggat_waktu' => $request->tenggat_waktu[$key],
                    'bukti_tindak_lanjut' => 'Belum Diunggah!',
                    'status_tindak_lanjut' => 'Proses',
                ]);
            } else {
                TindakLanjut::where('id', $request->id[$key])->update([
                    'tindak_lanjut' => $tindak_lanjut,
                    'unit_kerja' => $request->unit_kerja[$key],
                    'tim_pemantauan' => $request->tim_pemantauan[$key],
                    'tenggat_waktu' => $request->tenggat_waktu[$key],
                    'bukti_tindak_lanjut' => $request->bukti_tindak_lanjut[$key],
                    'status_tindak_lanjut' => $request->status_tindak_lanjut[$key],
                ]);
            }}

        return redirect('/pemutakhiran/'.$rekomendasi->id)->with('update', 'Data berhasil diubah!');
    }

}
