<?php

namespace App\Http\Controllers;

use App\Models\Rekomendasi;
use App\Models\TindakLanjut;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;

        if ($role === 'Tim Koordinator' || $role === 'Super Admin') { // data rekomendasi
            $data = Rekomendasi::all();
            $data_sesuai = $data->where('status_rekomendasi', 'Sesuai');
            $data_belum_sesuai = $data->whereIn('status_rekomendasi', ['Belum Sesuai']);
            $data_belum_ditindaklanjuti = $data->where('status_rekomendasi', 'Belum Ditindaklanjuti');
            $data_tidak_dapat_ditindaklanjuti = $data->where('status_rekomendasi', 'Tidak Dapat Ditindaklanjuti');
            $jumlah_data_per_tahun = Rekomendasi::select(DB::raw('tahun_pemeriksaan as tahun'), DB::raw('COUNT(*) as jumlah'))
            ->groupBy('tahun_pemeriksaan')
            ->orderBy('tahun_pemeriksaan', 'asc')
            ->get();
        } else if ($role === 'Operator Unit Kerja') { // data tindak lanjut berdasarkan unit kerja
            $unit_kerja = Auth::user()->unit_kerja;

            $data = TindakLanjut::where('unit_kerja', $unit_kerja)->get();
            $data_sesuai = $data->where('status_tindak_lanjut', 'Sesuai');
            $data_belum_sesuai = $data->whereIn('status_tindak_lanjut', ['Belum Sesuai', 'Identifikasi']);
            $data_belum_ditindaklanjuti = $data->where('status_tindak_lanjut', 'Belum Ditindaklanjuti');
            $data_tidak_dapat_ditindaklanjuti = $data->where('status_tindak_lanjut', 'Tidak Dapat Ditindaklanjuti');

            $jumlah_data_per_tahun = TindakLanjut::join('rekomendasi', 'tindak_lanjut.rekomendasi_id', '=', 'rekomendasi.id')
                ->where('tindak_lanjut.unit_kerja', $unit_kerja)
                ->selectRaw('rekomendasi.tahun_pemeriksaan as tahun, COUNT(tindak_lanjut.id) as jumlah')
                ->groupBy('rekomendasi.tahun_pemeriksaan')
                ->orderBy('rekomendasi.tahun_pemeriksaan', 'asc')
                ->get();
        } else if (in_array($role, ['Tim Pemantauan I', 'Tim Pemantauan II', 'Tim Pemantauan III'])) { // data tindak lanjut berdasarkan tim pemantauan
            $tim_pemantauan = Auth::user()->role;

            $data = TindakLanjut::where('tim_pemantauan', $tim_pemantauan)->get();
            $data_sesuai = $data->where('status_tindak_lanjut', 'Sesuai');
            $data_belum_sesuai = $data->whereIn('status_tindak_lanjut', ['Belum Sesuai', 'Identifikasi']);
            $data_belum_ditindaklanjuti = $data->where('status_tindak_lanjut', 'Belum Ditindaklanjuti');
            $data_tidak_dapat_ditindaklanjuti = $data->where('status_tindak_lanjut', 'Tidak Dapat Ditindaklanjuti');

            $jumlah_data_per_tahun = TindakLanjut::join('rekomendasi', 'tindak_lanjut.rekomendasi_id', '=', 'rekomendasi.id')
                ->where('tindak_lanjut.tim_pemantauan', $tim_pemantauan)
                ->selectRaw('rekomendasi.tahun_pemeriksaan as tahun, COUNT(tindak_lanjut.id) as jumlah')
                ->groupBy('rekomendasi.tahun_pemeriksaan')
                ->orderBy('rekomendasi.tahun_pemeriksaan', 'asc')
                ->get();
        }

        return view('dashboard.index', [
            'title' => 'Dashboard',
            'role' => $role,
            'data' => $data,
            'data_sesuai' => $data_sesuai,
            'data_belum_sesuai' => $data_belum_sesuai,
            'data_belum_ditindaklanjuti' => $data_belum_ditindaklanjuti,
            'data_tidak_dapat_ditindaklanjuti' => $data_tidak_dapat_ditindaklanjuti,
            'jumlah_data_per_tahun' => $jumlah_data_per_tahun,
        ]);
    }

}

