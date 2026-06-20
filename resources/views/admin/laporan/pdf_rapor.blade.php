<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rapor Santri - {{ $student->nama_lengkap }}</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            color: #333333;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #1b5e20;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0 0 5px 0;
            color: #1b5e20;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 12px;
            margin: 0 0 5px 0;
            color: #555555;
            font-weight: normal;
        }
        .header p {
            margin: 0;
            font-size: 10px;
            color: #777777;
        }
        .student-info {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .student-info td {
            padding: 4px 0;
            vertical-align: top;
        }
        .student-info td.label {
            width: 120px;
            font-weight: bold;
            color: #555555;
        }
        .student-info td.separator {
            width: 15px;
            text-align: center;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #ffffff;
            background-color: #1b5e20;
            padding: 6px 10px;
            margin-top: 20px;
            margin-bottom: 10px;
            border-radius: 3px;
        }
        .score-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .score-table th {
            background-color: #f2f2f2;
            border: 1px solid #dddddd;
            padding: 8px;
            font-weight: bold;
            text-align: left;
            font-size: 11px;
            color: #1b5e20;
        }
        .score-table td {
            border: 1px solid #dddddd;
            padding: 8px;
            vertical-align: top;
            font-size: 11px;
        }
        .score-value {
            font-weight: bold;
            font-size: 13px;
            color: #1b5e20;
            text-align: center;
        }
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .attendance-table th, .attendance-table td {
            border: 1px solid #dddddd;
            padding: 6px 10px;
            text-align: center;
            font-size: 11px;
        }
        .attendance-table th {
            background-color: #f2f2f2;
            color: #555555;
        }
        .footer-sig {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
        }
        .footer-sig td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .footer-sig p {
            margin: 0;
        }
        .footer-sig .space {
            height: 60px;
        }
    </style>
</head>
<body>

    <!-- Header / Kop Surat -->
    <div class="header">
        <h1>{{ $appSettings['nama_tpq'] ?? 'Taman Pendidikan Al-Qur\'an' }}</h1>
        <h2>Laporan Hasil Belajar Santri (Rapor)</h2>
        <p>Alamat: {{ $appSettings['alamat_tpq'] ?? '-' }} | No. Telp: {{ $appSettings['telepon_tpq'] ?? '-' }}</p>
    </div>

    <!-- Student Bio -->
    <table class="student-info">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td class="separator">:</td>
            <td style="font-weight: bold; font-size: 13px;">{{ $student->nama_lengkap }}</td>
            <td class="label" style="width: 100px;">Tahun Ajaran</td>
            <td class="separator">:</td>
            <td>{{ $appSettings['tahun_ajaran'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Nama Panggilan</td>
            <td class="separator">:</td>
            <td>{{ $student->nama_panggilan }}</td>
            <td class="label">Kelas / Level</td>
            <td class="separator">:</td>
            <td style="font-weight: bold; color: #1b5e20;">{{ $student->currentLevel->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Username Login</td>
            <td class="separator">:</td>
            <td>{{ $student->username }}</td>
            <td class="label">Tanggal Cetak</td>
            <td class="separator">:</td>
            <td>{{ now()->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <!-- Domain 1: Bacaan Al-Qur'an / Iqra -->
    <div class="section-title">I. Perkembangan Bacaan (Al-Qur'an / Iqra)</div>
    <table class="score-table">
        <thead>
            <tr>
                <th style="width: 15%;">Nilai</th>
                <th style="width: 45%;">Materi / Jilid Halaman</th>
                <th style="width: 40%;">Catatan & Rekomendasi Ustadz</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="score-value">{{ $latestBaca->nilai ?? '-' }}</td>
                <td>
                    @if($latestBaca)
                        <strong>{{ $latestBaca->surah_bacaan ? 'Al-Qur\'an: ' . $latestBaca->surah_bacaan : 'Buku Iqra' }}</strong>
                        @if($latestBaca->ayat_bacaan)
                            <br>Ayat: {{ $latestBaca->ayat_bacaan }}
                        @endif
                        @if($latestBaca->jilid_halaman)
                            <br>Halaman: {{ $latestBaca->jilid_halaman }}
                        @endif
                    @else
                        <span style="color: #999;">Belum ada penilaian bacaan</span>
                    @endif
                </td>
                <td>{{ $latestBaca->catatan ?? '-' }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Domain 2: Hafalan (Surah, Doa, Hadist) -->
    <div class="section-title">II. Perkembangan Hafalan (Surah, Doa & Hadist)</div>
    <table class="score-table">
        <thead>
            <tr>
                <th style="width: 15%;">Nilai</th>
                <th style="width: 45%;">Materi Hafalan</th>
                <th style="width: 40%;">Catatan Ustadz</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="score-value">{{ $latestHafalan->nilai ?? '-' }}</td>
                <td>
                    @if($latestHafalan)
                        <strong>{{ ucfirst($latestHafalan->tipe_materi) }}</strong>: 
                        {{ $latestHafalan->surah_hafalan ?? $latestHafalan->doa_hafalan ?? $latestHafalan->hadist_hafalan ?? '-' }}
                    @else
                        <span style="color: #999;">Belum ada penilaian hafalan</span>
                    @endif
                </td>
                <td>{{ $latestHafalan->catatan ?? '-' }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Domain 3: Menulis -->
    <div class="section-title">III. Perkembangan Menulis (Khat / Arab)</div>
    <table class="score-table">
        <thead>
            <tr>
                <th style="width: 15%;">Nilai</th>
                <th style="width: 15%;">Predikat</th>
                <th style="width: 70%;">Catatan Ustadz</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="score-value">{{ $latestTulis->nilai ?? '-' }}</td>
                <td style="text-align: center; font-weight: bold; font-size: 13px;">
                    @if($latestTulis)
                        @if($latestTulis->nilai >= 85) A
                        @elseif($latestTulis->nilai >= 75) B
                        @elseif($latestTulis->nilai >= 60) C
                        @else D
                        @endif
                    @else
                        -
                    @endif
                </td>
                <td>{{ $latestTulis->catatan ?? '-' }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Domain 4: Praktik Ibadah -->
    <div class="section-title">IV. Praktik Ibadah & Fiqh</div>
    <table class="score-table">
        <thead>
            <tr>
                <th style="width: 15%;">Nilai</th>
                <th style="width: 45%;">Nama Praktik / Gerakan</th>
                <th style="width: 40%;">Catatan Ustadz</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="score-value">{{ $latestPraktik->nilai ?? '-' }}</td>
                <td>
                    @if($latestPraktik)
                        <strong>{{ $latestPraktik->judul_praktik }}</strong>
                        <div style="font-size: 9px; color: #666; margin-top: 5px;">
                            Detail Checklist:<br>
                            @foreach($latestPraktik->komponenChecklist as $comp)
                                <span style="display: inline-block; margin-right: 8px;">
                                    {{ $comp->nama_komponen }}: <strong>{{ $comp->status_lulus ? 'Lulus' : 'Belum' }}</strong>
                                </span>
                            @endforeach
                        </div>
                    @else
                        <span style="color: #999;">Belum ada penilaian praktik ibadah</span>
                    @endif
                </td>
                <td>{{ $latestPraktik->catatan ?? '-' }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Attendance recap -->
    <div class="section-title">V. Rekapitulasi Kehadiran</div>
    <table class="attendance-table">
        <thead>
            <tr>
                <th>Hadir (H)</th>
                <th>Izin (I)</th>
                <th>Sakit (S)</th>
                <th>Tanpa Keterangan (A)</th>
                <th>Total Pertemuan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $attendanceStats['hadir'] }} hari</td>
                <td>{{ $attendanceStats['izin'] }} hari</td>
                <td>{{ $attendanceStats['sakit'] }} hari</td>
                <td style="color: red; font-weight: bold;">{{ $attendanceStats['alpha'] }} hari</td>
                <td style="font-weight: bold;">{{ $attendanceStats['total'] }} hari</td>
            </tr>
        </tbody>
    </table>

    <!-- Signature block -->
    <table class="footer-sig">
        <tr>
            <td>
                <p>Mengetahui,</p>
                <p style="font-weight: bold; margin-top: 5px;">Kepala TPQ</p>
                <div class="space"></div>
                <p style="text-decoration: underline; font-weight: bold;">{{ $appSettings['nama_kepala_tpq'] ?? '........................................' }}</p>
            </td>
            <td>
                <p>Tanggal, {{ now()->translatedFormat('d F Y') }}</p>
                <p style="font-weight: bold; margin-top: 5px;">Ustadz/Ustadzah Wali Kelas</p>
                <div class="space"></div>
                <p style="text-decoration: underline; font-weight: bold;">{{ auth()->guard('admin')->user()->nama }}</p>
            </td>
        </tr>
    </table>

</body>
</html>
