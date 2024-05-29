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
            $data_tidak_dapat_ditindaklanjuti = $data->where('status_rekomendasi', 'Tidak Ditindaklanjuti');
            $jumlah_data_per_tahun = Rekomendasi::select(DB::raw('tahun_pemeriksaan as tahun'), DB::raw('COUNT(*) as jumlah'))
            ->groupBy('tahun_pemeriksaan')
            ->orderBy('tahun_pemeriksaan', 'asc')
            ->get();

            $tindakLanjutList = TindakLanjut::all();

            // Inisialisasi objek $unitKerjaList
            $unitKerjaList = [];

            // Kelompokkan data tindak lanjut berdasarkan unit kerja
            $groupedTindakLanjut = $tindakLanjutList->groupBy('unit_kerja');

            // Loop untuk setiap kelompok unit kerja
            foreach ($groupedTindakLanjut as $unitKerja => $tindakLanjut) {
                // Hitung jumlah rekomendasi untuk unit kerja ini
                $jumlahRekomendasi = 0;

                // Hitung jumlah tindak lanjut untuk unit kerja ini berdasarkan status
                $jumlahSesuai = 0;
                $jumlahBelumSesuai = 0;
                $jumlahBelumDitindaklanjuti = 0;
                $jumlahTidakDitindaklanjuti = 0;

                // Loop untuk setiap tindak lanjut
                foreach ($tindakLanjut as $tindak) {
                    // Hitung jumlah rekomendasi unik untuk tindak lanjut ini
                    $jumlahRekomendasi += $data->where('id', $tindak->rekomendasi_id)->count();

                    // Hitung jumlah tindak lanjut berdasarkan status
                    if ($tindak->status_tindak_lanjut == 'Sesuai') {
                        $jumlahSesuai++;
                    } elseif ($tindak->status_tindak_lanjut == 'Belum Sesuai') {
                        $jumlahBelumSesuai++;
                    } elseif ($tindak->status_tindak_lanjut == 'Belum Ditindaklanjuti') {
                        $jumlahBelumDitindaklanjuti++;
                    } elseif ($tindak->status_tindak_lanjut == 'Tidak Ditindaklanjuti') {
                        $jumlahTidakDitindaklanjuti++;
                    }
                }

                // Hitung persentase penyelesaian
                $totalTindakLanjut = $jumlahSesuai + $jumlahBelumSesuai + $jumlahBelumDitindaklanjuti + $jumlahTidakDitindaklanjuti;
                $persentasePenyelesaian = ($jumlahSesuai / $totalTindakLanjut) * 100;

                // Hitung jumlah yang sudah upload
                $sudahUpload = $tindakLanjut->whereNotNull('upload_at')->count();

                // Simpan data ke dalam objek $unitKerjaList
                $unitKerjaList[] = [
                    'unit_kerja' => $unitKerja,
                    'jumlah_rekomendasi' => $jumlahRekomendasi,
                    'jumlah_sesuai' => $jumlahSesuai,
                    'jumlah_belum_sesuai' => $jumlahBelumSesuai,
                    'jumlah_belum_ditindaklanjuti' => $jumlahBelumDitindaklanjuti,
                    'jumlah_tidak_ditindaklanjuti' => $jumlahTidakDitindaklanjuti,
                    'persentase_penyelesaian' => $persentasePenyelesaian,
                    'sudah_upload' => $sudahUpload,
                ];
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
                'unitKerjaList' => $unitKerjaList,
            ]);

        } else if ($role === 'Operator Unit Kerja') { // data tindak lanjut berdasarkan unit kerja
            $unit_kerja = Auth::user()->unit_kerja;

            $data = TindakLanjut::where('unit_kerja', $unit_kerja)->get();
            $data_sesuai = $data->where('status_tindak_lanjut', 'Sesuai');
            $data_belum_sesuai = $data->whereIn('status_tindak_lanjut', ['Belum Sesuai', 'Identifikasi']);
            $data_belum_ditindaklanjuti = $data->where('status_tindak_lanjut', 'Belum Ditindaklanjuti');
            $data_tidak_dapat_ditindaklanjuti = $data->where('status_tindak_lanjut', 'Tidak Ditindaklanjuti');

            $jumlah_data_per_tahun = TindakLanjut::join('rekomendasi', 'tindak_lanjut.rekomendasi_id', '=', 'rekomendasi.id')
                ->where('tindak_lanjut.unit_kerja', $unit_kerja)
                ->selectRaw('rekomendasi.tahun_pemeriksaan as tahun, COUNT(tindak_lanjut.id) as jumlah')
                ->groupBy('rekomendasi.tahun_pemeriksaan')
                ->orderBy('rekomendasi.tahun_pemeriksaan', 'asc')
                ->get();

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

        } else if (in_array($role, ['Tim Pemantauan Wilayah I', 'Tim Pemantauan Wilayah II', 'Tim Pemantauan Wilayah III'])) { // data tindak lanjut berdasarkan tim pemantauan
            $tim_pemantauan = Auth::user()->role;

            $data = TindakLanjut::where('tim_pemantauan', $tim_pemantauan)->get();
            $data_sesuai = $data->where('status_tindak_lanjut', 'Sesuai');
            $data_belum_sesuai = $data->whereIn('status_tindak_lanjut', ['Belum Sesuai', 'Identifikasi']);
            $data_belum_ditindaklanjuti = $data->where('status_tindak_lanjut', 'Belum Ditindaklanjuti');
            $data_tidak_dapat_ditindaklanjuti = $data->where('status_tindak_lanjut', 'Tidak Ditindaklanjuti');
            $data_sudah_diidentifikasi = $data->where('status_tindak_lanjut_at', '!=', null);
            $data_belum_diidentifikasi = $data->where('status_tindak_lanjut_at', null);

            $jumlah_data_per_tahun = TindakLanjut::join('rekomendasi', 'tindak_lanjut.rekomendasi_id', '=', 'rekomendasi.id')
                ->where('tindak_lanjut.tim_pemantauan', $tim_pemantauan)
                ->selectRaw('rekomendasi.tahun_pemeriksaan as tahun, COUNT(tindak_lanjut.id) as jumlah')
                ->groupBy('rekomendasi.tahun_pemeriksaan')
                ->orderBy('rekomendasi.tahun_pemeriksaan', 'asc')
                ->get();

                $tindakLanjutList = $data;

                // Inisialisasi objek $unitKerjaList
                $unitKerjaList = [];

                // Kelompokkan data tindak lanjut berdasarkan unit kerja
                $groupedTindakLanjut = $tindakLanjutList->groupBy('unit_kerja');

                // Loop untuk setiap kelompok unit kerja
                foreach ($groupedTindakLanjut as $unitKerja => $tindakLanjut) {
                    // Hitung jumlah tindak lanjut untuk unit kerja ini
                    $jumlahTindakLanjut = 0;

                    // Hitung jumlah tindak lanjut untuk unit kerja ini berdasarkan status
                    $jumlahSesuai = 0;
                    $jumlahBelumSesuai = 0;
                    $jumlahBelumDitindaklanjuti = 0;
                    $jumlahTidakDitindaklanjuti = 0;

                    // Loop untuk setiap tindak lanjut
                    foreach ($tindakLanjut as $tindak) {
                        // Hitung jumlah tindak lanjut unit kerja ini
                        $jumlahTindakLanjut++;

                        // Hitung jumlah tindak lanjut berdasarkan status
                        if ($tindak->status_tindak_lanjut == 'Sesuai') {
                            $jumlahSesuai++;
                        } elseif ($tindak->status_tindak_lanjut == 'Belum Sesuai') {
                            $jumlahBelumSesuai++;
                        } elseif ($tindak->status_tindak_lanjut == 'Belum Ditindaklanjuti') {
                            $jumlahBelumDitindaklanjuti++;
                        } elseif ($tindak->status_tindak_lanjut == 'Tidak Ditindaklanjuti') {
                            $jumlahTidakDitindaklanjuti++;
                        }
                    }

                    // Hitung persentase penyelesaian
                    $totalTindakLanjut = $jumlahSesuai + $jumlahBelumSesuai + $jumlahBelumDitindaklanjuti + $jumlahTidakDitindaklanjuti;
                    $persentasePenyelesaian = ($jumlahSesuai / $totalTindakLanjut) * 100;

                    // Hitung jumlah yang sudah upload
                    $sudahUpload = $tindakLanjut->whereNotNull('upload_at')->count();

                    // Simpan data ke dalam objek $unitKerjaList
                    $unitKerjaList[] = [
                        'unit_kerja' => $unitKerja,
                        'jumlah_tindak_lanjut' => $jumlahTindakLanjut,
                        'jumlah_sesuai' => $jumlahSesuai,
                        'jumlah_belum_sesuai' => $jumlahBelumSesuai,
                        'jumlah_belum_ditindaklanjuti' => $jumlahBelumDitindaklanjuti,
                        'jumlah_tidak_ditindaklanjuti' => $jumlahTidakDitindaklanjuti,
                        'persentase_penyelesaian' => $persentasePenyelesaian,
                        'sudah_upload' => $sudahUpload,
                    ];
                }

                return view('dashboard.pemantauan', [
                    'title' => 'Dashboard',
                    'role' => $role,
                    'data' => $data,
                    'data_sesuai' => $data_sesuai,
                    'data_belum_sesuai' => $data_belum_sesuai,
                    'data_belum_ditindaklanjuti' => $data_belum_ditindaklanjuti,
                    'data_tidak_dapat_ditindaklanjuti' => $data_tidak_dapat_ditindaklanjuti,
                    'jumlah_data_per_tahun' => $jumlah_data_per_tahun,
                    'data_sudah_diidentifikasi' => $data_sudah_diidentifikasi,
                    'data_belum_diidentifikasi' => $data_belum_diidentifikasi,
                    'unitKerjaList' => $unitKerjaList,
                ]);
        } else {
            // Jika kondisi else if tidak terpenuhi, buat variabel kosong atau null
            $data = collect(); // atau null
            $data_sesuai = collect(); // atau null
            $data_belum_sesuai = collect(); // atau null
            $data_belum_ditindaklanjuti = collect(); // atau null
            $data_tidak_dapat_ditindaklanjuti = collect(); // atau null
            $jumlah_data_per_tahun = collect(); // atau null

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

}

