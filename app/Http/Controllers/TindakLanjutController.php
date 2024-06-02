<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Rekomendasi;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;
use Novay\WordTemplate\WordTemplate;
use App\Notifications\BuktiTindakLanjutNotification;
use App\Notifications\DeadlineTindakLanjutNotification;
use Illuminate\Support\Facades\Notification;

class TindakLanjutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tindakLanjut = TindakLanjut::orderByRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(semester_tindak_lanjut, ' ', -1), ' ', 1) + 0 DESC")
        ->orderByRaw("CASE WHEN bukti_tindak_lanjut = 'Belum Diunggah!' THEN 0 ELSE 1 END")
        ->orderBy('created_at', 'desc')
        ->get();

        return view('tindak-lanjut.index', [
            'title' => 'Daftar Tindak Lanjut',
            'tindak_lanjut' => $tindakLanjut,
            'semesterTindakLanjut' => TindakLanjut::distinct()->pluck('semester_tindak_lanjut')->toArray(),
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
                'bukti_tindak_lanjut' => 'required|file|mimes:pdf,zip,rar|max:100000',
                'detail_bukti_tindak_lanjut' => 'required',
            ]);

            // Memeriksa apakah file telah diunggah
            if ($request->hasFile('bukti_tindak_lanjut')) {
                $file = $request->file('bukti_tindak_lanjut');
                $fileName = $file->getClientOriginalName();
                // apabila file sudah ada, maka tambahkan angka di belakang nama file
                if (file_exists(public_path('uploads/tindak_lanjut/' . $fileName))) {
                    $fileName = pathinfo($fileName, PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();
                }
                $file->move(public_path('uploads/tindak_lanjut'), $fileName);

                // hapus file lama jika ada
                if ($tindakLanjut->bukti_tindak_lanjut !== null && file_exists(public_path('uploads/tindak_lanjut/' . $tindakLanjut->bukti_tindak_lanjut))) {
                    unlink(public_path('uploads/tindak_lanjut/' . $tindakLanjut->bukti_tindak_lanjut));
                }

                // Update informasi tindak lanjut
                $tindakLanjut->update([
                    'bukti_tindak_lanjut' => $fileName,
                    'detail_bukti_tindak_lanjut' => $request->detail_bukti_tindak_lanjut,
                    'upload_by' => auth()->user()->nama,
                    'upload_at' => now(),
                    'status_tindak_lanjut' => 'Belum Sesuai',
                    'status_tindak_lanjut_at' => null,
                    'status_tindak_lanjut_by' => null,
                    'catatan_tindak_lanjut' => null
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

    // fungsi mirip dnegan update namun hanya mengupdate tenggat waktu
    public function updateDeadline(Request $request, TindakLanjut $tindakLanjut)
    {
        try {
            $request->validate([
                'tenggat_waktu' => 'required|date',
            ]);

            $tindakLanjut->update([
                'tenggat_waktu' => $request->tenggat_waktu,
            ]);

            // kirim notifikasi ke user yang memiliki role yang sesuai dengan $tindakLanjut->unit_kerja bahwa tenggat waktu telah diubah
            $usersWithRole = User::where('unit_kerja', $request->unit_kerja)
            ->get();

            Notification::send($usersWithRole, new DeadlineTindakLanjutNotification($tindakLanjut));

            return redirect('/tindak-lanjut/' . $tindakLanjut->id)->with('update', 'Tenggat Waktu Berhasil Diubah!');
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();

            return redirect()->back()->withInput()->with('error', $errorMessage);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TindakLanjut $tindakLanjut)
    {
        // Cari data tindak lanjut berdasarkan ID
        $tindakLanjut = TindakLanjut::find($tindakLanjut->id);

        // Periksa apakah data ditemukan
        if (!$tindakLanjut) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Hapus file bukti tindak lanjut
        if ($tindakLanjut->bukti_tindak_lanjut !== null && file_exists(public_path('uploads/tindak_lanjut/' . $tindakLanjut->bukti_tindak_lanjut))) {
            unlink(public_path('uploads/tindak_lanjut/' . $tindakLanjut->bukti_tindak_lanjut));
        }

        // Hapus data
        $tindakLanjut->delete();

        return redirect()->back()->with('success', 'Tindak Lanjut berhasil dihapus.');
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
