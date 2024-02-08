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
    ];


    // Rekomendasi memiliki banyak tindak lanjut
    public function tindakLanjut()
    {
        return $this->hasMany(TindakLanjut::class);
    }
}
