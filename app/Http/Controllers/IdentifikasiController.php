<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rekomendasi;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BuktiSIPTLNotification;
use App\Notifications\IdentifikasiNotification;

class IdentifikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tindakLanjut = TindakLanjut::orderByRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(semester_tindak_lanjut, ' ', -1), ' ', 1) + 0 DESC")
        ->orderByRaw("CASE WHEN bukti_tindak_lanjut = 'Belum Diunggah!' THEN 0 ELSE 1 END")
        ->orderByRaw("CASE WHEN status_tindak_lanjut_at IS NULL THEN 0 ELSE 1 END")
        ->orderBy('created_at', 'desc')
        ->get();

        return view('identifikasi.index', [
            'title' => 'Daftar Identifikasi Tindak Lanjut',
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

        // Kirim notifikasi identifikasi kepada unit kerja yang sesuai
        Notification::send(User::where('unit_kerja', $tindakLanjut->unit_kerja)->get(), new IdentifikasiNotification($tindakLanjut));

        // Ambil rekomendasi berdasarkan ID dari tindak lanjut
        $rekomendasi = Rekomendasi::find($tindakLanjut->rekomendasi_id);

        if ($rekomendasi) {
            // Ambil semua tindak lanjut dari rekomendasi
            $tindakLanjutRekomendasi = $rekomendasi->tindakLanjut;

            if ($tindakLanjutRekomendasi->isNotEmpty()) {
                // Gunakan metode every pada koleksi untuk memeriksa apakah semua tindak lanjut sesuai
                $semuaSesuai = $tindakLanjutRekomendasi->every(function ($tindakLanjut) {
                    return $tindakLanjut->status_tindak_lanjut === 'Sesuai';
                });

                if ($semuaSesuai) {
                    // Kirim notifikasi upload bukti SIPTL kepada tim koordinator
                    Notification::send(User::where('role', 'Tim Koordinator')->get(), new BuktiSIPTLNotification($tindakLanjut->rekomendasi_id));
                }
            }
        } else {
            return redirect('/identifikasi/' . $tindakLanjut->id)->with('update', 'Update Status Berhasil!');
        }


        return redirect('/identifikasi/' . $tindakLanjut->id)->with('update', 'Update Status Berhasil!');
    }


}
