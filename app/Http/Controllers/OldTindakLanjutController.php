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

        // Hapus data
        $tindakLanjut->delete();

        return redirect()->back()->with('success', 'Tindak Lanjut berhasil dihapus.');
    }
}
