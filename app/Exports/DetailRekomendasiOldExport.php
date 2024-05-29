<?php

namespace App\Exports;

use App\Models\Rekomendasi;
use App\Models\TindakLanjut;
use App\Models\OldRekomendasi;
use App\Models\OldTindakLanjut;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class DetailRekomendasiOldExport implements FromCollection, WithHeadings
{

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Mengambil data rekomendasi berdasarkan ID
        $rekomendasi = OldRekomendasi::findOrFail($this->id);

        // Mengambil data tindak lanjut yang terkait dengan rekomendasi
        $tindakLanjut = OldTindakLanjut::where('rekomendasi_id', $this->id)->get();

        // Membuat array untuk menampung data rekomendasi dan tindak lanjut
        $data = new Collection();

        // Menambahkan data tindak lanjut ke dalam array
        foreach ($tindakLanjut as $tindak) {
            $data->push([
                'Nomor Rekomendasi' => $rekomendasi->id,
                'Link Rekomendasi' => URL::to('/rekomendasi/' . $rekomendasi->id),
                // data pemeriksaan
                'Pemeriksaan' => $rekomendasi->pemeriksaan,
                'Jenis Pemeriksaan' => $rekomendasi->jenis_pemeriksaan,
                'Tahun Pemeriksaan' => $rekomendasi->tahun_pemeriksaan,
                'Hasil Pemeriksaan' => strip_tags($rekomendasi->hasil_pemeriksaan),
                // data rekomendasi
                'Jenis Temuan' => $rekomendasi->jenis_temuan,
                'Uraian Temuan' => strip_tags($rekomendasi->uraian_temuan),
                'Rekomendasi' => strip_tags($rekomendasi->rekomendasi),
                'Catatan Rekomendasi' => strip_tags($rekomendasi->catatan_rekomendasi),
                'Dokumen LHP' => $rekomendasi->lhp ? URL::to('/storage/uploads/' . $rekomendasi->lhp) : "File LHP Tidak Ditemukan",
                // data tindak lanjut
                'Semester Tindak Lanjut' => $tindak->semester_tindak_lanjut,
                'Tindak Lanjut' => strip_tags($tindak->tindak_lanjut),
                'Unit Kerja' => $tindak->unit_kerja,
                'Tim Pemantauan' => $tindak->tim_pemantauan,
                'Tenggat Waktu' => $tindak->tenggat_waktu,
                'Bukti Tindak Lanjut' => $tindak->bukti_tindak_lanjut ? URL::to('/uploads/tindak_lanjut/' . $tindak->bukti_tindak_lanjut) : "File Bukti Tindak Lanjut Tidak Ditemukan",
                'Detail Bukti Tindak Lanjut' => $tindak->detail_bukti_tindak_lanjut,
                'Status Tindak Lanjut' => $tindak->status_tindak_lanjut,
                'Catatan Tindak Lanjut' => $tindak->catatan_tindak_lanjut,
                // data dokumen bukti inpput SIPTL
                'Bukti Input SIPTL' => $rekomendasi->buktiInputSIPTL->bukti_input_siptl ? URL::to('/uploads/bukti_input_siptl/' . $rekomendasi->buktiInputSIPTL->bukti_input_siptl) : "File Bukti Input SIPTL Tidak Ditemukan",
                // data pemutakhiran status
                'Status Rekomendasi' => $rekomendasi->status_rekomendasi,
                'Catatan Pemutakhiran' => $rekomendasi->catatan_pemutakhiran,
                'Semester Pemutakhiran' => $rekomendasi->semester_pemutakhiran,
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Nomor Rekomendasi',
            'Link Rekomendasi',
            'Pemeriksaan',
            'Jenis Pemeriksaan',
            'Tahun Pemeriksaan',
            'Hasil Pemeriksaan',
            'Jenis Temuan',
            'Uraian Temuan',
            'Rekomendasi',
            'Catatan Rekomendasi',
            'Dokumen LHP',
            'Semester Tindak Lanjut',
            'Tindak Lanjut',
            'Unit Kerja',
            'Tim Pemantauan',
            'Tenggat Waktu',
            'Bukti Tindak Lanjut',
            'Detail Bukti Tindak Lanjut',
            'Status Tindak Lanjut',
            'Catatan Tindak Lanjut',
            'Bukti Input SIPTL',
            'Status Rekomendasi',
            'Catatan Pemutakhiran',
            'Semester Pemutakhiran',
        ];
    }
}
