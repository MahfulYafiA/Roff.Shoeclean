<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - ROFF.SHOECLEAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fafafa; } </style>
</head>
<body class="text-slate-900 antialiased min-h-screen flex flex-col relative pb-24">

    {{-- NAVBAR ADMIN --}}
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-full mx-auto px-4 md:px-6 lg:px-10 py-4 md:py-5 flex justify-between items-center gap-2">
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-2 text-xs md:text-sm font-bold text-slate-500 hover:text-blue-600 uppercase tracking-widest transition-colors">
                    <i class="fa-solid fa-arrow-left"></i> Beranda Utama
                </a>
            </div>
            
            <div class="flex items-center shrink-0">
                <div class="flex items-center bg-white border border-slate-200 p-1 pr-4 rounded-full shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 md:w-8 md:h-8 rounded-full overflow-hidden bg-blue-600 text-white flex items-center justify-center text-[9px] md:text-[10px] font-black border border-white shadow-sm shrink-0">
                            @if(auth()->user()->foto_profil)
                                <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr(auth()->user()->nama, 0, 2)) }}
                            @endif
                        </div>
                        <span class="text-[9px] md:text-[10px] font-black text-slate-800 uppercase tracking-widest truncate max-w-[80px] md:max-w-[100px]">
                            {{ explode(' ', auth()->user()->nama)[0] }}
                        </span>
                    </div>
                    <div class="w-px h-4 bg-slate-300 mx-3 md:mx-4"></div>
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0 flex items-center">
                        @csrf
                        <button type="submit" class="flex items-center gap-1.5 text-[9px] md:text-[10px] font-bold uppercase tracking-widest text-red-500 hover:text-red-700 transition-colors group">
                            Keluar <i class="fa-solid fa-power-off text-[9px] group-hover:scale-110 transition-transform"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- KONTEN UTAMA --}}
    <main class="w-full px-4 md:px-6 lg:px-12 py-8 md:py-10 flex-1">
        
        {{-- HERO SECTION --}}
        <div class="bg-[#0B1120] rounded-[2rem] md:rounded-[2.5rem] px-8 py-12 md:px-12 md:py-16 lg:px-16 lg:py-20 relative overflow-hidden mb-8 md:mb-10 shadow-xl shadow-slate-900/5">
            {{-- Glow effect --}}
            <div class="absolute top-0 right-0 w-64 h-64 md:w-[600px] md:h-[600px] bg-blue-600/20 blur-[80px] md:blur-[120px] rounded-full pointer-events-none -translate-y-1/2 translate-x-1/4"></div>

            <div class="relative z-10">
                <h1 class="text-4xl md:text-5xl lg:text-[4rem] font-black text-white tracking-tighter mb-4 leading-tight">
                    Selamat Datang, <span class="text-blue-500 italic lowercase">{{ auth()->user()->nama ?? 'admin' }}</span>!
                </h1>
                <p class="text-slate-400 font-medium text-sm md:text-base max-w-2xl leading-relaxed">
                    Ini adalah pusat kendali Anda. Kelola pengguna, pantau antrean pesanan, dan cek laporan pendapatan di sini.
                </p>
            </div>
        </div>

        {{-- GRID 4 KOTAK MENU ADMIN - Dikasih z-20 supaya nggak ketutup glow hero --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8 mt-8 relative z-20">
            
            {{-- KOTAK 1: KELOLA PROFIL --}}
            <a href="{{ route('profil.index') }}" class="group block bg-white rounded-[2.5rem] p-8 md:p-10 border border-slate-100 shadow-xl shadow-slate-200/20 hover:-translate-y-2 hover:bg-blue-600 hover:shadow-2xl hover:shadow-blue-500/30 transition-all duration-500">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-white/20 group-hover:text-white transition-colors duration-500">
                    <i class="fa-solid fa-user-gear text-xl"></i>
                </div>
                <h3 class="font-black text-xl lg:text-2xl text-slate-900 mb-3 group-hover:text-white transition-colors duration-500 uppercase leading-none tracking-tighter">Kelola Profil</h3>
                <p class="text-slate-500 font-medium text-sm leading-relaxed group-hover:text-blue-100 transition-colors duration-500">Perbarui data diri, nomor telepon, dan alamat Anda.</p>
            </a>

            {{-- KOTAK 2: ANTREAN PESANAN --}}
            <a href="{{ route('admin.antrean') }}" class="group block bg-white rounded-[2.5rem] p-8 md:p-10 border border-slate-100 shadow-xl shadow-slate-200/20 hover:-translate-y-2 hover:bg-blue-600 hover:shadow-2xl hover:shadow-blue-500/30 transition-all duration-500">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-white/20 group-hover:text-white transition-colors duration-500">
                    <i class="fa-solid fa-clipboard-list text-xl"></i>
                </div>
                <h3 class="font-black text-xl lg:text-2xl text-slate-900 mb-3 group-hover:text-white transition-colors duration-500 uppercase leading-none tracking-tighter">Antrean Pesanan</h3>
                <p class="text-slate-500 font-medium text-sm leading-relaxed group-hover:text-blue-100 transition-colors duration-500">Validasi reservasi masuk dan update status cuci sepatu.</p>
            </a>

            {{-- KOTAK 3: LAPORAN PENDAPATAN --}}
            <a href="{{ route('admin.laporan') }}" class="group block bg-white rounded-[2.5rem] p-8 md:p-10 border border-slate-100 shadow-xl shadow-slate-200/20 hover:-translate-y-2 hover:bg-blue-600 hover:shadow-2xl hover:shadow-blue-500/30 transition-all duration-500">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-white/20 group-hover:text-white transition-colors duration-500">
                    <i class="fa-solid fa-chart-line text-xl"></i>
                </div>
                <h3 class="font-black text-xl lg:text-2xl text-slate-900 mb-3 group-hover:text-white transition-colors duration-500 uppercase leading-none tracking-tighter">Laporan Omzet</h3>
                <p class="text-slate-500 font-medium text-sm leading-relaxed group-hover:text-blue-100 transition-colors duration-500">Rekapitulasi omzet dari cucian yang selesai hari ini.</p>
            </a>

            {{-- KOTAK 4: MANAJEMEN USER --}}
            <a href="{{ route('admin.users') }}" class="group block bg-white rounded-[2.5rem] p-8 md:p-10 border border-slate-100 shadow-xl shadow-slate-200/20 hover:-translate-y-2 hover:bg-blue-600 hover:shadow-2xl hover:shadow-blue-500/30 transition-all duration-500">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-white/20 group-hover:text-white transition-colors duration-500">
                    <i class="fa-solid fa-users-gear text-xl"></i>
                </div>
                <h3 class="font-black text-xl lg:text-2xl text-slate-900 mb-3 group-hover:text-white transition-colors duration-500 uppercase leading-none tracking-tighter">Manajemen User</h3>
                <p class="text-slate-500 font-medium text-sm leading-relaxed group-hover:text-blue-100 transition-colors duration-500">Kelola data akun, hak akses, dan status pengguna ROFF.</p>
            </a>

        </div>
    </main>

</body>
</html>