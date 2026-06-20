<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianBaca extends Model
{
    protected $table = 'penilaian_bacas';

    protected $fillable = [
        'user_id',
        'admin_id',
        'tanggal',
        'jenis_bacaan',
        'jilid_juz',
        'halaman_ayat',
        'keterangan_posisi',
        'kelancaran',
        'catatan_tajwid',
        'catatan_umum',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
