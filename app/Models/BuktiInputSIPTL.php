<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiInputSIPTL extends Model
{
    use HasFactory;

    protected $table = 'bukti_input_siptl';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bukti_input_siptl',
        'detail_bukti_input_siptl',
        'upload_by',
        'upload_at',
        'rekomendasi_id',
    ];

    // Bukti Input SIPTL dimiliki oleh satu tindak lanjut
    public function tindakLanjut()
    {
        return $this->belongsTo(Rekomendasi::class);
    }
}
