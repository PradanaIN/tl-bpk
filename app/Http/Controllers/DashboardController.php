<?php

namespace App\Http\Controllers;

use App\Models\Rekomendasi;
use App\Models\TindakLanjut;

class DashboardController extends Controller
{
    public function index()
    {
        $rekomendasi = Rekomendasi::all();
        $tindak_lanjut = TindakLanjut::all();

        $rekomendasi_selesai = Rekomendasi::where('status_rekomendasi', 'Selesai')->get();
        $rekomendasi_belum_selesai = Rekomendasi::where('status_rekomendasi', 'Belum Selesai')->get();
        $rekomendasi_tidak_dapat_ditindaklanjuti = Rekomendasi::where('status_rekomendasi', 'Tidak Dapat Ditindaklanjuti')->get();
        $rekomendasi_belum_selesai_pertahun_pemeriksaan = Rekomendasi::whereIn('status_rekomendasi', ['Belum Sesuai', 'Proses'])->groupBy('tahun_pemeriksaan')->get();

       $tindak_lanjut_belum_selesai = TindakLanjut::where('status_tindak_lanjut', 'Belum Selesai')->get();
       $tindak_lanjut_belum_selesai_unit_kerja = TindakLanjut::where('status_tindak_lanjut', 'Belum Selesai')->groupBy('unit_kerja')->get();


        return view('livewire.dashboard.index', [
            'title' => 'Dashboard',
            'rekomendasi' => $rekomendasi,
            'rekomendasi_selesai' => $rekomendasi_selesai,
            'rekomendasi_belum_selesai' => $rekomendasi_belum_selesai,
            'rekomendasi_tidak_dapat_ditindaklanjuti' => $rekomendasi_tidak_dapat_ditindaklanjuti,
            'rekomendasi_belum_selesai_pertahun_pemeriksaan' => $rekomendasi_belum_selesai_pertahun_pemeriksaan,
            'tindak_lanjut' => $tindak_lanjut,
            'tindak_lanjut_belum_selesai' => $tindak_lanjut_belum_selesai,
            'tindak_lanjut_belum_selesai_unit_kerja' => $tindak_lanjut_belum_selesai_unit_kerja,
        ]);
    }
}
