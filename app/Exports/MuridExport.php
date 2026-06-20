<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MuridExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $levelId;

    public function __construct($levelId = null)
    {
        $this->levelId = $levelId;
    }

    public function collection()
    {
        $query = User::with('currentLevel')->latest();
        if ($this->levelId) {
            $query->where('current_level_id', $this->levelId);
        }
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID Santri',
            'Nama Lengkap',
            'Nama Panggilan',
            'Username',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Level Saat Ini',
            'Nama Orang Tua',
            'No HP Orang Tua',
            'Alamat',
            'Tanggal Masuk',
            'Status Aktif',
        ];
    }

    public function map($student): array
    {
        return [
            $student->id,
            $student->nama_lengkap,
            $student->nama_panggilan,
            $student->username,
            $student->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
            $student->tempat_lahir ?? '-',
            $student->tanggal_lahir ? $student->tanggal_lahir->format('d-m-Y') : '-',
            $student->currentLevel->nama ?? '-',
            $student->nama_orang_tua ?? '-',
            $student->no_hp_orang_tua ?? '-',
            $student->alamat ?? '-',
            $student->tanggal_masuk ? $student->tanggal_masuk->format('d-m-Y') : '-',
            $student->is_active ? 'Aktif' : 'Nonaktif',
        ];
    }
}
