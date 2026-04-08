<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Superadmin - ROFF.SHOECLEAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .hover-shadow-luxury { transition: all 0.4s ease; }
        .hover-shadow-luxury:hover { transform: translateY(-5px); box-shadow: 0 20px 40px -15px rgba(0,0,0,0.05); }
    </style>
</head>
<body class="text-slate-900 antialiased min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="w-full px-4 md:px-6 lg:px-12 flex justify-between items-center py-3 md:py-4 gap-2">
            <div class="flex items-center gap-4">
                <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-2 md:gap-4 hover:text-blue-600 transition-colors group shrink-0">
                    <i class="fa-solid fa-arrow-left text-slate-400 group-hover:text-blue-600 transition-transform group-hover:-translate-x-1 hidden sm:block"></i>
                    <span class="text-[9px] md:text-[11px] font-black uppercase tracking-[0.2em] mt-0.5 text-slate-400 group-hover:text-blue-600">Beranda Utama</span>
                </a>
            </div>
            
            <div class="flex items-center gap-4 md:gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-black text-[10px]">SU</div>
                    <p class="font-black text-[10px] md:text-xs uppercase tracking-widest hidden sm:block">SUPERADMIN</p>
                </div>
                <div class="w-px h-6 bg-slate-200 hidden sm:block"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-[9px] md:text-[10px] font-black uppercase tracking-widest text-red-500 hover:text-red-700 transition-colors flex items-center gap-2">
                        Keluar <i class="fa-solid fa-power-off"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- KONTEN UTAMA --}}
    <main class="w-full px-4 md:px-6 lg:px-12 py-8 md:py-12 flex-1 flex flex-col">
        
        {{-- HERO SECTION --}}
        <div class="bg-slate-900 rounded-[2rem] md:rounded-[3rem] p-8 md:p-14 mb-8 md:mb-12 relative overflow-hidden shadow-2xl shadow-slate-900/20">
            <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            <div class="relative z-10 max-w-2xl">
                <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight mb-4 md:mb-6">
                    Selamat Datang, <span class="text-blue-400 italic">SUPERADMIN</span>!
                </h1>
                <p class="text-slate-300 font-medium text-sm md:text-base leading-relaxed">
                    Pusat kendali Owner. Pantau ringkasan pendapatan bisnis, kelola hak akses admin, dan kelola informasi profil Anda di sini.
                </p>
            </div>
        </div>

        {{-- MENU CARDS --}}
        {{-- 🚨 UBAH GRID: Menjadi 4 kolom (lg:grid-cols-4) agar muat 4 kotak secara sejajar --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
            
            {{-- CARD 1: KELOLA PROFIL --}}
            <a href="{{ route('profil.index') }}" class="bg-white rounded-[2rem] p-8 md:p-10 border border-slate-100 hover-shadow-luxury group flex flex-col h-full">
                <div class="w-12 h-12 md:w-14 md:h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-xl md:text-2xl mb-6 md:mb-8 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-user-gear"></i>
                </div>
                <h3 class="font-black text-lg md:text-xl text-slate-900 uppercase tracking-tight mb-2 md:mb-3 group-hover:text-indigo-600 transition-colors">KELOLA PROFIL</h3>
                <p class="text-xs md:text-sm text-slate-500 font-medium leading-relaxed">Perbarui data diri, nomor telepon, dan alamat Anda.</p>
            </a>

            {{-- CARD 2: OMSET --}}
            <a href="{{ route('superadmin.laporan') }}" class="bg-white rounded-[2rem] p-8 md:p-10 border border-slate-100 hover-shadow-luxury group flex flex-col h-full">
                <div class="w-12 h-12 md:w-14 md:h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl md:text-2xl mb-6 md:mb-8 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <h3 class="font-black text-lg md:text-xl text-slate-900 uppercase tracking-tight mb-2 md:mb-3 group-hover:text-blue-600 transition-colors">OMSET</h3>
                <p class="text-xs md:text-sm text-slate-500 font-medium leading-relaxed">Pantau statistik pendapatan bisnis dari admin secara menyeluruh.</p>
            </a>

            {{-- CARD 3: MANAJEMEN USER --}}
            <a href="{{ route('superadmin.users') }}" class="bg-white rounded-[2rem] p-8 md:p-10 border border-slate-100 hover-shadow-luxury group flex flex-col h-full relative overflow-hidden">
                <div class="absolute top-8 right-8 w-2 h-2 rounded-full bg-emerald-400"></div>
                <div class="w-12 h-12 md:w-14 md:h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl md:text-2xl mb-6 md:mb-8 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-users-gear"></i>
                </div>
                <h3 class="font-black text-lg md:text-xl text-slate-900 uppercase tracking-tight mb-2 md:mb-3 group-hover:text-emerald-600 transition-colors">MANAJEMEN USER</h3>
                <p class="text-xs md:text-sm text-slate-500 font-medium leading-relaxed">Kelola data akun, tambah admin baru, dan atur hak akses sistem.</p>
            </a>

            {{-- 🚨 CARD 4: KELOLA LAYANAN (TAMBAHAN BARU) 🚨 --}}
            <a href="{{ route('superadmin.layanan.index') }}" class="bg-white rounded-[2rem] p-8 md:p-10 border border-slate-100 hover-shadow-luxury group flex flex-col h-full relative overflow-hidden">
                <div class="w-12 h-12 md:w-14 md:h-14 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-xl md:text-2xl mb-6 md:mb-8 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-tags"></i>
                </div>
                <h3 class="font-black text-lg md:text-xl text-slate-900 uppercase tracking-tight mb-2 md:mb-3 group-hover:text-purple-600 transition-colors">KELOLA LAYANAN</h3>
                <p class="text-xs md:text-sm text-slate-500 font-medium leading-relaxed">Kontrol penuh untuk menambah, mengubah, dan menghapus daftar layanan.</p>
            </a>

        </div>
    </main>
</body>
</html>