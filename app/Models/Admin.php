<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $table = 'admins';
    protected $guard_name = 'admin';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_hp',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    public function userLevelHistories()
    {
        return $this->hasMany(UserLevelHistory::class, 'admin_id');
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'admin_id');
    }

    public function penilaianBacas()
    {
        return $this->hasMany(PenilaianBaca::class, 'admin_id');
    }

    public function penilaianHafalans()
    {
        return $this->hasMany(PenilaianHafalan::class, 'admin_id');
    }

    public function penilaianTulises()
    {
        return $this->hasMany(PenilaianTulis::class, 'admin_id');
    }

    public function penilaianPraktiks()
    {
        return $this->hasMany(PenilaianPraktik::class, 'admin_id');
    }

    public function ceritaKisahs()
    {
        return $this->hasMany(CeritaKisah::class, 'admin_id');
    }

    public function panduanPraktiks()
    {
        return $this->hasMany(PanduanPraktik::class, 'admin_id');
    }

    public function pengumumans()
    {
        return $this->hasMany(Pengumuman::class, 'admin_id');
    }

    // Helper functions
    public function isSuperadmin(): bool
    {
        return $this->role === 'superadmin';
    }
}
