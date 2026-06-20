<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $guard_name = 'web';

    protected $fillable = [
        'nama_lengkap',
        'nama_panggilan',
        'username',
        'password',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'nama_orang_tua',
        'no_hp_orang_tua',
        'alamat',
        'foto',
        'tanggal_masuk',
        'current_level_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    public function currentLevel()
    {
        return $this->belongsTo(Level::class, 'current_level_id');
    }

    public function levelHistories()
    {
        return $this->hasMany(UserLevelHistory::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function penilaianBacas()
    {
        return $this->hasMany(PenilaianBaca::class);
    }

    public function penilaianHafalans()
    {
        return $this->hasMany(PenilaianHafalan::class);
    }

    public function penilaianTulises()
    {
        return $this->hasMany(PenilaianTulis::class);
    }

    public function penilaianPraktiks()
    {
        return $this->hasMany(PenilaianPraktik::class);
    }
}
