<?php

namespace App\Http\Controllers;

use App\Models\Kamus;
use App\Models\UnitKerja;
use App\Models\Rekomendasi;
use Illuminate\Support\Str;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;
use App\Models\BuktiTindakLanjut;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DetailRekomendasiExport;
use App\Models\BuktiInputSIPTL;

class RekomendasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('rekomendasi.index', [
            'title' => 'Daftar Rekomendasi',
            'rekomendasi' => Rekomendasi::all(),
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
            'hasil_pemeriksaan' => 'required',
            'jenis_temuan' => 'required',
            'uraian_temuan' => 'required',
            'rekomendasi' => 'required',
            'catatan_rekomendasi' => 'required',
            'status_rekomendasi' => 'required',
        ]);

        // memberikan id pada rekomendasi berupa uuid
        $validatedData['id'] = Str::uuid()->toString();

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
                // Buat entri baru dalam tabel TindakLanjut
                TindakLanjut::create([
                    'id' => Str::uuid()->toString(),
                    'rekomendasi_id' => $rekomendasi->id,
                    'tindak_lanjut' => $tindak_lanjut,
                    'unit_kerja' => $request->unit_kerja[$key],
                    'tim_pemantauan' => $request->tim_pemantauan[$key],
                    'tenggat_waktu' => $request->tenggat_waktu[$key],
                    'status_tindak_lanjut' => 'Belum Sesuai',
                    'bukti_tindak_lanjut' => 'Belum Diunggah!',
                ]);
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
            $validatedData = $request->validate([
                'pemeriksaan' => 'required',
                'jenis_pemeriksaan' => 'required',
                'tahun_pemeriksaan' => 'required|integer|min:1900|max:2099',
                'hasil_pemeriksaan' => 'required',
                'jenis_temuan' => 'required',
                'uraian_temuan' => 'required',
                'rekomendasi' => 'required',
                'catatan_rekomendasi' => 'required',
                'status_rekomendasi' => 'required'
            ]);

            $rekomendasi->update($validatedData);

            foreach ($request->tindak_lanjut as $key => $tindak_lanjut) {
                $validatedData = $request->validate([
                    'tindak_lanjut.' . $key => 'required',
                    'unit_kerja.' . $key => 'required',
                    'tim_pemantauan.' . $key => 'required',
                    'tenggat_waktu.' . $key => 'required',
                ]);

                if (isset($request->id[$key]) && $request->id[$key] != null) {
                    TindakLanjut::where('id', $request->id[$key])->update([
                        'tindak_lanjut' => $tindak_lanjut,
                        'unit_kerja' => $request->unit_kerja[$key],
                        'tim_pemantauan' => $request->tim_pemantauan[$key],
                        'tenggat_waktu' => $request->tenggat_waktu[$key],
                        'bukti_tindak_lanjut' => $request->bukti_tindak_lanjut[$key],
                        'status_tindak_lanjut' => $request->status_tindak_lanjut[$key],
                    ]);
                } else {
                    TindakLanjut::create([
                        'rekomendasi_id' => $rekomendasi->id,
                        'tindak_lanjut' => $tindak_lanjut,
                        'unit_kerja' => $request->unit_kerja[$key],
                        'tim_pemantauan' => $request->tim_pemantauan[$key],
                        'tenggat_waktu' => $request->tenggat_waktu[$key],
                        'bukti_tindak_lanjut' => 'Belum Diunggah!',
                        'status_tindak_lanjut' => 'Belum Sesuai',
                    ]);
                }
            }

            return redirect('/rekomendasi/'.$rekomendasi->id)->with('update', 'Data berhasil diubah!');
        } catch (\Exception $e) {
            // Tangani error
            $errorMessage = $e->getMessage(); // Dapatkan pesan error

            // Tampilkan SweetAlert dengan pesan error
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rekomendasi $rekomendasi)
    {
        TindakLanjut::where('rekomendasi_id', $rekomendasi->id)->delete();
        Rekomendasi::destroy($rekomendasi->id);

        return redirect('/rekomendasi')->with('delete', 'Data berhasil dihapus!');
    }

    /**
     * Export the specified resource to Excel.
     */

    public function export(Rekomendasi $rekomendasi)
    {
        return Excel::download(new DetailRekomendasiExport($rekomendasi->id), 'rekomendasi.xlsx');
    }

}
