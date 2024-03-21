<?php

namespace App\Exports;

use App\Models\Rekomendasi;
use App\Models\TindakLanjut;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class DetailRekomendasiExport implements FromCollection, WithHeadings
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
        $rekomendasi = Rekomendasi::findOrFail($this->id);

        // Mengambil data tindak lanjut yang terkait dengan rekomendasi
        $tindakLanjut = TindakLanjut::where('rekomendasi_id', $this->id)->get();

        // Membuat array untuk menampung data rekomendasi dan tindak lanjut
        $data = new Collection();

        // Menambahkan data tindak lanjut ke dalam array
        foreach ($tindakLanjut as $tindak) {
            $data->push([
                'Nomor Rekomendasi' => $rekomendasi->id,
                'Pemeriksaan' => $rekomendasi->pemeriksaan,
                'Jenis Pemeriksaan' => $rekomendasi->jenis_pemeriksaan,
                'Tahun Pemeriksaan' => $rekomendasi->tahun_pemeriksaan,
                'Hasil Pemeriksaan' => $rekomendasi->hasil_pemeriksaan,
                'Jenis Temuan' => $rekomendasi->jenis_temuan,
                'Uraian Temuan' => $rekomendasi->uraian_temuan,
                'Rekomendasi' => $rekomendasi->rekomendasi,
                'Catatan Rekomendasi' => $rekomendasi->catatan_rekomendasi,
                'Status Rekomendasi' => $rekomendasi->status_rekomendasi,
                'Tindak Lanjut' => $tindak->tindak_lanjut,
                'Unit Kerja' => $tindak->unit_kerja,
                'Tim Pemantauan' => $tindak->tim_pemantauan,
                'Tenggat Waktu' => $tindak->tenggat_waktu,
                'Dokumen Tindak Lanjut' => $tindak->dokumen_tindak_lanjut,
                'Status Tindak Lanjut' => $tindak->status_tindak_lanjut,
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Nomor Rekomendasi',
            'Pemeriksaan',
            'Jenis Pemeriksaan',
            'Tahun Pemeriksaan',
            'Hasil Pemeriksaan',
            'Jenis Temuan',
            'Uraian Temuan',
            'Rekomendasi',
            'Catatan Rekomendasi',
            'Status Rekomendasi',
            'Tindak Lanjut',
            'Unit Kerja',
            'Tim Pemantauan',
            'Tenggat Waktu',
            'Dokumen Tindak Lanjut',
            'Status Tindak Lanjut',
        ];
    }
}
