<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - ROFF.ADMIN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0f172a; overflow: hidden; } 
        
        /* Glassmorphism Card (Tema Admin) */
        .glass-card { 
            background: rgba(30, 41, 59, 0.4); 
            backdrop-filter: blur(12px); 
            border: 1px solid rgba(255, 255, 255, 0.05); 
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card:hover { background: rgba(30, 41, 59, 0.8); transform: translateY(-8px); border-color: rgba(59, 130, 246, 0.3); /* Warna hover border biru */ }

        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    </style>
</head>
<body class="text-slate-200 antialiased flex h-screen overflow-hidden relative">

    {{-- MAIN CONTENT AREA (TANPA SIDEBAR SAMA SEKALI) --}}
    <main class="flex-1 flex flex-col h-screen min-w-0 bg-[#0f172a] overflow-hidden relative z-10">
        
        {{-- TOP NAVIGATION --}}
        <header class="bg-[#0f172a]/40 backdrop-blur-xl px-6 md:px-12 py-4 flex justify-between items-center shrink-0 z-40 border-b border-white/5">
            
            {{-- LOGO & TOMBOL HOME KIRI --}}
            <div class="flex items-center gap-3 md:gap-4">
                <a href="{{ url('/') }}" class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-slate-800/50 border border-slate-700 text-slate-400 hover:text-blue-400 hover:bg-slate-800 transition-all flex items-center justify-center shadow-sm group active:scale-95" title="Kembali ke Beranda Utama">
                    <i class="fa-solid fa-house text-sm group-hover:scale-110 transition-transform"></i>
                </a>
                
                <h1 class="block font-black text-xl md:text-2xl uppercase tracking-tighter italic text-white leading-tight">
                    ROFF.<span class="text-blue-500">ADMIN</span>
                </h1>
            </div>
            
            {{-- PROFIL KANAN ATAS --}}
            <div class="flex items-center gap-5">
                <div class="flex items-center bg-slate-800/40 border border-slate-700 p-1 pr-4 rounded-full shadow-inner">
                    <div class="w-8 h-8 rounded-full overflow-hidden bg-blue-600 flex items-center justify-center text-[10px] font-black text-white border border-slate-700 shadow-xl shadow-blue-500/20">
                        @if(auth()->user()->foto_profil)
                            <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->nama, 0, 2)) }}
                        @endif
                    </div>
                    <div class="ml-3 hidden sm:block">
                        <p class="text-[10px] font-black text-white uppercase tracking-widest leading-none">{{ explode(' ', auth()->user()->nama)[0] }}</p>
                        <p class="text-[7px] font-bold text-blue-400/80 uppercase mt-0.5 tracking-tighter">Staff Access</p>
                    </div>
                    <div class="w-px h-5 bg-slate-700 mx-4"></div>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="text-red-400 hover:text-red-500 hover:scale-110 transition-all flex items-center justify-center" title="Logout">
                            <i class="fa-solid fa-power-off text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- CONTENT AREA --}}
        <div class="flex-1 flex flex-col justify-start lg:justify-center p-5 md:p-8 lg:p-12 overflow-y-auto lg:overflow-hidden custom-scroll z-10 relative">
            
            {{-- Background Glow Biru --}}
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-600/10 blur-[120px] rounded-full pointer-events-none -translate-y-1/2 translate-x-1/4"></div>

            {{-- HERO SECTION --}}
            <div class="relative rounded-[2rem] md:rounded-[3rem] p-6 md:p-10 lg:p-12 overflow-hidden mb-6 shadow-2xl shadow-black/40 group shrink-0">
                <div class="absolute inset-0 bg-gradient-to-br from-[#1e293b] to-[#0f172a] group-hover:scale-105 transition-transform duration-1000"></div>
                <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-indigo-600/10 blur-[80px] rounded-full translate-y-1/2 -translate-x-1/4 pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 bg-blue-500/10 border border-blue-500/20 px-4 py-1.5 rounded-full mb-4 md:mb-6">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse shadow-[0_0_10px_rgba(59,130,246,1)]"></span>
                        <p class="text-[8px] md:text-[9px] font-black text-blue-400 uppercase tracking-[0.4em]">Sistem Manajemen ROFF</p>
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white tracking-tighter mb-4 md:mb-6 leading-none">
                        Selamat Datang, <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-200 italic">{{ auth()->user()->nama ?? 'Admin' }}!</span>
                    </h1>
                    <p class="text-slate-400 font-medium text-sm md:text-base max-w-3xl leading-relaxed mb-0">
                        Pantau operasional bisnis ROFF.SHOECLEAN secara real-time. Kelola antrean, update harga layanan, hingga pantau performa omset harian.
                    </p>
                </div>
            </div>

            {{-- GRID MENU (TEMA ADMIN) --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 md:gap-6 shrink-0">
                @php
                    $menus = [
                        ['url' => route('profil.index'), 'icon' => 'fa-id-card-clip', 'title' => 'Profil', 'desc' => 'Admin Settings', 'color' => 'text-blue-400', 'bg' => 'bg-blue-500/10'],
                        ['url' => route('admin.antrean'), 'icon' => 'fa-clipboard-list', 'title' => 'Antrean', 'desc' => 'Update Status Cuci', 'color' => 'text-emerald-400', 'bg' => 'bg-emerald-500/10'],
                        ['url' => route('admin.laporan'), 'icon' => 'fa-chart-pie', 'title' => 'Laporan', 'desc' => 'Rekap Omset Harian', 'color' => 'text-cyan-400', 'bg' => 'bg-cyan-500/10'],
                        ['url' => route('admin.users'), 'icon' => 'fa-users', 'title' => 'Pelanggan', 'desc' => 'Database Customer', 'color' => 'text-purple-400', 'bg' => 'bg-purple-500/10'],
                        ['url' => route('admin.layanan.index'), 'icon' => 'fa-boxes-stacked', 'title' => 'Layanan', 'desc' => 'Menu Harga & Jasa', 'color' => 'text-pink-400', 'bg' => 'bg-pink-500/10'],
                    ];
                @endphp

                @foreach($menus as $menu)
                <a href="{{ $menu['url'] }}" class="glass-card group p-5 lg:p-8 rounded-[1.5rem] lg:rounded-[2rem] flex flex-col justify-center items-start h-full">
                    <div class="w-10 h-10 lg:w-14 lg:h-14 {{ $menu['bg'] }} {{ $menu['color'] }} rounded-[1rem] flex items-center justify-center mb-3 lg:mb-6 group-hover:scale-110 group-hover:shadow-[0_0_20px_rgba(59,130,246,0.2)] transition-all duration-500 shadow-lg shadow-black/20">
                        <i class="fa-solid {{ $menu['icon'] }} text-lg lg:text-xl"></i>
                    </div>
                    <h3 class="font-black text-sm lg:text-xl text-white mb-1 uppercase tracking-tighter leading-none group-hover:text-blue-400 transition-colors">{{ $menu['title'] }}</h3>
                    <p class="text-slate-500 text-[8px] lg:text-[10px] font-bold uppercase tracking-widest italic opacity-70">{{ $menu['desc'] }}</p>
                </a>
                @endforeach
            </div>

            {{-- FOOTER --}}
            <div class="mt-auto pt-6 pb-2 border-t border-white/5 flex justify-center items-center opacity-40 shrink-0">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] w-full text-center text-white">© 2026 ROFF.ADMIN PANEL CONTROL</p>
            </div>
        </div>
    </main>

</body>
</html>