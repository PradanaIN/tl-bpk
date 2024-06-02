<?php

namespace App\Http\Controllers;

use App\Models\Kamus;
use App\Models\UnitKerja;
use App\Models\Rekomendasi;
use Illuminate\Support\Str;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;
use App\Models\BuktiInputSIPTL;
use Illuminate\Database\QueryException;

class PemutakhiranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rekomendasi = Rekomendasi::whereHas('tindakLanjut', function ($query) {
            $query->where('status_tindak_lanjut', 'Sesuai');
        })->where(function ($query) {
            $query->whereDoesntHave('tindakLanjut', function ($subquery) {
                $subquery->where('status_tindak_lanjut', '!=', 'Sesuai');
            })->orWhereHas('tindakLanjut', function ($subquery) {
                $subquery->where('tenggat_waktu', '<', now());
            });
        })
        ->orderByRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(semester_rekomendasi, ' ', -1), ' ', 1) + 0 DESC")
        ->orderByRaw("CASE WHEN pemutakhiran_at IS NULL THEN 0 ELSE 1 END")
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


        return view('pemutakhiran.index', [
            'title' => 'Pemutakhiran Status Rekomendasi',
            'rekomendasi' => $rekomendasi,
            'semesterRekomendasi' => $semesterRekomendasi,
            'kamus_pemeriksaan' => Kamus::where('jenis', 'Pemeriksaan')->get(),
            'TindakLanjut' => TindakLanjut::all(),
            'buktiInputSIPTL' => BuktiInputSIPTL::all(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rekomendasi $rekomendasi)
    {
        auth()->user()->unreadNotifications->where('data.rekomendasi_id', $rekomendasi->id)->markAsRead();

        // rekomendasi with tindak lanjut and bukti input SIPTL
        $rekomendasi = Rekomendasi::
            with('tindakLanjut')
            ->with('buktiInputSIPTL')
            ->where('id', $rekomendasi->id)
            ->first();

        return view('pemutakhiran.show', [
            'title' => 'Detail Rekomendasi',
            'rekomendasi' => $rekomendasi,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rekomendasi $rekomendasi)
    {
        // Menentukan semester pemutakhiran
        $tahun = date('Y');
        $bulan = date('n');
        // Tentukan semester berdasarkan bulan
        $semester = $bulan <= 6 ? 'Semester 1' : 'Semester 2';
        // Gabungkan semester dengan tahun
        $semester_tahun = $semester . ' ' . $tahun;

        $rekomendasi->update([
            'status_rekomendasi' => $request->status_rekomendasi,
            // apabila status rekoemndasi "Sesuai" maka catatan pemutakhiran kosong
            'catatan_pemutakhiran' => $request->status_rekomendasi === 'Sesuai' ? null : $request->catatan_pemutakhiran,
            'semester_pemutakhiran' => $semester_tahun,
            'pemutakhiran_by' => auth()->user()->nama,
            'pemutakhiran_at' => now(),
        ]);

        return redirect('/pemutakhiran-status/'.$rekomendasi->id)->with('update', 'Rekomendasi Berhasil Dimutakhirkan!');
    }

    /**
     * Upload file bukti input SIPTL
     */

    public function uploadBuktiInputSIPTL(Request $request, Rekomendasi $rekomendasi)
    {
        try {
            $request->validate([
                'bukti_input_siptl' => 'required|file|mimes:jpg,png,pdf|max:100000',
                'detail_bukti_input_siptl' => 'required',
            ]);

            if ($request->hasFile('bukti_input_siptl')) {
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

                    $message = 'Bukti Input SIPTL berhasil diperbarui!';
                } else {
                    BuktiInputSIPTL::create([
                        'id' => Str::uuid()->toString(),
                        'bukti_input_siptl' => $fileName,
                        'detail_bukti_input_siptl' => $request->detail_bukti_input_siptl,
                        'upload_by' => auth()->user()->nama,
                        'upload_at' => now(),
                        'rekomendasi_id' => $rekomendasi->id,
                    ]);

                    $message = 'Bukti Input SIPTL berhasil diunggah!';
                }

                return redirect()->back()->with('update', $message);
            }
        } catch (QueryException $e) {
            $errorMessage = $e->getMessage();
            return redirect()->back()->withInput()->with('error', $errorMessage);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }
    }
}
