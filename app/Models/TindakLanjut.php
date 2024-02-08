<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TindakLanjut extends Model
{
    use HasFactory;

    protected $table = 'tindak_lanjut';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tindak_lanjut',
        'unit_kerja',
        'tim_pemantauan',
        'tenggat_waktu',
        'dokumen_tindak_lanjut',
        'uploader',
        'tanggal_upload',
        'status_tindak_lanjut',
        'rekomendasi_id',
    ];

    // Tindak lanjut dimiliki oleh satu rekomendasi
    public function rekomendasi()
    {
        return $this->belongsTo(Rekomendasi::class);
    }
}
