<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - LMS TPQ</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Amiri:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top right, #e8f5e9 0%, #c8e6c9 40%, #a5d6a7 100%);
        }
        .arabic-text {
            font-family: 'Amiri', serif;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-white/40 overflow-hidden transition-all duration-300 hover:shadow-2xl">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-emerald-800 to-emerald-700 p-8 text-center relative">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(255,255,255,0.15),transparent)] pointer-events-none"></div>
            <!-- Arabic Ornament/Greeting -->
            <div class="text-amber-400 arabic-text text-3xl font-bold mb-2">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</div>
            <h1 class="text-white text-2xl font-bold tracking-tight">LMS TPQ</h1>
            <p class="text-emerald-100 text-sm mt-1">Sistem Informasi Manajemen & Belajar Santri</p>
        </div>

        <!-- Card Body -->
        <div class="p-8">
            <!-- Status Message (e.g. from logout) -->
            @if (session('status'))
                <div class="mb-4 p-3 bg-emerald-50 border-l-4 border-emerald-500 rounded text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Errors -->
            @if ($errors->any())
                <div class="mb-4 p-3 bg-rose-50 border-l-4 border-rose-500 rounded text-sm text-rose-800">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ url('/login') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Username / Email Input -->
                <div>
                    <label for="login" class="block text-sm font-semibold text-emerald-950 mb-2">Username atau Email</label>
                    <div class="relative">
                        <input type="text" name="login" id="login" required value="{{ old('login') }}" autofocus
                            placeholder="Email (Pengurus) atau Username (Santri)"
                            class="w-full px-4 py-3 bg-white/70 border border-emerald-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition duration-200 placeholder-emerald-800/30 text-emerald-950">
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-emerald-950 mb-2">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                            placeholder="Masukkan password Anda"
                            class="w-full px-4 py-3 bg-white/70 border border-emerald-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition duration-200 placeholder-emerald-800/30 text-emerald-950">
                    </div>
                </div>

                <!-- Remember Me and Action Button -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center text-sm cursor-pointer select-none text-emerald-900 font-medium">
                        <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-emerald-600 border-emerald-300 rounded focus:ring-emerald-500 mr-2 transition duration-200">
                        Ingat Saya
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 text-white font-bold rounded-xl shadow-md hover:from-emerald-700 hover:to-emerald-600 transition duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 active:scale-[0.98]">
                        Masuk Aplikasi
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="bg-emerald-50/50 p-4 border-t border-emerald-100 text-center text-xs text-emerald-800 font-medium">
            &copy; 2026 LMS TPQ - Platform Pembelajaran Qur'an
        </div>
    </div>
</body>
</html>
