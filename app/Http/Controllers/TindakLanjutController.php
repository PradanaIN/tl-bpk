<?php

namespace App\Http\Controllers;

use App\Models\Rekomendasi;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;
use Novay\WordTemplate\WordTemplate;

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
        $currentTime = now()->format('dmY');
        $fileName = $currentTime . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/tindak_lanjut'), $fileName);

        $tindakLanjut->update([
            'rekomendasi_id' => $tindakLanjut->rekomendasi_id,
            'tindak_lanjut' => $tindakLanjut->tindak_lanjut,
            'unit_kerja' => $tindakLanjut->unit_kerja,
            'tim_pemantauan' => $tindakLanjut->tim_pemantauan,
            'dokumen_tindak_lanjut' => $fileName,
            'detail_dokumen_tindak_lanjut' => $request->detail_dokumen_tindak_lanjut,
            'upload_by' => auth()->user()->nama,
            'upload_at' => now(),
            'status_tindak_lanjut' => 'Identifikasi',
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

    public static function word(TindakLanjut $tindakLanjut)
    {
        // get berita_acara.rtf ftom public folder
        $file = public_path('berita_acara.rtf');

        $array = array(
			'[NOMOR_SURAT]' => '015/BT/SK/V/2023',
            '[NAMA1]' => 'Budi',
            '[NIP1]' => '1234567890',
            '[JABATAN1]' => 'Inspektur Utama',
            '[NAMA2]' => 'Anduk',
            '[NIP2]' => '0987654321',
            '[JABATAN2]' => 'Kepala Unit Kerja',
            '[TINDAK_LANJUT]' => $tindakLanjut->tindak_lanjut,
            '[UNIT_KERJA]' => $tindakLanjut->unit_kerja,
            '[TENGGAT_WAKTU]' => $tindakLanjut->tenggat_waktu,
            '[KOTA]' => 'Jakarta',
            '[TANGGAL]' => now()->format('d F Y'),
		);

        $nama_file = 'berita-acara.doc';

            // Create an instance of WordTemplate
        $wordTemplate = new WordTemplate();

    // Call the export method on the instance
        return $wordTemplate->export($file, $array, $nama_file);
    }
}
