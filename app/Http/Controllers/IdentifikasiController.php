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
        // urutkan berdasarkan data terbaru
        $tindak_lanjut = TindakLanjut::all()->sortByDesc('created_at');

        return view('identifikasi.index', [
            'title' => 'Identifikasi Tindak Lanjut',
            'tindak_lanjut' => $tindak_lanjut,
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
