<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekomendasi extends Model
{
    use HasFactory;

    protected $table = 'rekomendasi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pemeriksaan',
        'jenis_pemeriksaan',
        'tahun_pemeriksaan',
        'hasil_pemeriksaan',
        'jenis_temuan',
        'uraian_temuan',
        'rekomendasi',
        'catatan_rekomendasi',
        'status_rekomendasi',
        'catatan_pemutakhiran',
        'pemutakhiran_by',
        'pemutakhiran_at',
    ];


    // Rekomendasi memiliki banyak tindak lanjut
    public function tindakLanjut()
    {
        return $this->hasMany(TindakLanjut::class);
    }

    // Rekomendasi memiliki satu bukti input SIPTL
    public function buktiInputSIPTL()
    {
        return $this->hasOne(BuktiInputSIPTL::class);
    }
}
