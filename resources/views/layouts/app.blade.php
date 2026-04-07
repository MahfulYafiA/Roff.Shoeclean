<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ROFF.SHOECLEAN - Perawatan Sepatu Premium')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #2563eb; --surface: #fafafa; --text: #0f172a; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--surface); color: var(--text); overflow-x: hidden; }
        
        .noise { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; pointer-events: none; z-index: 9999; opacity: 0.03; background: url('data:image/svg+xml;utf8,%3Csvg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"%3E%3Cfilter id="noiseFilter"%3E%3CfeTurbulence type="fractalNoise" baseFrequency="0.65" numOctaves="3" stitchTiles="stitch"/%3E%3C/filter%3E%3Crect width="100%25" height="100%25" filter="url(%23noiseFilter)"/%3E%3C/svg%3E'); }
        .glass-nav { background: rgba(250, 250, 250, 0.7); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); border-bottom: 1px solid rgba(0, 0, 0, 0.03); }
        .blue-glow { background: radial-gradient(circle at 50% 50%, rgba(37, 99, 235, 0.08) 0%, transparent 60%); }
        .text-gradient { background: linear-gradient(135deg, #0f172a 0%, #334155 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .luxury-shadow { box-shadow: 0 24px 48px -12px rgba(15, 23, 42, 0.05); }
    </style>
</head>
<body class="antialiased selection:bg-blue-600 selection:text-white">
    <div class="noise"></div>

    {{-- NAVBAR RESPONSIF --}}
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300">
        <div class="w-full px-4 md:px-6 lg:px-12 py-4 lg:py-6 flex justify-between items-center gap-2">
            
            {{-- LOGO: Dikecilkan di HP --}}
            <a href="/" class="font-black text-xl lg:text-3xl tracking-tighter uppercase shrink-0">
                ROFF.<span class="text-blue-600">SHOECLEAN</span>
            </a>

            <div class="flex items-center gap-3 lg:gap-10">
                {{-- Menu Tengah (Hanya di Desktop) --}}
                <div class="hidden md:flex gap-10 items-center font-bold text-[11px] uppercase tracking-[0.2em] text-slate-500">
                    <a href="#layanan" class="hover:text-blue-600 transition-colors">Layanan</a>
                    <a href="#alur" class="hover:text-blue-600 transition-colors">Alur Kerja</a>
                </div>

                @auth
                    {{-- PROFIL (Sudah Login) --}}
                    <a href="{{ url('/dashboard') }}" class="flex items-center gap-2 lg:gap-3 bg-white border border-slate-200 p-1 lg:p-1.5 pr-3 lg:pr-5 rounded-2xl hover:border-blue-600 hover:shadow-lg transition-all shrink-0">
                        <div class="w-8 h-8 lg:w-10 lg:h-10 rounded-xl overflow-hidden bg-blue-600 flex-shrink-0">
                            @if(auth()->user()->foto)
                                <img src="{{ asset('storage/' . auth()->user()->foto) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-white font-black text-[10px] lg:text-xs">
                                    {{ strtoupper(substr(auth()->user()->nama, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col text-left">
                            <span class="text-[6px] lg:text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] leading-none mb-0.5 lg:mb-1">Online</span>
                            <span class="text-[9px] lg:text-xs font-black text-slate-900 leading-none truncate max-w-[50px] lg:max-w-none">
                                {{ explode(' ', auth()->user()->nama)[0] }}
                            </span>
                        </div>
                    </a>
                @else
                    {{-- TOMBOL (Belum Login) --}}
                    <div class="flex items-center gap-2 lg:gap-6 pl-2 lg:pl-6 border-l border-slate-200 shrink-0">
                        <a href="{{ route('login') }}" class="text-[10px] lg:text-[11px] font-black uppercase tracking-widest text-slate-500 hover:text-blue-600 whitespace-nowrap">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 lg:px-8 lg:py-3.5 rounded-full font-black uppercase text-[9px] lg:text-[11px] tracking-widest shadow-lg shadow-blue-500/20 hover:bg-slate-900 transition-all whitespace-nowrap">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <div class="pt-20"> {{-- Spacer agar konten tidak tertutup nav fixed --}}
        @yield('content')
    </div>

    {{-- FOOTER RESPONSIF --}}
    <footer class="py-12 lg:py-16 px-6 lg:px-12 bg-white border-t border-slate-100">
        <div class="max-w-full mx-auto flex flex-col md:flex-row justify-between items-center md:items-start gap-10 md:gap-12">
            
            {{-- Bagian Logo Footer --}}
            <div class="text-center md:text-left flex-1 w-full">
                <a href="#" class="font-black text-2xl lg:text-3xl tracking-tighter block mb-3 uppercase italic">
                    ROFF.<span class="text-blue-600">SHOECLEAN</span>
                </a>
                <p class="text-slate-400 font-medium text-xs md:text-sm max-w-xs mx-auto md:mx-0 leading-relaxed uppercase tracking-wider">
                    Sistem Perawatan Sepatu Premium &copy; 2026
                </p>
            </div>

            {{-- Bagian Instagram --}}
            <div class="flex flex-col items-center gap-4 flex-1 w-full">
                <p class="text-[9px] lg:text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">Ikuti Kami</p>
                <a href="https://instagram.com/roff.shoeclean" target="_blank" class="group flex items-center gap-3 lg:gap-4 bg-slate-50 border border-slate-100 pl-2 pr-5 lg:pr-6 py-2 rounded-2xl hover:border-blue-600 hover:bg-white transition-all shadow-sm">
                    <div class="w-8 h-8 lg:w-10 lg:h-10 bg-gradient-to-tr from-yellow-400 via-red-500 to-purple-600 rounded-xl flex items-center justify-center text-white text-lg group-hover:scale-110 transition-transform">
                        <i class="fa-brands fa-instagram"></i>
                    </div>
                    <span class="text-[11px] lg:text-xs font-black text-slate-900 tracking-tight">@roff.shoeclean</span>
                </a>
            </div>

            {{-- Bagian Link Bantuan --}}
            <div class="flex flex-wrap justify-center md:justify-end items-center gap-4 lg:gap-8 font-black text-[9px] lg:text-[10px] uppercase tracking-[0.2em] text-slate-400 flex-1 w-full pt-4 md:pt-0">
                <a href="https://wa.me/6281234567890" target="_blank" class="text-blue-600 flex items-center gap-2 bg-blue-50 px-4 py-2 rounded-full hover:bg-blue-600 hover:text-white transition-all">
                    <i class="fa-brands fa-whatsapp text-sm"></i> 
                    Hubungi Kami
                </a>
                <a href="#" class="hover:text-blue-600 transition-colors">Syarat & Ketentuan</a>
            </div>
        </div>
    </footer>
</body>
</html>