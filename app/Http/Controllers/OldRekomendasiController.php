<?php

namespace App\Http\Controllers;

use App\Models\Kamus;
use App\Models\UnitKerja;
use App\Models\Rekomendasi;
use Illuminate\Support\Str;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;
use App\Models\BuktiInputSIPTL;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DetailRekomendasiExport;

class OldRekomendasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // where('is_active', 0) digunakan untuk menampilkan data yang tidak aktif
        $rekomendasi = Rekomendasi::where('is_active', 0)->orderByRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(semester_rekomendasi, ' ', -1), ' ', 1) + 0 DESC")
        ->orderBy('created_at', 'desc')
        ->get();

        return view('old-rekomendasi.index', [
            'title' => 'Daftar Rekomendasi Lama',
            'rekomendasi' => $rekomendasi,
            'semesterRekomendasi' => Rekomendasi::distinct()->pluck('semester_rekomendasi')->toArray(),
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

        return view('old-rekomendasi.create', [
            'title' => 'Tambah Rekomendasi Lama',
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
            'status_rekomendasi' => 'required',
            'semester_rekomendasi' => 'required',
        ]);

        // Validasi file LHP
        if ($request->hasFile('lhp')) {
            $request->validate([
                'lhp' => 'required|mimes:pdf|max:100000',
            ]);
            $lhp = $request->file('lhp');
            $lhpFileName = $lhp->getClientOriginalName();
            // apabila file sudah ada, maka tambahkan angka di belakang nama file
            if (file_exists(public_path('uploads/lhp/' . $lhpFileName))) {
                $lhpFileName = pathinfo($lhpFileName, PATHINFO_FILENAME) . '_' . time() . '.' . $lhp->getClientOriginalExtension();
            }
            $lhp->move(public_path('uploads/lhp'), $lhpFileName);
            $validatedData['lhp'] = $lhpFileName;
        } else {
            return redirect()->back()->withInput()->with('error', 'File LHP tidak diunggah!');
        }

        // memberikan id pada rekomendasi berupa uuid
        $validatedData['id'] = Str::uuid()->toString();

        // mendapatkan semester dan tahun dari semester_rekomendasi
        $bulan = date('n', strtotime($request->semester_rekomendasi));
        $tahun = date('Y', strtotime($request->semester_rekomendasi));
        $semester = $bulan <= 6 ? 'Semester 1' : 'Semester 2';
        $semester_rekomendasi = $semester . ' ' . $tahun;
        // Simpan semester_rekomendasi ke dalam array $validatedData
        $validatedData['semester_rekomendasi'] = $semester_rekomendasi;

        // tambahkan pemutakhiran_by dan pemutakhiran_at, semester_pemutakhiran
        $validatedData['pemutakhiran_by'] = auth()->user()->nama;
        $validatedData['pemutakhiran_at'] = now();
        $validatedData['semester_pemutakhiran'] = $semester_rekomendasi;
        // catatan pemutakhiran jika sesuai maka kosong jika tidak maka diisi
        $validatedData['catatan_pemutakhiran'] = $request->status_rekomendasi === 'Sesuai' ? null : $request->catatan_pemutakhiran;
        // is active
        $validatedData['is_active'] = 0;

        // Aturan validasi untuk entri TindakLanjut
        $tindakLanjutValidationRules = [
            'tindak_lanjut.*' => 'required',
            'unit_kerja.*' => 'required',
            'tim_pemantauan.*' => 'required',
            'tenggat_waktu.*' => 'required',
            'status_tindak_lanjut.*' => 'required',
            'bukti_tindak_lanjut.*' => 'required',
            'detail_bukti_tindak_lanjut.*' => 'required',
        ];

        // Validasi data untuk entri TindakLanjut
        $validatedTindakLanjutData = $request->validate($tindakLanjutValidationRules);

        DB::beginTransaction();

        try {
            // Simpan data rekomendasi ke dalam tabel OldRekomendasi
            $rekomendasi = Rekomendasi::create($validatedData);

            // Simpan data TindakLanjut ke dalam tabel OldTindakLanjut
            foreach ($validatedTindakLanjutData['tindak_lanjut'] as $key => $tindak_lanjut) {

                // Mendapatkan nilai tenggat waktu dari input pengguna
                $tenggat_waktu = $request->tenggat_waktu[$key];

                // Hitung semester tindak lanjut berdasarkan tenggat waktu
                $bulan = date('n', strtotime($tenggat_waktu));
                $tahun = date('Y', strtotime($tenggat_waktu));
                $semester = $bulan <= 6 ? 'Semester 1' : 'Semester 2';
                $semester_tindak_lanjut = $semester . ' ' . $tahun;

                $tindakLanjutData = [
                    'id' => Str::uuid()->toString(),
                    'tindak_lanjut' => $tindak_lanjut,
                    'unit_kerja' => $validatedTindakLanjutData['unit_kerja'][$key],
                    'tim_pemantauan' => $validatedTindakLanjutData['tim_pemantauan'][$key],
                    'tenggat_waktu' => $validatedTindakLanjutData['tenggat_waktu'][$key],
                    'semester_tindak_lanjut' => $semester_tindak_lanjut,
                    'status_tindak_lanjut' => $validatedTindakLanjutData['status_tindak_lanjut'][$key],
                    'status_tindak_lanjut_at' => now(),
                    'status_tindak_lanjut_by' => auth()->user()->nama,
                    'catatan_tindak_lanjut' => null,
                    'bukti_tindak_lanjut' => $validatedTindakLanjutData['bukti_tindak_lanjut'][$key],
                    'upload_by' => auth()->user()->nama,
                    'upload_at' => now(),
                    'detail_bukti_tindak_lanjut' => $validatedTindakLanjutData['detail_bukti_tindak_lanjut'][$key],
                    'rekomendasi_id' => $rekomendasi->id,
                ];

                // simpan file bukti tindak lanjut
                if ($request->hasFile('bukti_tindak_lanjut')) {
                    $file = $request->file('bukti_tindak_lanjut')[$key];
                    $fileName = $file->getClientOriginalName();
                    // apabila file sudah ada, maka tambahkan angka di belakang nama file
                    if (file_exists(public_path('uploads/tindak_lanjut/' . $fileName))) {
                        $fileName = pathinfo($fileName, PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();
                    }
                    $file->move(public_path('uploads/tindak_lanjut'), $fileName);
                    $tindakLanjutData['bukti_tindak_lanjut'] = $fileName;
                }

                TindakLanjut::create($tindakLanjutData);
            }

            if ($request->hasFile('bukti_input_siptl')) {
                $request->validate([
                    'bukti_input_siptl' => 'required|file|mimes:pdf,png,jpg|max:100000',
                ]);
                $file = $request->file('bukti_input_siptl');
                $fileName = $file->getClientOriginalName();
                // apabila file sudah ada, maka tambahkan angka di belakang nama file
                if (file_exists(public_path('uploads/bukti_input_siptl/' . $fileName))) {
                    $fileName = pathinfo($fileName, PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();
                }
                $file->move(public_path('uploads/bukti_input_siptl'), $fileName);

                $buktiInput = BuktiInputSIPTL::where('rekomendasi_id', $rekomendasi->id)->first();

                if ($buktiInput) {
                    $buktiInput->update([
                        'bukti_input_siptl' => $fileName,
                        'detail_bukti_input_siptl' => $request->detail_bukti_input_siptl,
                        'upload_by' => auth()->user()->nama,
                        'upload_at' => now(),
                    ]);
                } else {
                    BuktiInputSIPTL::create([
                        'id' => Str::uuid()->toString(),
                        'bukti_input_siptl' => $fileName,
                        'detail_bukti_input_siptl' => $request->detail_bukti_input_siptl,
                        'upload_by' => auth()->user()->nama,
                        'upload_at' => now(),
                        'rekomendasi_id' => $rekomendasi->id,
                    ]);
                }
            }

            DB::commit();

            return redirect('/old-rekomendasi')->with('create', 'Data berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Rekomendasi $rekomendasi)
    {
        // tindak lanjut dan bukti input SIPTL
        $tindakLanjut = TindakLanjut::where('rekomendasi_id', $rekomendasi->id)->get();
        $buktiInputSIPTL = BuktiInputSIPTL::where('rekomendasi_id', $rekomendasi->id)->first();

        $rekomendasi->tindakLanjut = $tindakLanjut;
        $rekomendasi->buktiInputSIPTL = $buktiInputSIPTL;

        return view('old-rekomendasi.show', [
            'title' => 'Detail Rekomendasi Lama',
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
        $oldRekomendasi = Rekomendasi::with('tindakLanjut')->find($rekomendasi->id);
        $unit_kerja = UnitKerja::all();

        return view('old-rekomendasi.edit', [
            'title' => 'Edit Rekomendasi Lama',
            'kamus_temuan' => $kamus_temuan,
            'kamus_pemeriksaan' => $kamus_pemeriksaan,
            'rekomendasi' => $oldRekomendasi,
            'unit_kerja' => $unit_kerja,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rekomendasi $rekomendasi)
    {

        try {
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
                'semester_rekomendasi' => 'required',
            ]);

            // Validasi file LHP
            if ($request->hasFile('lhp')) {
                $request->validate([
                    'lhp' => 'required|mimes:pdf|max:100000',
                ]);
                $lhp = $request->file('lhp');
                $lhpFileName = $lhp->getClientOriginalName();
                // apabila file sudah ada, maka tambahkan angka di belakang nama file
                if (file_exists(public_path('uploads/lhp/' . $lhpFileName))) {
                    $lhpFileName = pathinfo($lhpFileName, PATHINFO_FILENAME) . '_' . time() . '.' . $lhp->getClientOriginalExtension();
                }
                $lhp->move(public_path('uploads/lhp'), $lhpFileName);

                // hapus file lama jika ada
                if ($rekomendasi->lhp !== null && file_exists(public_path('uploads/lhp/' . $rekomendasi->lhp))) {
                    unlink(public_path('uploads/lhp/' . $rekomendasi->lhp));
                }

                $validatedData['lhp'] = $lhpFileName;
            } else {
                $validatedData['lhp'] = $request->lhp_lama;
            }

            // mendapatkan semester dan tahun dari semester_rekomendasi
            $bulan = date('n', strtotime($request->semester_rekomendasi));
            $tahun = date('Y', strtotime($request->semester_rekomendasi));
            $semester = $bulan <= 6 ? 'Semester 1' : 'Semester 2';
            $semester_rekomendasi = $semester . ' ' . $tahun;
            // Simpan semester_rekomendasi ke dalam array $validatedData
            $validatedData['semester_rekomendasi'] = $semester_rekomendasi;

            // tambahkan pemutakhiran_by dan pemutakhiran_at, semester_pemutakhiran
            $validatedData['pemutakhiran_by'] = auth()->user()->nama;
            $validatedData['pemutakhiran_at'] = now();
            $validatedData['semester_pemutakhiran'] = $semester_rekomendasi;
            // catatan pemutakhiran jika
            $validatedData['catatan_pemutakhiran'] = $request->status_rekomendasi === 'Sesuai' ? null : $request->catatan_pemutakhiran;

            $rekomendasi->update($validatedData);

            // Aturan validasi untuk entri TindakLanjut
            foreach ($request->tindak_lanjut as $key => $tindak_lanjut) {
                $validatedTindakLanjutData = $request->validate([
                    'tindak_lanjut.' . $key => 'required',
                    'unit_kerja.' . $key => 'required',
                    'tim_pemantauan.' . $key => 'required',
                    'tenggat_waktu.' . $key => 'required',
                    'status_tindak_lanjut.' . $key => 'required',
                    'detail_bukti_tindak_lanjut.' . $key => 'required',
                ]);

                // Hitung Semester Tindak Lanjut berdasarkan Tenggat Waktu
                $bulan = date('n', strtotime($request->tenggat_waktu[$key]));
                $tahun = date('Y', strtotime($request->tenggat_waktu[$key]));
                $semester = $bulan <= 6 ? 'Semester 1' : 'Semester 2';
                $semester_tindak_lanjut = $semester . ' ' . $tahun;

                if (isset($request->id[$key]) && $request->id[$key] !== null) {
                    // update tindak lanjut yang sudah ada
                    $oldTindakLanjut = TindakLanjut::find($request->id[$key]);
                    $oldTindakLanjut->update([
                        'tindak_lanjut' => $tindak_lanjut,
                        'unit_kerja' => $validatedTindakLanjutData['unit_kerja'][$key],
                        'tim_pemantauan' => $validatedTindakLanjutData['tim_pemantauan'][$key],
                        'tenggat_waktu' => $validatedTindakLanjutData['tenggat_waktu'][$key],
                        'semester_tindak_lanjut' => $semester_tindak_lanjut,
                        'status_tindak_lanjut' => $validatedTindakLanjutData['status_tindak_lanjut'][$key],
                        'status_tindak_lanjut_at' => now(),
                        'status_tindak_lanjut_by' => auth()->user()->nama,
                        'catatan_tindak_lanjut' => null,
                        'detail_bukti_tindak_lanjut' => $validatedTindakLanjutData['detail_bukti_tindak_lanjut'][$key],
                    ]);

                    // cek apakah file bukti tindak lanjut diunggah
                    if ($request->hasFile('bukti_tindak_lanjut') && isset($request->file('bukti_tindak_lanjut')[$key])) {
                        $file = $request->file('bukti_tindak_lanjut')[$key];
                        $fileName = $file->getClientOriginalName();
                        // apabila file sudah ada, maka tambahkan angka di belakang nama file
                        if (file_exists(public_path('uploads/tindak_lanjut/' . $fileName))) {
                            $fileName = pathinfo($fileName, PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();
                        }
                        $file->move(public_path('uploads/tindak_lanjut'), $fileName);

                        // hapus file lama
                        if ($oldTindakLanjut->bukti_tindak_lanjut !== null && file_exists(public_path('uploads/tindak_lanjut/' . $oldTindakLanjut->bukti_tindak_lanjut))) {
                            unlink(public_path('uploads/tindak_lanjut/' . $oldTindakLanjut->bukti_tindak_lanjut));
                        }

                        // update file bukti tindak lanjut
                        $oldTindakLanjut->update([
                            'bukti_tindak_lanjut' => $fileName,
                        ]);
                    } else {
                        $fileName = $oldTindakLanjut->bukti_tindak_lanjut;
                    }
                } else {
                    // tambahkan tindak lanjut baru
                    $tindakLanjutData = [
                        'id' => Str::uuid()->toString(),
                        'tindak_lanjut' => $tindak_lanjut,
                        'unit_kerja' => $validatedTindakLanjutData['unit_kerja'][$key],
                        'tim_pemantauan' => $validatedTindakLanjutData['tim_pemantauan'][$key],
                        'tenggat_waktu' => $validatedTindakLanjutData['tenggat_waktu'][$key],
                        'semester_tindak_lanjut' => $semester_tindak_lanjut,
                        'status_tindak_lanjut' => $validatedTindakLanjutData['status_tindak_lanjut'][$key],
                        'status_tindak_lanjut_at' => now(),
                        'status_tindak_lanjut_by' => auth()->user()->nama,
                        'catatan_tindak_lanjut' => null,
                        'bukti_tindak_lanjut' => $request->file('bukti_tindak_lanjut')[$key] ?? null,
                        'upload_by' => auth()->user()->nama,
                        'upload_at' => now(),
                        'detail_bukti_tindak_lanjut' => $validatedTindakLanjutData['detail_bukti_tindak_lanjut'][$key],
                        'rekomendasi_id' => $rekomendasi->id,
                    ];

                    // simpan file bukti tindak lanjut
                    if ($request->hasFile('bukti_tindak_lanjut')) {
                        $file = $request->file('bukti_tindak_lanjut')[$key];
                        $fileName = $file->getClientOriginalName();
                        // apabila file sudah ada, maka tambahkan angka di belakang nama file
                        if (file_exists(public_path('uploads/tindak_lanjut/' . $fileName))) {
                            $fileName = pathinfo($fileName, PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();
                        }
                        $file->move(public_path('uploads/tindak_lanjut'), $fileName);
                        $tindakLanjutData['bukti_tindak_lanjut'] = $fileName;
                    }

                    TindakLanjut::create($tindakLanjutData);
                }
            }

            // cek bukti input SIPTL
            if ($request->hasFile('bukti_input_siptl')) {
                $request->validate([
                    'bukti_input_siptl' => 'required|file|mimes:pdf,png,jpg|max:100000',
                ]);

                $file = $request->file('bukti_input_siptl');
                $fileName = $file->getClientOriginalName();
                // apabila file sudah ada, maka tambahkan angka di belakang nama file
                if (file_exists(public_path('uploads/bukti_input_siptl/' . $fileName))) {
                    $fileName = pathinfo($fileName, PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();
                }
                $file->move(public_path('uploads/bukti_input_siptl'), $fileName);

                $buktiInput = BuktiInputSIPTL::where('rekomendasi_id', $rekomendasi->id)->first();

                if ($buktiInput) {

                    // hapus file lama
                    if ($buktiInput->bukti_input_siptl !== null && file_exists(public_path('uploads/bukti_input_siptl/' . $buktiInput->bukti_input_siptl))) {
                        unlink(public_path('uploads/bukti_input_siptl/' . $buktiInput->bukti_input_siptl));
                    }

                    $buktiInput->update([
                        'bukti_input_siptl' => $fileName,
                        'detail_bukti_input_siptl' => $request->detail_bukti_input_siptl,
                        'upload_by' => auth()->user()->nama,
                        'upload_at' => now(),
                    ]);
                } else {
                    BuktiInputSIPTL::create([
                        'id' => Str::uuid()->toString(),
                        'bukti_input_siptl' => $fileName,
                        'detail_bukti_input_siptl' => $request->detail_bukti_input_siptl,
                        'upload_by' => auth()->user()->nama,
                        'upload_at' => now(),
                        'rekomendasi_id' => $rekomendasi->id,
                    ]);
                }
            }

            return redirect('/old-rekomendasi/'.$rekomendasi->id)->with('update', 'Data Berhasil Diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rekomendasi $rekomendasi)
    {
        // hapus file bukti siptl
        $buktiInputSIPTL = BuktiInputSIPTL::where('rekomendasi_id', $rekomendasi->id)->first();
        if ($buktiInputSIPTL) {
            if ($buktiInputSIPTL->bukti_input_siptl !== null && file_exists(public_path('uploads/bukti_input_siptl/' . $buktiInputSIPTL->bukti_input_siptl))) {
                unlink(public_path('uploads/bukti_input_siptl/' . $buktiInputSIPTL->bukti_input_siptl));
            }
            $buktiInputSIPTL->delete();
        }
        // hapus file bukti tindak lanjut
        $tindakLanjut = TindakLanjut::where('rekomendasi_id', $rekomendasi->id)->get();
        foreach ($tindakLanjut as $tindak) {
            if ($tindak->bukti_tindak_lanjut !== null && file_exists(public_path('uploads/tindak_lanjut/' . $tindak->bukti_tindak_lanjut))) {
                unlink(public_path('uploads/tindak_lanjut/' . $tindak->bukti_tindak_lanjut));
            }
        }
        // hapus file lhp
        if ($rekomendasi->lhp !== null && file_exists(public_path('uploads/lhp/' . $rekomendasi->lhp))) {
            unlink(public_path('uploads/lhp/' . $rekomendasi->lhp));
        }
        Rekomendasi::destroy($rekomendasi->id);

        return redirect('/old-rekomendasi')->with('delete', 'Data berhasil dihapus!');
    }

    /**
     * Export data rekomendasi to Excel
     */
    public function export(Rekomendasi $rekomendasi)
    {
        $rekomendasi = Rekomendasi::findOrFail($rekomendasi->id);

        $rekomendasiName = 'Rekomendasi_' . $rekomendasi->semester_rekomendasi . '_' . $rekomendasi->id;

        $fileName = $rekomendasiName . '.xlsx';

        return Excel::download(new DetailRekomendasiExport($rekomendasi->id), $fileName);
    }
}
