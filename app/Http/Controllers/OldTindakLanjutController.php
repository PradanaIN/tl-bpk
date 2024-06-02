<?php

namespace App\Http\Controllers;

use App\Models\TindakLanjut;

class OldTindakLanjutController extends Controller
{
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
}
