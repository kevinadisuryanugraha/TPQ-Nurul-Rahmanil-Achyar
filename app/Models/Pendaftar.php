<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'nama_orang_tua',
        'no_wa',
        'alamat',
        'pernah_mengaji',
        'level_mengaji_sebelumnya',
        'catatan_tambahan',
        'status',
        'catatan_internal',
        'user_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'pernah_mengaji' => 'boolean',
    ];

    /**
     * Get the student user account that was created from this registration.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
