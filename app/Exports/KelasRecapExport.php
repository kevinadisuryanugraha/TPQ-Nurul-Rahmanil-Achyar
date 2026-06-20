<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Absensi;
use App\Models\PenilaianBaca;
use App\Models\PenilaianHafalan;
use App\Models\PenilaianTulis;
use App\Models\PenilaianPraktik;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KelasRecapExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $levelId;

    public function __construct($levelId)
    {
        $this->levelId = $levelId;
    }

    public function collection()
    {
        return User::with('currentLevel')
            ->where('current_level_id', $this->levelId)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nama Santri',
            'Level',
            'Nilai Baca Terakhir',
            'Keterangan Baca',
            'Nilai Hafalan Terakhir',
            'Keterangan Hafalan',
            'Nilai Tulis Terakhir',
            'Nilai Praktik Terakhir',
            'Hadir',
            'Izin',
            'Sakit',
            'Alpha',
        ];
    }

    public function map($student): array
    {
        $baca = PenilaianBaca::where('user_id', $student->id)->latest()->first();
        $hafalan = PenilaianHafalan::where('user_id', $student->id)->latest()->first();
        $tulis = PenilaianTulis::where('user_id', $student->id)->latest()->first();
        $praktik = PenilaianPraktik::where('user_id', $student->id)->latest()->first();

        // Attendance stats
        $attendances = Absensi::where('user_id', $student->id)->get();

        return [
            $student->nama_lengkap,
            $student->currentLevel->nama ?? '-',
            $baca->nilai ?? '-',
            $baca ? ($baca->surah_bacaan ?? $baca->jilid_halaman) : '-',
            $hafalan->nilai ?? '-',
            $hafalan ? ($hafalan->surah_hafalan ?? $hafalan->hadist_hafalan ?? $hafalan->doa_hafalan) : '-',
            $tulis->nilai ?? '-',
            $praktik->nilai ?? '-',
            $attendances->where('status', 'hadir')->count(),
            $attendances->where('status', 'izin')->count(),
            $attendances->where('status', 'sakit')->count(),
            $attendances->where('status', 'alpha')->count(),
        ];
    }
}
