<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#064e3b">
    <title>Koneksi Terputus - LMS TPQ</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f0f4f1;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 text-center">
    <div class="max-w-md w-full bg-white rounded-3xl p-8 border border-gray-100 shadow-xl space-y-6">
        <div class="w-20 h-20 rounded-full bg-emerald-50 text-emerald-800 flex items-center justify-center text-4xl mx-auto shadow-sm">
            <i class="fa-solid fa-wifi-slash animate-pulse"></i>
        </div>
        
        <div class="space-y-2">
            <h1 class="text-xl font-extrabold text-gray-900">Koneksi Internet Terputus</h1>
            <p class="text-xs text-gray-500 leading-relaxed px-4">
                Oops! Anda sedang offline. Hubungkan perangkat Anda ke internet atau WiFi untuk memuat halaman ini.
            </p>
        </div>

        <div class="bg-emerald-50/50 border border-emerald-100 rounded-2xl p-4 text-left">
            <h4 class="text-xs font-bold text-emerald-800 mb-1 flex items-center">
                <i class="fa-solid fa-lightbulb mr-1.5"></i>Tips Belajar Offline:
            </h4>
            <p class="text-[10px] text-emerald-700 leading-relaxed font-semibold">
                Anda masih dapat mengakses materi Al-Qur'an, doa-doa harian, dan hadist pilihan yang telah dibuka sebelumnya tanpa koneksi internet!
            </p>
        </div>

        <div class="flex flex-col space-y-2.5">
            <button onclick="window.location.reload()"
                class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-bold py-3 rounded-xl transition text-xs shadow-sm">
                Coba Hubungkan Kembali
            </button>
            <a href="{{ route('murid.dashboard') }}"
                class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-xl transition text-xs border border-gray-250 block">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
