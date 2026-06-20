<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianPraktikKomponen extends Model
{
    protected $table = 'penilaian_praktik_komponens';
    
    public $timestamps = false;

    protected $fillable = [
        'penilaian_praktik_id',
        'nama_komponen',
        'is_terpenuhi',
    ];

    protected $casts = [
        'is_terpenuhi' => 'boolean',
    ];

    public function penilaianPraktik()
    {
        return $this->belongsTo(PenilaianPraktik::class, 'penilaian_praktik_id');
    }
}
