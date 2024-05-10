<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiInputSIPTL extends Model
{
    use HasFactory;

    protected $table = 'bukti_input_siptl';

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
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
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
