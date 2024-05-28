<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OldRekomendasi extends Model
{
    use HasFactory;

    protected $table = 'rekomendasi_old';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'pemeriksaan',
        'jenis_pemeriksaan',
        'tahun_pemeriksaan',
        'hasil_pemeriksaan',
        'jenis_temuan',
        'uraian_temuan',
        'rekomendasi',
        'catatan_rekomendasi',
        'lhp',
        'semester_rekomendasi',
        'status_rekomendasi',
        'catatan_pemutakhiran',
        'pemutakhiran_by',
        'pemutakhiran_at',
        'semester_pemutakhiran',
    ];


    // Rekomendasi memiliki banyak tindak lanjut
    public function tindakLanjut()
    {
        return $this->hasMany(OldTindakLanjut::class, 'rekomendasi_id');
    }

    // Rekomendasi memiliki satu bukti input SIPTL
    public function buktiInputSIPTL()
    {
        return $this->hasOne(OldBuktiInputSIPTL::class, 'rekomendasi_id');
    }
}