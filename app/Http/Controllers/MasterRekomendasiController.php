<?php

namespace App\Http\Controllers;

use App\Models\Kamus;
use App\Models\UnitKerja;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\OldRekomendasi;
use App\Models\OldTindakLanjut;
use App\Models\OldBuktiInputSIPTL;
use Illuminate\Support\Facades\DB;

class MasterRekomendasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('master-rekomendasi.index', [
            'title' => 'Daftar Rekomendasi Lama',
            'rekomendasi' => OldRekomendasi::all()->sortByDesc('created_at'),
            'semesterRekomendasi' => OldRekomendasi::distinct()->pluck('semester_rekomendasi')->toArray(),
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

        return view('master-rekomendasi.create', [
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
            'hasil_pemeriksaan' => 'required',
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
            $lhp->storeAs('public/uploads/lhp', $lhpFileName);
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
            $rekomendasi = OldRekomendasi::create($validatedData);

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
                    $currentTime = now()->format('dmY');
                    $fileName = $currentTime . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/tindak_lanjut'), $fileName);
                    $tindakLanjutData['bukti_tindak_lanjut'] = $fileName;
                }

                OldTindakLanjut::create($tindakLanjutData);
            }

            if ($request->hasFile('bukti_input_siptl')) {
                $file = $request->file('bukti_input_siptl');
                $currentTime = now()->format('dmY');
                $fileName = $currentTime . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/bukti_input_siptl'), $fileName);

                $buktiInput = OldBuktiInputSIPTL::where('rekomendasi_id', $rekomendasi->id)->first();

                if ($buktiInput) {
                    $buktiInput->update([
                        'bukti_input_siptl' => $fileName,
                        'detail_bukti_input_siptl' => $request->detail_bukti_input_siptl,
                        'upload_by' => auth()->user()->nama,
                        'upload_at' => now(),
                    ]);
                } else {
                    OldBuktiInputSIPTL::create([
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

            return redirect('/master-rekomendasi')->with('create', 'Data berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(OldRekomendasi $rekomendasi)
    {
        // tindak lanjut dan bukti input SIPTL
        $tindakLanjut = OldTindakLanjut::where('rekomendasi_id', $rekomendasi->id)->get();
        $buktiInputSIPTL = OldBuktiInputSIPTL::where('rekomendasi_id', $rekomendasi->id)->first();

        $rekomendasi->tindakLanjut = $tindakLanjut;
        $rekomendasi->buktiInputSIPTL = $buktiInputSIPTL;

        return view('master-rekomendasi.show', [
            'title' => 'Detail Rekomendasi Lama',
            'rekomendasi' => $rekomendasi,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OldRekomendasi $rekomendasi)
    {
        $kamus_temuan = Kamus::where('jenis', 'Temuan')->get();
        $kamus_pemeriksaan = Kamus::where('jenis', 'Pemeriksaan')->get();
        $oldRekomendasi = OldRekomendasi::with('tindakLanjut')->find($rekomendasi->id);
        $unit_kerja = UnitKerja::all();

        return view('master-rekomendasi.edit', [
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
    public function update(Request $request, OldRekomendasi $rekomendasi)
    {
        // Aturan validasi untuk entri Rekomendasi
        $validatedData = $request->validate([
            'pemeriksaan' => 'required',
            'jenis_pemeriksaan' => 'required',
            'tahun_pemeriksaan' => 'required|integer|min:1900|max:2099',
            'hasil_pemeriksaan' => 'required',
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
            $lhp->storeAs('public/uploads/lhp', $lhpFileName);
            $validatedData['lhp'] = $lhpFileName;
        } else {
            $validatedData['lhp'] = $rekomendasi->lhp;
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
            $rekomendasi->update($validatedData);

            // Simpan data TindakLanjut ke dalam tabel OldTindakLanjut
            foreach ($validatedTindakLanjutData['tindak_lanjut'] as $key => $tindak_lanjut) {

                // Mendapatkan nilai tenggat waktu dari input pengguna
                $tenggat_waktu = $request->tenggat_waktu[$key];

                // Hitung semester tindak lanjut berdasarkan tenggat waktu
                $bulan = date('n', strtotime($tenggat_waktu));
                $tahun = date('Y', strtotime($tenggat_waktu));
                $semester = $bulan <= 6 ? 'Semester 1' : 'Semester 2';
                $semester_tindak_lanjut = $semester . ' ' . $tahun;

                if (isset($request->id[$key]) && $request->id[$key] != null) {
                    // update tindak lanjut
                    $tindakLanjut = OldTindakLanjut::find($request->id[$key]);
                    $tindakLanjut->update([
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
                    ]);

                    // cek apakah file bukti tindak lanjut diunggah
                    if ($request->hasFile('bukti_tindak_lanjut')) {
                        $file = $request->file('bukti_tindak_lanjut')[$key];
                        $currentTime = now()->format('dmY');
                        $fileName = $currentTime . '_' . $file->getClientOriginalName();
                        $file->move(public_path('uploads/tindak_lanjut'), $fileName);

                        // hapus file bukti tindak lanjut lama
                        $oldFile = $tindakLanjut->bukti_tindak_lanjut;
                        $oldFilePath = public_path('uploads/tindak_lanjut/' . $oldFile);
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }

                        $tindakLanjut->update([
                            'bukti_tindak_lanjut' => $fileName,
                        ]);
                    }
                } elseif (isset($request->id[$key])) {
                    $tindakLanjut = OldTindakLanjut::find($request->id[$key]);

                    // Cek jika semua isian lainnya kosong
                    $isEmpty = empty($validatedTindakLanjutData['tindak_lanjut'][$key]) &&
                            empty($validatedTindakLanjutData['unit_kerja'][$key]) &&
                            empty($validatedTindakLanjutData['tim_pemantauan'][$key]) &&
                            empty($validatedTindakLanjutData['tenggat_waktu'][$key]) &&
                            empty($validatedTindakLanjutData['bukti_tindak_lanjut'][$key]) &&
                            empty($validatedTindakLanjutData['status_tindak_lanjut'][$key]) &&
                            empty($validatedTindakLanjutData['detail_bukti_tindak_lanjut'][$key]) &&
                            empty($request->bukti_input_siptl) &&
                            empty($request->detail_bukti_input_siptl) &&
                            empty($request->catatan_pemutakhiran);

                    if ($tindakLanjut && $isEmpty) {
                        $tindakLanjut->delete();
                    }

                } else {
                    // simpan tindak lanjut baru
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
                        $currentTime = now()->format('dmY');
                        $fileName = $currentTime . '_' . $file->getClientOriginalName();
                        $file->move(public_path('uploads/tindak_lanjut'), $fileName);
                        $tindakLanjutData['bukti_tindak_lanjut'] = $fileName;
                    }

                    OldTindakLanjut::create($tindakLanjutData);
                }

            }

            if ($request->hasFile('bukti_input_siptl')) {
                $file = $request->file('bukti_input_siptl');
                $currentTime = now()->format('dmY');
                $fileName = $currentTime . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/bukti_input_siptl'), $fileName);

                $buktiInput = OldBuktiInputSIPTL::where('rekomendasi_id', $rekomendasi->id)->first();

                if ($buktiInput) {
                    // hapus file bukti input SIPTL lama
                    $oldFile = $buktiInput->bukti_input_siptl;
                    $oldFilePath = public_path('uploads/bukti_input_siptl/' . $oldFile);
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }

                    $buktiInput->update([
                        'bukti_input_siptl' => $fileName,
                        'detail_bukti_input_siptl' => $request->detail_bukti_input_siptl,
                        'upload_by' => auth()->user()->nama,
                        'upload_at' => now(),
                    ]);
                } else {
                    OldBuktiInputSIPTL::create([
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

            return redirect('/master-rekomendasi/'.$rekomendasi->id)->with('update', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OldRekomendasi $rekomendasi)
    {
        OldBuktiInputSIPTL::where('rekomendasi_id', $rekomendasi->id)->delete();
        OldTindakLanjut::where('rekomendasi_id', $rekomendasi->id)->delete();
        OldRekomendasi::destroy($rekomendasi->id);

        return redirect('/master-rekomendasi')->with('delete', 'Data berhasil dihapus!');
    }
}
