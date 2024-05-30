<?php

namespace App\Http\Controllers;

use App\Models\OldTindakLanjut;
use Illuminate\Http\Request;

class OldTindakLanjutController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OldTindakLanjut $tindakLanjut)
    {
        // Cari data tindak lanjut berdasarkan ID
        $tindakLanjut = OldTindakLanjut::find($tindakLanjut->id);

        // Periksa apakah data ditemukan
        if (!$tindakLanjut) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Hapus file bukti tindak lanjut
        if ($tindakLanjut->bukti_tindak_lanjut) {
            unlink(public_path('uploads/tindak_lanjut/' . $tindakLanjut->bukti_tindak_lanjut));
        }

        // Hapus data
        $tindakLanjut->delete();

        return redirect()->back()->with('success', 'Tindak Lanjut berhasil dihapus.');
    }
}
