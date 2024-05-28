<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TindakLanjut extends Model
{
    use HasFactory;

    protected $table = 'tindak_lanjut';

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
        'tindak_lanjut',
        'unit_kerja',
        'tim_pemantauan',
        'tenggat_waktu',
        'bukti_tindak_lanjut',
        'detail_bukti_tindak_lanjut',
        'upload_by',
        'upload_at',
        'status_tindak_lanjut',
        'status_tindak_lanjut_at',
        'status_tindak_lanjut_by',
        'catatan_tindak_lanjut',
        'semester_tindak_lanjut',
        'rekomendasi_id',
    ];

    // Tindak lanjut dimiliki oleh satu rekomendasi
    public function rekomendasi()
    {
        return $this->belongsTo(Rekomendasi::class);
    }
}
