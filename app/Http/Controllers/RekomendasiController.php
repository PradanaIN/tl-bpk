<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use App\Models\Rekomendasi;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;

class RekomendasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('livewire.kelola-rekomendasi.index', [
            'title' => 'Kelola Rekomendasi',
            'rekomendasi' => Rekomendasi::all(),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $unit_kerja = UnitKerja::all();

        return view('livewire.kelola-rekomendasi.create', [
            'title' => 'Tambah Rekomendasi',
            'unit_kerja' => $unit_kerja,
        ]);
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
        ]);

        DB::beginTransaction();

        try {
            $rekomendasi = Rekomendasi::create($validatedData);

            foreach ($request->tindak_lanjut as $key => $tindak_lanjut) {
                TindakLanjut::create([
                    'rekomendasi_id' => $rekomendasi->id,
                    'tindak_lanjut' => $tindak_lanjut,
                    'unit_kerja' => $request->unit_kerja[$key],
                    'tim_pemantauan' => $request->tim_pemantauan[$key],
                    'tenggat_waktu' => $request->tenggat_waktu[$key],
                    'dokumen_tindak_lanjut' => 'Belum Diunggah!',
                    'status_tindak_lanjut' => 'Proses'
                ]);
            }

            DB::commit();

            return redirect('/kelola-rekomendasi')->with('create', 'Data berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan. Data gagal disimpan.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Rekomendasi $rekomendasi)
    {

        $rekomendasi = Rekomendasi::with('tindakLanjut')->find($rekomendasi->id);

        return view('livewire.kelola-rekomendasi.show', [
            'title' => 'Detail Rekomendasi',
            'rekomendasi' => $rekomendasi,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rekomendasi $rekomendasi)
    {

        $rekomendasi = Rekomendasi::with('tindakLanjut')->find($rekomendasi->id);
        $unit_kerja = UnitKerja::all();

        return view('livewire.kelola-rekomendasi.edit', [
            'title' => 'Edit Rekomendasi',
            'rekomendasi' => $rekomendasi,
            'unit_kerja' => $unit_kerja,
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
                    'dokumen_tindak_lanjut' => 'Belum Diunggah!',
                    'status_tindak_lanjut' => 'Proses'
                ]);
            } else {
                TindakLanjut::where('id', $request->id[$key])->update([
                    'tindak_lanjut' => $tindak_lanjut,
                    'unit_kerja' => $request->unit_kerja[$key],
                    'tim_pemantauan' => $request->tim_pemantauan[$key],
                    'tenggat_waktu' => $request->tenggat_waktu[$key],
                    'dokumen_tindak_lanjut' => 'Belum Diunggah!',
                    'status_tindak_lanjut' => 'Proses'
                ]);
            }}

        return redirect('/kelola-rekomendasi/'.$rekomendasi->id)->with('update', 'Data berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rekomendasi $rekomendasi)
    {

        TindakLanjut::where('rekomendasi_id', $rekomendasi->id)->delete();
        Rekomendasi::destroy($rekomendasi->id);

        return redirect('/kelola-rekomendasi')->with('delete', 'Data berhasil dihapus!');
    }
}
