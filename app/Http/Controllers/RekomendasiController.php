<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kamus;
use App\Models\UnitKerja;
use App\Models\Rekomendasi;
use Illuminate\Support\Str;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;
use App\Models\BuktiInputSIPTL;
use App\Models\BuktiTindakLanjut;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DetailRekomendasiExport;
use App\Notifications\RekomendasiNotification;

class RekomendasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rekomendasi = Rekomendasi::orderByRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(semester_rekomendasi, ' ', -1), ' ', 1) + 0 DESC")
        ->orderBy('tahun_pemeriksaan', 'desc')
        ->orderBy('created_at', 'desc')
        ->get();

        $semesterRekomendasi = Rekomendasi::distinct()
        ->pluck('semester_rekomendasi')
        ->toArray();

        // Urutkan koleksi secara manual
        usort($semesterRekomendasi, function($a, $b) {
            // Pisahkan tahun dan semester dari string
            $tahunA = substr($a, 10);
            $tahunB = substr($b, 10);
            $semesterA = substr($a, 9, 1);
            $semesterB = substr($b, 9, 1);

            // Urutkan berdasarkan tahun secara menurun
            if ($tahunA != $tahunB) {
                return $tahunB - $tahunA;
            }

            // Jika tahun sama, urutkan berdasarkan semester secara menurun
            return $semesterB - $semesterA;
        });

        return view('rekomendasi.index', [
            'title' => 'Daftar Rekomendasi',
            'rekomendasi' => $rekomendasi,
            'semesterRekomendasi' => $semesterRekomendasi,
            'kamus_pemeriksaan' => Kamus::where('jenis', 'Pemeriksaan')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kamus_temuan = Kamus::where('jenis', 'Temuan')->get();
        $kamus_pemeriksaan = Kamus::where('jenis', 'Pemeriksaan')->get();
        $unit_kerja = UnitKerja::all();

        return view('rekomendasi.create', [
            'title' => 'Tambah Rekomendasi',
            'unit_kerja' => $unit_kerja,
            'kamus_temuan' => $kamus_temuan,
            'kamus_pemeriksaan' => $kamus_pemeriksaan,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Aturan validasi untuk entri Rekomendasi
        $validatedData = $request->validate([
            'pemeriksaan' => 'required',
            'jenis_pemeriksaan' => 'required',
            'tahun_pemeriksaan' => 'required|integer|min:1900|max:2099',
            'jenis_temuan' => 'required',
            'uraian_temuan' => 'required',
            'rekomendasi' => 'required',
            'catatan_rekomendasi' => 'required',
            'lhp' => 'required|mimes:pdf|max:100000',
            'status_rekomendasi' => 'required',
        ]);

        // Validasi file LHP
        if ($request->hasFile('lhp')) {
            $lhp = $request->file('lhp');
            $lhpFileName = $lhp->getClientOriginalName();
            // apabila nama file sudah ada, maka tambahkan angka di belakang nama file
            if (file_exists(public_path('uploads/lhp/' . $lhpFileName))) {
                $lhpFileName = pathinfo($lhpFileName, PATHINFO_FILENAME) . '_' . time() . '.' . $lhp->getClientOriginalExtension();
            }
            $lhp->move(public_path('uploads/lhp'), $lhpFileName);
            // Simpan nama file LHP ke dalam array $validatedData
            $validatedData['lhp'] = $lhpFileName;
        } else {
            return redirect()->back()->withInput()->with('error', 'File LHP tidak diunggah!');
        }
        // memberikan id pada rekomendasi berupa uuid
        $validatedData['id'] = Str::uuid()->toString();

        // Menentukan semester rekomendasi
        $tahun = date('Y');
        $bulan = date('n');
        // Tentukan semester berdasarkan bulan
        $semester = $bulan <= 6 ? 'Semester 1' : 'Semester 2';
        // Gabungkan semester dengan tahun
        $semester_tahun = $semester . ' ' . $tahun;
        // Assign ke validatedData
        $validatedData['semester_rekomendasi'] = $semester_tahun;
        // rekomendasi semester sebelumnya
        $validatedData['rekomendasi_old_id'] = null;
        // is active
        $validatedData['is_active'] = 1;

        // Aturan validasi untuk entri TindakLanjut
        $tindakLanjutValidationRules = [
            'tindak_lanjut.*' => 'required',
            'unit_kerja.*' => 'required',
            'tim_pemantauan.*' => 'required',
            'tenggat_waktu.*' => 'required',
        ];

        // Validasi data untuk entri TindakLanjut
        $validatedTindakLanjutData = $request->validate($tindakLanjutValidationRules);

        DB::beginTransaction();

        try {
            // Buat entri Rekomendasi berdasarkan data yang divalidasi
            $rekomendasi = Rekomendasi::create($validatedData);

            foreach ($request->tindak_lanjut as $key => $tindak_lanjut) {
                // Mendapatkan nilai tenggat waktu dari input pengguna
                $tenggat_waktu = $request->tenggat_waktu[$key];

                // Hitung semester tindak lanjut berdasarkan tenggat waktu
                $bulan = date('n', strtotime($tenggat_waktu));
                $tahun = date('Y', strtotime($tenggat_waktu));
                $semester = $bulan <= 6 ? 'Semester 1' : 'Semester 2';
                $semester_tindak_lanjut = $semester . ' ' . $tahun;

                // Simpan data tindak lanjut beserta semester tindak lanjut ke dalam database
                TindakLanjut::create([
                    'id' => Str::uuid()->toString(),
                    'rekomendasi_id' => $rekomendasi->id,
                    'tindak_lanjut' => $tindak_lanjut,
                    'unit_kerja' => $request->unit_kerja[$key],
                    'tim_pemantauan' => $request->tim_pemantauan[$key],
                    'tenggat_waktu' => $tenggat_waktu,
                    'semester_tindak_lanjut' => $semester_tindak_lanjut,
                    'status_tindak_lanjut' => 'Belum Sesuai',
                    'bukti_tindak_lanjut' => 'Belum Diunggah!',
                ]);

                // Kirim notifikasi ke pengguna terkait
                $usersWithRole = User::where('role', $request->tim_pemantauan[$key])
                    ->orWhere('unit_kerja', $request->unit_kerja[$key])
                    ->get();

                foreach ($usersWithRole as $user) {
                    $user->notify(new RekomendasiNotification(TindakLanjut::latest()->first()));
                }
            }

            // masukkan value id rekomendasi ke dalam tabel bukti_input_siptl
            BuktiInputSIPTL::create([
                'id' => Str::uuid()->toString(),
                'bukti_input_siptl' => 'Belum Diunggah!',
                'rekomendasi_id' => $rekomendasi->id,
            ]);

            DB::commit();

            return redirect('/rekomendasi')->with('create', 'Data berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Tangani error
            $errorMessage = $e->getMessage(); // Dapatkan pesan error

            return redirect()->back()->withInput()->with('error', $errorMessage);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Rekomendasi $rekomendasi)
    {

        $rekomendasi = Rekomendasi::with('tindakLanjut')->find($rekomendasi->id);

        return view('rekomendasi.show', [
            'title' => 'Detail Rekomendasi',
            'rekomendasi' => $rekomendasi,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rekomendasi $rekomendasi)
    {
        $kamus_temuan = Kamus::where('jenis', 'Temuan')->get();
        $kamus_pemeriksaan = Kamus::where('jenis', 'Pemeriksaan')->get();
        $rekomendasi = Rekomendasi::with('tindakLanjut')->find($rekomendasi->id);
        $unit_kerja = UnitKerja::all();

        return view('rekomendasi.edit', [
            'title' => 'Edit Rekomendasi',
            'kamus_temuan' => $kamus_temuan,
            'kamus_pemeriksaan' => $kamus_pemeriksaan,
            'rekomendasi' => $rekomendasi,
            'unit_kerja' => $unit_kerja,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rekomendasi $rekomendasi)
    {
        try {
            // Validasi data rekomendasi
            $validatedData = $request->validate([
                'pemeriksaan' => 'required',
                'jenis_pemeriksaan' => 'required',
                'tahun_pemeriksaan' => 'required|integer|min:1900|max:2099',
                'jenis_temuan' => 'required',
                'uraian_temuan' => 'required',
                'rekomendasi' => 'required',
                'catatan_rekomendasi' => 'required',
                'status_rekomendasi' => 'required'
            ]);

            // Validasi file LHP
            if ($request->hasFile('lhp')) {
                $request->validate([
                    'lhp' => 'required|mimes:pdf|max:100000',
                ]);
                $lhp = $request->file('lhp');
                $lhpFileName = $lhp->getClientOriginalName();
                // apabila nama file sudah ada, maka tambahkan angka di belakang nama file
                if (file_exists(public_path('uploads/lhp/' . $lhpFileName))) {
                    $lhpFileName = pathinfo($lhpFileName, PATHINFO_FILENAME) . '_' . time() . '.' . $lhp->getClientOriginalExtension();
                }
                $lhp->move(public_path('uploads/lhp'), $lhpFileName);

                // Hapus file lama jika ada
                if ($rekomendasi->lhp != null && file_exists(public_path('uploads/lhp/' . $rekomendasi->lhp))) {
                    unlink(public_path('uploads/lhp/' . $rekomendasi->lhp));
                }

                $validatedData['lhp'] = $lhpFileName;
            } else {
                $validatedData['lhp'] = $request->lhp_lama;
            }

            // Update data rekomendasi
            $rekomendasi->update($validatedData);

            // Proses tindak lanjut
            foreach ($request->tindak_lanjut as $key => $tindak_lanjut) {
                $validatedTindakLanjutData = $request->validate([
                    'tindak_lanjut.' . $key => 'required',
                    'unit_kerja.' . $key => 'required',
                    'tim_pemantauan.' . $key => 'required',
                    'tenggat_waktu.' . $key => 'required',
                ]);

                // Hitung semester berdasarkan tenggat waktu
                $bulan = date('n', strtotime($request->tenggat_waktu[$key]));
                $tahun = date('Y', strtotime($request->tenggat_waktu[$key]));
                $semester = $bulan <= 6 ? 'Semester 1' : 'Semester 2';
                $semester_tindak_lanjut = $semester . ' ' . $tahun;

                if (isset($request->id[$key]) && $request->id[$key] != null) {
                    // Update tindak lanjut yang sudah ada
                    TindakLanjut::where('id', $request->id[$key])->update([
                        'tindak_lanjut' => $tindak_lanjut,
                        'unit_kerja' => $request->unit_kerja[$key],
                        'tim_pemantauan' => $request->tim_pemantauan[$key],
                        'tenggat_waktu' => $request->tenggat_waktu[$key],
                        'semester_tindak_lanjut' => $semester_tindak_lanjut,
                        'bukti_tindak_lanjut' => $request->bukti_tindak_lanjut[$key],
                        'status_tindak_lanjut' => $request->status_tindak_lanjut[$key],
                    ]);
                } else {
                    // Buat tindak lanjut baru
                    TindakLanjut::create([
                        'id' => Str::uuid()->toString(),
                        'rekomendasi_id' => $rekomendasi->id,
                        'tindak_lanjut' => $tindak_lanjut,
                        'unit_kerja' => $request->unit_kerja[$key],
                        'tim_pemantauan' => $request->tim_pemantauan[$key],
                        'tenggat_waktu' => $request->tenggat_waktu[$key],
                        'semester_tindak_lanjut' => $semester_tindak_lanjut,
                        'bukti_tindak_lanjut' => 'Belum Diunggah!',
                        'status_tindak_lanjut' => 'Belum Sesuai',
                    ]);
                }

                // Kirim notifikasi ke pengguna terkait
                $usersWithRole = User::where('role', $request->tim_pemantauan[$key])
                    ->orWhere('unit_kerja', $request->unit_kerja[$key])
                    ->get();

                foreach ($usersWithRole as $user) {
                    $user->notify(new RekomendasiNotification(TindakLanjut::latest()->first())); // Sesuaikan dengan notifikasi yang ingin dikirim
                }
            }

            // kembali ke halaman detail rekomendasi dengan pesan sukses
            return redirect('/rekomendasi/' . $rekomendasi->id)->with('update', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            // Tangani error
            $errorMessage = $e->getMessage(); // Dapatkan pesan error

            // Tampilkan SweetAlert dengan pesan error
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }
    }

    // edit rekomendasi untuk semester berikutnya
    public function nextSemester(Rekomendasi $rekomendasi)
    {
        $kamus_temuan = Kamus::where('jenis', 'Temuan')->get();
        $kamus_pemeriksaan = Kamus::where('jenis', 'Pemeriksaan')->get();
        $rekomendasi = Rekomendasi::with('tindakLanjut')->find($rekomendasi->id);
        $unit_kerja = UnitKerja::all();

        return view('rekomendasi.nextSemester', [
            'title' => 'Rekomendasi Semester Berikutnya',
            'kamus_temuan' => $kamus_temuan,
            'kamus_pemeriksaan' => $kamus_pemeriksaan,
            'rekomendasi' => $rekomendasi,
            'unit_kerja' => $unit_kerja,
        ]);
    }
    // create rekomendasi untuk semester berikutnya
    public function createNextSemester(Request $request)
    {
        // Aturan validasi untuk entri Rekomendasi
        $validatedData = $request->validate([
            'pemeriksaan' => 'required',
            'jenis_pemeriksaan' => 'required',
            'tahun_pemeriksaan' => 'required|integer|min:1900|max:2099',
            'jenis_temuan' => 'required',
            'uraian_temuan' => 'required',
            'rekomendasi' => 'required',
            'catatan_rekomendasi' => 'required',
            'status_rekomendasi' => 'required',
        ]);

        // Validasi file LHP
        if ($request->hasFile('lhp')) {
            $lhp = $request->file('lhp');
            // Gunakan nama yang sesuai
            $lhpFileName = $lhp->getClientOriginalName();
            // apabila nama file sudah ada, maka tambahkan angka di belakang nama file
            if (file_exists(public_path('uploads/lhp/' . $lhpFileName))) {
                $lhpFileName = pathinfo($lhpFileName, PATHINFO_FILENAME) . '_' . time() . '.' . $lhp->getClientOriginalExtension();
            }
            $lhp->move(public_path('uploads/lhp'), $lhpFileName);

            // Hapus file lama jika ada
            if ($request->lhp != null && file_exists(public_path('uploads/lhp/' . $request->lhp))) {
                unlink(public_path('uploads/lhp/' . $request->lhp));
            }

            // Simpan nama file LHP ke dalam array $validatedData
            $validatedData['lhp'] = $lhpFileName;
        } else {
            $validatedData['lhp'] = $request->lhp_lama;
        }

        // memberikan id pada rekomendasi berupa uuid
        $validatedData['id'] = Str::uuid()->toString();

        // Menentukan semester rekomendasi
        $tahun = date('Y');
        $bulan = date('n');
        // Tentukan semester berdasarkan bulan
        $semester = $bulan <= 6 ? 'Semester 1' : 'Semester 2';
        // Gabungkan semester dengan tahun
        $semester_tahun = $semester . ' ' . $tahun;
        // Assign ke validatedData
        $validatedData['semester_rekomendasi'] = $semester_tahun;

        // rekomendasi semester sebelumnya
        $validatedData['rekomendasi_old_id'] = $request->rekomendasi_old_id;
        // is active
        $validatedData['is_active'] = 1;

        // Aturan validasi untuk entri TindakLanjut
        $tindakLanjutValidationRules = [
            'tindak_lanjut.*' => 'required',
            'unit_kerja.*' => 'required',
            'tim_pemantauan.*' => 'required',
            'tenggat_waktu.*' => 'required',
        ];

        // Validasi data untuk entri TindakLanjut
        $validatedTindakLanjutData = $request->validate($tindakLanjutValidationRules);

        DB::beginTransaction();

        try {
            // Buat entri Rekomendasi berdasarkan data yang divalidasi
            $rekomendasi = Rekomendasi::create($validatedData);

            foreach ($request->tindak_lanjut as $key => $tindak_lanjut) {
                // Mendapatkan nilai tenggat waktu dari input pengguna
                $tenggat_waktu = $request->tenggat_waktu[$key];

                // Hitung semester tindak lanjut berdasarkan tenggat waktu
                $bulan = date('n', strtotime($tenggat_waktu));
                $tahun = date('Y', strtotime($tenggat_waktu));
                $semester = $bulan <= 6 ? 'Semester 1' : 'Semester 2';
                $semester_tindak_lanjut = $semester . ' ' . $tahun;

                // Simpan data tindak lanjut beserta semester tindak lanjut ke dalam database
                TindakLanjut::create([
                    'id' => Str::uuid()->toString(),
                    'rekomendasi_id' => $rekomendasi->id,
                    'tindak_lanjut' => $tindak_lanjut,
                    'unit_kerja' => $request->unit_kerja[$key],
                    'tim_pemantauan' => $request->tim_pemantauan[$key],
                    'tenggat_waktu' => $tenggat_waktu,
                    'semester_tindak_lanjut' => $semester_tindak_lanjut,
                    'status_tindak_lanjut' => 'Belum Sesuai',
                    'bukti_tindak_lanjut' => 'Belum Diunggah!',
                ]);

                // Kirim notifikasi ke pengguna terkait
                $usersWithRole = User::where('role', $request->tim_pemantauan[$key])
                    ->orWhere('unit_kerja', $request->unit_kerja[$key])
                    ->get();

                foreach ($usersWithRole as $user) {
                    $user->notify(new RekomendasiNotification(TindakLanjut::latest()->first()));
                }
            }

            // masukkan value id rekomendasi ke dalam tabel bukti_input_siptl
            BuktiInputSIPTL::create([
                'id' => Str::uuid()->toString(),
                'bukti_input_siptl' => 'Belum Diunggah!',
                'rekomendasi_id' => $rekomendasi->id,
            ]);

            DB::commit();

            return redirect('/rekomendasi')->with('create', 'Data berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Tangani error
            $errorMessage = $e->getMessage(); // Dapatkan pesan error

            return redirect()->back()->withInput()->with('error', $errorMessage);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rekomendasi $rekomendasi)
    {
        // Hapus file bukti input SIPTL
        $buktiInputSIPTL = BuktiInputSIPTL::where('rekomendasi_id', $rekomendasi->id)->first();
        // jika file bukti input SIPTL tidak ada atau "Belum Diunggah!", maka do nothing
        if ($buktiInputSIPTL->bukti_input_siptl !== null && file_exists(public_path('uploads/bukti_input_siptl/' . $buktiInputSIPTL->bukti_input_siptl))) {
            unlink(public_path('uploads/bukti_input_siptl/' . $buktiInputSIPTL->bukti_input_siptl));
        }
        BuktiInputSIPTL::where('rekomendasi_id', $rekomendasi->id)->delete();
        // hapus file tindak lanjut
        $buktiTindakLanjut = TindakLanjut::where('rekomendasi_id', $rekomendasi->id)->get();
        foreach ($buktiTindakLanjut as $bukti) {
            if ($bukti->bukti_tindak_lanjut !== null && file_exists(public_path('uploads/bukti_tindak_lanjut/' . $bukti->bukti_tindak_lanjut))) {
                unlink(public_path('uploads/bukti_tindak_lanjut/' . $bukti->bukti_tindak_lanjut));
            }
        }
        TindakLanjut::where('rekomendasi_id', $rekomendasi->id)->delete();

        // hapus file LHP tapi cek dulu apakah file LHP ada atau tidak
        if ($rekomendasi->lhp != null && file_exists(public_path('uploads/lhp/' . $rekomendasi->lhp))) {
            unlink(public_path('uploads/lhp/' . $rekomendasi->lhp));
        }

        Rekomendasi::destroy($rekomendasi->id);

        return redirect('/rekomendasi')->with('delete', 'Data berhasil dihapus!');
    }

    /**
     * Export the specified resource to Excel.
     */

    public function export(Rekomendasi $rekomendasi)
    {
        $rekomendasi = Rekomendasi::findOrFail($rekomendasi->id);

        $rekomendasiName = 'Rekomendasi_' . $rekomendasi->semester_rekomendasi . '_' . $rekomendasi->id;

        $fileName = $rekomendasiName . '.xlsx';

        return Excel::download(new DetailRekomendasiExport($rekomendasi->id), $fileName);
    }

}
