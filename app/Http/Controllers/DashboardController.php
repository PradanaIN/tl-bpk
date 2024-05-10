<?php

namespace App\Http\Controllers;

use App\Models\Rekomendasi;
use App\Models\TindakLanjut;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $rekomendasi = Rekomendasi::all();

        $rekomendasi_sesuai = Rekomendasi::where('status_rekomendasi', 'Sesuai')->get();
        $rekomendasi_belum_sesuai = Rekomendasi::whereIn('status_rekomendasi', ['Belum Sesuai'])->get();
        $rekomendasi_belum_ditindaklanjuti = Rekomendasi::where('status_rekomendasi', 'Belum Ditindaklanjuti')->get();
        $rekomendasi_tidak_dapat_ditindaklanjuti = Rekomendasi::where('status_rekomendasi', 'Tidak Dapat Ditindaklanjuti')->get();

        // Agregasi data jumlah rekomendasi per tahun pemeriksaan
        $jumlah_rekomendasi_per_tahun = Rekomendasi::select(DB::raw('tahun_pemeriksaan as tahun'), DB::raw('COUNT(*) as jumlah_rekomendasi'))
            ->groupBy('tahun_pemeriksaan')
            ->orderBy('tahun_pemeriksaan', 'asc') // Urutkan tahun dari terkecil ke terbesar
            ->get();


        return view('dashboard.index', [
            'title' => 'Dashboard',
            'rekomendasi' => $rekomendasi,
            'rekomendasi_sesuai' => $rekomendasi_sesuai,
            'rekomendasi_belum_sesuai' => $rekomendasi_belum_sesuai,
            'rekomendasi_belum_ditindaklanjuti' => $rekomendasi_belum_ditindaklanjuti,
            'rekomendasi_tidak_dapat_ditindaklanjuti' => $rekomendasi_tidak_dapat_ditindaklanjuti,
            'jumlah_rekomendasi_per_tahun' => $jumlah_rekomendasi_per_tahun,
        ]);
    }
}
