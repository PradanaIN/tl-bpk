<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Rekomendasi;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;
use Novay\WordTemplate\WordTemplate;
use App\Notifications\BuktiTindakLanjutNotification;
use Illuminate\Support\Facades\Notification;

class TindakLanjutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('tindak-lanjut.index', [
            'title' => 'Daftar Tindak Lanjut',
            'tindak_lanjut' => TindakLanjut::all(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(TindakLanjut $tindakLanjut)
    {
        auth()->user()->unreadNotifications->where('data.tindak_lanjut_id', $tindakLanjut->id)->markAsRead();
        $rekomendasi = Rekomendasi::find($tindakLanjut->rekomendasi_id);

        return view('tindak-lanjut.show', [
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
        try {
            // Validasi jenis file
            $request->validate([
                'bukti_tindak_lanjut' => 'required|file|mimes:pdf,zip,rar,tar',
                'detail_bukti_tindak_lanjut' => 'required',
            ]);

            // Memeriksa apakah file telah diunggah
            if ($request->hasFile('bukti_tindak_lanjut')) {
                $file = $request->file('bukti_tindak_lanjut');
                $currentTime = now()->format('dmY');
                $fileName = $currentTime . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/tindak_lanjut'), $fileName);

                // Update informasi tindak lanjut
                $tindakLanjut->update([
                    'bukti_tindak_lanjut' => $fileName,
                    'detail_bukti_tindak_lanjut' => $request->detail_bukti_tindak_lanjut,
                    'upload_by' => auth()->user()->nama,
                    'upload_at' => now(),
                ]);

            // Ambil semua user yang memiliki role yang sesuai dengan $tindakLanjut->tim_pemantauan
            $usersWithRole = User::whereHas('roles', function($query) use ($tindakLanjut) {
                $query->where('name', $tindakLanjut->tim_pemantauan);
            })->get();

            // Kirim notifikasi hanya kepada user yang memiliki role yang sesuai
            Notification::send($usersWithRole, new BuktiTindakLanjutNotification($tindakLanjut));

                // Redirect dengan pesan sukses
                return redirect('/tindak-lanjut/' . $tindakLanjut->id)->with('update', 'Upload Berhasil!');
            } else {
                // Jika tidak ada file yang diunggah, kembalikan pesan error
                throw new \Exception('Tidak ada file yang diunggah.');
            }
        } catch (\Exception $e) {
            // Tangani error
            $errorMessage = $e->getMessage(); // Dapatkan pesan error

            // Tampilkan SweetAlert dengan pesan error
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }
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
