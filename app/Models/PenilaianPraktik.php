<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianPraktik extends Model
{
    protected $table = 'penilaian_praktiks';

    protected $fillable = [
        'user_id',
        'admin_id',
        'tanggal',
        'jenis_praktik',
        'catatan',
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

    public function komponenChecklist()
    {
        return $this->hasMany(PenilaianPraktikKomponen::class, 'penilaian_praktik_id');
    }
}
