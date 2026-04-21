@php
    // Ambil data banner hero dari database
    $heroSetting = Illuminate\Support\Facades\DB::table('ms_pengaturan')->where('key', 'hero_image')->first();
    $heroPath = ($heroSetting && $heroSetting->value) 
                ? asset('storage/' . $heroSetting->value) 
                : asset('images/adidasspezial.png');

    // ✅ AMBIL DATA FOTO TENTANG KAMI DARI DATABASE
    $tentangSetting = Illuminate\Support\Facades\DB::table('ms_pengaturan')->where('key', 'tentang_image')->first();
    $tentangPath = ($tentangSetting && $tentangSetting->value) 
                ? asset('storage/' . $tentangSetting->value) 
                : asset('images/fototentangkami.jpeg'); // Gambar default jika admin belum upload
@endphp

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ROFF.SHOECLEAN - Perawatan Sepatu Premium</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- LIBRARY AOS (Animate On Scroll) CSS --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root { 
            --primary: #2563eb; 
            --surface: #f8fafc; 
            --text: #0f172a; 
            --cyan-accent: #06b6d4;
            --purple-accent: #8b5cf6;
        }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--surface); 
            color: var(--text); 
            overflow-x: hidden; 
        }
        
        /* --- BACKGROUND PREMIUM GLOBAL (MESH & ORBS) --- */
        .noise { 
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; 
            pointer-events: none; z-index: 9999; opacity: 0.035; 
            background: url('data:image/svg+xml;utf8,%3Csvg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"%3E%3Cfilter id="noiseFilter"%3E%3CfeTurbulence type="fractalNoise" baseFrequency="0.65" numOctaves="3" stitchTiles="stitch"/%3E%3C/filter%3E%3Crect width="100%25" height="100%25" filter="url(%23noiseFilter)"/%3E%3C/svg%3E'); 
        }
        .bg-mesh {
            position: fixed; inset: 0; z-index: -3;
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 32px 32px; 
            opacity: 0.5;
        }
        .orb-1, .orb-2, .orb-3 {
            position: fixed; border-radius: 50%; filter: blur(140px); z-index: -2;
            animation: floatOrb 15s infinite alternate ease-in-out;
        }
        .orb-1 { width: 600px; height: 600px; background: rgba(37, 99, 235, 0.15); top: -100px; left: -100px; }
        .orb-2 { width: 700px; height: 700px; background: rgba(56, 189, 248, 0.12); bottom: -200px; right: -100px; animation-delay: -5s; }
        .orb-3 { width: 500px; height: 500px; background: rgba(139, 92, 246, 0.1); top: 40%; left: 30%; animation-delay: -10s; }
        
        @keyframes floatOrb {
            0% { transform: translate(0, 0) scale(1) rotate(0deg); }
            100% { transform: translate(100px, 80px) scale(1.2) rotate(10deg); }
        }

        /* --- GLASSMORPHISM PREMIUM --- */
        .glass-nav { 
            background: rgba(255, 255, 255, 0.65); 
            backdrop-filter: blur(28px); 
            -webkit-backdrop-filter: blur(28px); 
            border-bottom: 1px solid rgba(255, 255, 255, 0.5); 
        }
        
        .glass-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.3));
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.7);
            box-shadow: 0 16px 48px -12px rgba(31, 38, 135, 0.08);
        }

        /* --- TEXT GRADIENTS & ACCENTS --- */
        .text-gradient { 
            background: linear-gradient(135deg, #0f172a 0%, #334155 100%); 
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
        }
        .text-premium-blue { 
            background: linear-gradient(135deg, var(--cyan-accent) 0%, var(--purple-accent) 100%); 
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
        }
        
        /* --- UTILITIES & SHADOWS --- */
        .luxury-shadow { box-shadow: 0 32px 64px -16px rgba(15, 23, 42, 0.06); }
        .glow-shadow { box-shadow: 0 0 50px -10px rgba(37,99,235,0.4); }
        .glow-shadow-light { box-shadow: 0 0 30px -5px rgba(37,99,235,0.2); }
        
        .hover-lift { transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
        .hover-lift:hover { transform: translateY(-10px) scale(1.02); box-shadow: 0 40px 80px -16px rgba(15, 23, 42, 0.12); z-index: 10; }
        
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 7s ease-in-out infinite; }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="antialiased selection:bg-blue-600 selection:text-white">

    <div class="noise"></div>
    <div class="bg-mesh"></div>
    <div class="orb-1"></div>
    <div class="orb-2"></div>
    <div class="orb-3"></div>

    <nav class="fixed w-full z-50 glass-nav transition-all duration-300 border-b-2 border-slate-100/50" data-aos="fade-down" data-aos-duration="1000">
        <div class="w-full px-5 md:px-10 lg:px-12 py-4 md:py-6 flex justify-between items-center gap-2 relative">
            
            <div class="flex items-center gap-3">
                @auth
                <a href="{{ route('profil.index') }}" class="sm:hidden w-10 h-10 rounded-full overflow-hidden bg-blue-600 text-white flex items-center justify-center text-[11px] font-black border-2 border-white/80 shadow-md active:scale-95 transition-transform shrink-0 relative">
                    @if(auth()->user()->foto_profil)
                        <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(auth()->user()->nama, 0, 2)) }}
                    @endif
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-400 border border-white rounded-full"></span>
                </a>
                @endauth

                <a href="#" class="font-black text-2xl md:text-3xl lg:text-4xl tracking-tighter uppercase whitespace-nowrap shrink-0 group">
                    ROFF.<span class="text-blue-600 group-hover:text-cyan-500 transition-colors duration-500">SHOECLEAN</span>
                </a>
            </div>
            
            <div class="flex items-center gap-3 lg:gap-10">
                <div class="hidden md:flex gap-8 lg:gap-10 items-center font-bold text-[10px] lg:text-[11px] uppercase tracking-[0.25em]">
                    <a href="#beranda" class="nav-item text-slate-900 hover:text-blue-600 transition-colors">Beranda</a>
                    <a href="#alur" class="nav-item text-slate-400 hover:text-blue-600 transition-colors">Panduan</a>
                    <a href="#tentang" class="nav-item text-slate-400 hover:text-blue-600 transition-colors">Tentang</a>
                    <a href="#layanan" class="nav-item text-slate-400 hover:text-blue-600 transition-colors">Layanan</a>
                    <a href="#lokasi" class="nav-item text-slate-400 hover:text-blue-600 transition-colors">Lokasi</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="nav-item text-slate-400 hover:text-blue-600 transition-colors">Dashboard</a>
                    @endauth
                </div>

                @auth
                    <div class="hidden sm:flex items-center shrink-0">
                        <div class="flex items-center bg-white/60 backdrop-blur-xl border border-white/80 p-1.5 pr-5 rounded-full shadow-sm hover:shadow-lg hover:bg-white transition-all">
                            <a href="{{ route('profil.index') }}" class="flex items-center gap-2.5 group">
                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full overflow-hidden bg-gradient-to-tr from-blue-600 to-cyan-500 text-white flex items-center justify-center text-[10px] md:text-[11px] font-black border border-white/80 shadow-sm group-hover:scale-105 transition-transform shrink-0">
                                    @if(auth()->user()->foto_profil)
                                        <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" class="w-full h-full object-cover">
                                    @else
                                        {{ strtoupper(substr(auth()->user()->nama, 0, 2)) }}
                                    @endif
                                </div>
                                <span class="text-[10px] md:text-[11px] font-black text-slate-800 uppercase tracking-widest group-hover:text-blue-600 transition-colors truncate max-w-[90px]">
                                    {{ explode(' ', auth()->user()->nama)[0] }}
                                </span>
                            </a>
                            <div class="w-px h-5 bg-slate-300 mx-3 md:mx-4"></div>
                            <form method="POST" action="{{ route('logout') }}" class="m-0 p-0 flex items-center">
                                @csrf
                                <button type="submit" class="flex items-center gap-1.5 text-[10px] md:text-[11px] font-bold uppercase tracking-widest text-red-500 hover:text-red-700 transition-colors group">
                                    Keluar <i class="fa-solid fa-power-off text-[10px] group-hover:scale-110 transition-transform"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="hidden md:flex items-center gap-4 lg:gap-6 pl-4 lg:pl-6 border-l border-slate-200 shrink-0">
                        <a href="{{ route('login') }}" class="text-[11px] lg:text-[12px] font-black uppercase tracking-widest text-slate-500 hover:text-blue-600 whitespace-nowrap transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-600 to-blue-500 text-white px-8 py-3 rounded-full font-black uppercase text-[11px] lg:text-[12px] tracking-widest shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:scale-105 transition-all duration-300 whitespace-nowrap">Daftar</a>
                    </div>
                @endauth

                <button onclick="document.getElementById('menu-hp').classList.toggle('hidden')" class="md:hidden ml-1 flex items-center justify-center w-11 h-11 bg-white rounded-xl text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition-colors border border-slate-200 shadow-sm focus:outline-none">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <div id="menu-hp" class="hidden absolute top-full left-0 w-full bg-white/95 backdrop-blur-2xl border-b-2 border-slate-200/50 shadow-2xl flex flex-col font-bold text-xs uppercase tracking-[0.25em] text-slate-500 transition-all">
            <a href="#beranda" onclick="document.getElementById('menu-hp').classList.add('hidden')" class="nav-item p-6 border-b border-slate-100/50 text-slate-900 hover:bg-blue-50/50 hover:text-blue-600 transition-colors text-center">Beranda</a>
            <a href="#alur" onclick="document.getElementById('menu-hp').classList.add('hidden')" class="nav-item p-6 border-b border-slate-100/50 text-slate-400 hover:bg-blue-50/50 hover:text-blue-600 transition-colors text-center">Panduan</a>
            <a href="#tentang" onclick="document.getElementById('menu-hp').classList.add('hidden')" class="nav-item p-6 border-b border-slate-100/50 text-slate-400 hover:bg-blue-50/50 hover:text-blue-600 transition-colors text-center">Tentang</a>
            <a href="#layanan" onclick="document.getElementById('menu-hp').classList.add('hidden')" class="nav-item p-6 border-b border-slate-100/50 text-slate-400 hover:bg-blue-50/50 hover:text-blue-600 transition-colors text-center">Layanan</a>
            <a href="#lokasi" onclick="document.getElementById('menu-hp').classList.add('hidden')" class="nav-item p-6 text-slate-400 border-b border-slate-100/50 hover:bg-blue-50/50 hover:text-blue-600 transition-colors text-center">Lokasi</a>
            @auth
                <a href="{{ route('dashboard') }}" onclick="document.getElementById('menu-hp').classList.add('hidden')" class="nav-item p-6 text-slate-400 border-b border-slate-100/50 hover:bg-blue-50/50 hover:text-blue-600 transition-colors text-center">Dashboard</a>
            @endauth
            
            @auth
                <div class="p-6 flex flex-col gap-4 bg-slate-50/80">
                    <form method="POST" action="{{ route('logout') }}" class="w-full m-0 p-0">
                        @csrf
                        <button type="submit" class="w-full text-center bg-red-50 text-red-600 py-5 rounded-2xl font-black uppercase tracking-widest border border-red-100 hover:bg-red-600 hover:text-white transition-colors">Keluar</button>
                    </form>
                </div>
            @else
                <div class="p-6 flex flex-col gap-4 bg-slate-50/80">
                    <a href="{{ route('login') }}" class="w-full text-center border border-slate-200/80 bg-white/70 backdrop-blur-lg py-5 rounded-2xl font-black uppercase text-slate-500 hover:border-blue-600 transition-colors shadow-sm tracking-widest">Masuk</a>
                    <a href="{{ route('register') }}" class="w-full text-center bg-blue-600 text-white py-5 rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-blue-500/30 hover:bg-slate-900 transition-colors tracking-widest">Daftar</a>
                </div>
            @endauth
        </div>
    </nav>

    <header id="beranda" class="relative w-full min-h-[100svh] flex flex-col justify-center items-center px-6 lg:px-16 xl:px-24 pt-24 pb-8 md:pt-28 md:pb-12 overflow-hidden bg-transparent scroll-mt-0">
        <div class="w-full mx-auto relative z-10 flex flex-col lg:grid lg:grid-cols-12 gap-6 md:gap-10 lg:gap-12 items-center flex-grow h-full justify-between md:justify-center">
            
            <div class="lg:col-span-7 text-center md:text-left flex flex-col items-center md:items-start w-full mt-4 md:mt-0">
                <div data-aos="fade-down" data-aos-duration="1000" class="inline-flex items-center gap-2 md:gap-3 border border-white/80 glass-card px-4 md:px-6 py-2 md:py-3 rounded-full text-[9px] md:text-[11px] font-black uppercase tracking-[0.25em] mb-4 md:mb-10 text-slate-700 mx-auto md:mx-0 shadow-lg glow-shadow-light">
                    <span class="relative flex h-1.5 w-1.5 md:h-2 md:w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-500 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 md:h-2 md:w-2 bg-blue-600"></span>
                    </span>
                    Solusi Perawatan Sepatu Anda
                </div>
                
                <h1 class="text-4xl sm:text-5xl md:text-7xl lg:text-[7rem] xl:text-[8rem] font-black tracking-tighter md:leading-[1.0] uppercase mb-4 md:mb-10 text-gradient flex flex-col items-center md:items-start space-y-0 leading-tight">
                    <span data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">Rawat</span>
                    <span data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300" class="text-premium-blue italic whitespace-nowrap pr-0 md:pr-10 leading-none py-1 md:py-0">Tanpa Harus</span>
                    <span data-aos="fade-up" data-aos-duration="1000" data-aos-delay="500" class="text-premium-blue italic pr-0 md:pr-10 leading-tight">Antri.</span>
                </h1>
                
                <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="700" class="hidden lg:flex flex-col sm:flex-row gap-4 md:gap-6 mt-6 md:mt-10 justify-center md:justify-start">
                    <a href="{{ route('reservasi.create') }}" class="group w-full sm:w-auto bg-slate-900 text-white px-10 md:px-12 py-5 md:py-6 lg:py-7 rounded-full font-black uppercase text-[10px] md:text-xs tracking-[0.25em] flex items-center justify-center gap-4 hover:bg-blue-600 transition-all duration-500 glow-shadow hover:scale-105 shadow-xl shadow-slate-900/10">
                        Buat Reservasi 
                        <svg class="w-6 h-6 md:w-7 md:h-7 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="lg:col-span-5 relative flex flex-col items-center justify-center md:justify-end w-full flex-grow mt-2 md:mt-0gap-4 md:gap-0" data-aos="zoom-in-left" data-aos-duration="1500" data-aos-delay="400">
                <div class="relative w-[70%] max-w-[280px] sm:max-w-[360px] md:max-w-md lg:max-w-[500px] xl:max-w-[600px] aspect-square md:aspect-[4/5] max-h-[40vh] md:max-h-none rounded-[2rem] md:rounded-[4rem] overflow-hidden glass-card luxury-shadow animate-float border-8 md:border-[10px] border-white mx-auto lg:mx-0 shadow-2xl">
                    <img src="{{ $heroPath }}" alt="Sepatu Premium" class="absolute inset-0 w-full h-full object-cover scale-105 hover:scale-110 transition-transform duration-[2s]">
                    
                    <div class="absolute bottom-3 left-3 right-3 md:bottom-10 md:left-10 md:right-10 bg-white/85 backdrop-blur-2xl p-4 md:p-10 rounded-2xl md:rounded-[2.5rem] border border-white shadow-2xl transition-transform hover:-translate-y-2 duration-500">
                        <div class="flex items-center gap-2 md:gap-3.5 mb-1.5 md:mb-2.5">
                            <div class="w-1.5 h-1.5 md:w-2.5 md:h-2.5 rounded-full bg-cyan-500 animate-pulse glow-shadow-light"></div>
                            <p class="text-[7px] md:text-[10px] font-black uppercase tracking-[0.25em] text-slate-500">Standar Kualitas</p>
                        </div>
                        <p class="text-sm md:text-3xl lg:text-4xl font-black text-slate-900 uppercase tracking-tighter leading-none leading-tight">Pembersihan <br><span class="text-premium-blue italic">Mendalam.</span></p>
                    </div>
                </div>

                <div class="flex lg:hidden w-full max-w-[320px] sm:max-w-[360px] md:max-w-md mt-auto shrink-0 pt-4" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="600">
                    <a href="{{ route('reservasi.create') }}" class="group w-full bg-slate-900 text-white px-10 md:px-12 py-5 md:py-6 rounded-full font-black uppercase text-[10px] md:text-xs tracking-[0.25em] flex items-center justify-center gap-4 hover:bg-blue-600 transition-all duration-500 glow-shadow hover:scale-105 shadow-2xl shadow-slate-900/10 tracking-widest">
                        Buat Reservasi 
                        <svg class="w-6 h-6 md:w-7 md:h-7 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
            
        </div>
    </header>

    <section id="alur" class="w-full min-h-[100svh] flex flex-col justify-center px-0 md:px-8 lg:px-16 xl:px-24 pt-24 pb-12 bg-transparent scroll-mt-0 relative overflow-hidden border-t-2 border-slate-100/50">
        <div class="w-full mx-auto relative z-10 flex flex-col justify-center h-full">
            <div class="mb-12 lg:mb-16 text-center md:text-left px-6 md:px-0" data-aos="fade-right" data-aos-duration="1000">
                <span class="text-blue-600 font-black uppercase tracking-[0.35em] text-[10px] md:text-xs mb-3 md:mb-5 block">Panduan Reservasi</span>
                <h2 class="font-black text-4xl sm:text-5xl md:text-7xl lg:text-8xlUppercase uppercase tracking-tighter text-slate-900 leading-[0.85]">
                    Langkah <span class="text-premium-blue italic">Mudah.</span>
                </h2>
            </div>
            
            <div class="flex md:grid md:grid-cols-12 gap-6 md:gap-8 lg:gap-12 overflow-x-auto snap-x snap-mandatory no-scrollbar pb-8 px-6 md:pb-0 md:px-0">
                
                <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100" class="w-[85vw] sm:w-[340px] md:w-auto shrink-0 snap-center md:col-span-4 glass-card rounded-[3rem] p-8 md:p-10 lg:p-12 hover-lift relative overflow-hidden flex flex-col justify-center luxury-shadow border-2 border-white/70">
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-blue-100 to-blue-50 text-blue-600 rounded-2xl md:rounded-3xl flex items-center justify-center mb-6 md:mb-8 text-xl md:text-2xl font-black shadow-inner border border-blue-100">1</div>
                    <h5 class="font-black text-2xl md:text-3xl uppercase tracking-tight mb-3 md:mb-5 text-slate-900">Registrasi</h5>
                    <p class="text-slate-500 font-medium text-sm md:text-base lg:text-lg leading-relaxed">
                        Daftarkan akun pelanggan Anda dengan aman dan cepat untuk mendapatkan akses ke sistem kami.
                    </p>
                </div>
                
                <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300" class="w-[85vw] sm:w-[340px] md:w-auto shrink-0 snap-center md:col-span-8 bg-gradient-to-br from-slate-900 to-slate-800 backdrop-blur-md rounded-[3rem] p-8 md:p-10 lg:p-12 border border-slate-700/60 hover-lift relative overflow-hidden text-white group flex flex-col justify-center luxury-shadow glow-shadow">
                    <div class="absolute inset-0 bg-gradient-to-r from-cyan-600 to-purple-600 opacity-0 group-hover:opacity-20 transition-opacity duration-500"></div>
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-white/10 text-cyan-400 border border-cyan-500/30 rounded-2xl md:rounded-3xl flex items-center justify-center mb-6 md:mb-8 text-xl md:text-2xl font-black relative z-10 shadow-xl backdrop-blur">2</div>
                    <h5 class="font-black text-3xl md:text-4xl uppercase tracking-tight mb-3 md:mb-5 text-white relative z-10">Pilih Layanan</h5>
                    <p class="text-slate-300 font-medium text-base md:text-lg lg:text-xl leading-relaxed max-w-2xl relative z-10">
                        Pilih jenis pencucian yang sesuai dengan material sepatu Anda. Transparansi harga tercatat jelas di dalam sistem.
                    </p>
                </div>
                
                <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="w-[85vw] sm:w-[340px] md:w-auto shrink-0 snap-center md:col-span-6 glass-card rounded-[3rem] p-8 md:p-10 lg:p-12 hover-lift relative overflow-hidden flex flex-col justify-center luxury-shadow border-2 border-white/70">
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-blue-100 to-blue-50 text-blue-600 rounded-2xl md:rounded-3xl flex items-center justify-center mb-6 md:mb-8 text-xl md:text-2xl font-black shadow-inner border border-blue-100">3</div>
                    <h5 class="font-black text-2xl md:text-3xl uppercase tracking-tight mb-3 md:mb-4 text-slate-900">Penyerahan</h5>
                    <p class="text-slate-500 font-medium text-sm md:text-base lg:text-lg leading-relaxed">
                        Kirim sepatu ke toko kami atau gunakan layanan logistik. Data Anda akan langsung masuk ke dalam antrean pengerjaan.
                    </p>
                </div>
                
                <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400" class="w-[85vw] sm:w-[340px] md:w-auto shrink-0 snap-center md:col-span-6 bg-gradient-to-tr from-blue-50 to-cyan-50 backdrop-blur-md border-2 border-white rounded-[3rem] p-8 md:p-10 lg:p-12 hover-lift relative overflow-hidden flex flex-col justify-center luxury-shadow shadow-xl shadow-cyan-500/5">
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-white text-blue-600 shadow-xl rounded-2xl md:rounded-3xl flex items-center justify-center mb-6 md:mb-8 text-xl md:text-2xl font-black border border-slate-100">4</div>
                    <h5 class="font-black text-2xl md:text-3xl uppercase tracking-tight mb-3 md:mb-5 text-blue-800">Pantau Status</h5>
                    <p class="text-blue-700 font-medium text-sm md:text-base lg:text-lg leading-relaxed">
                        Pantau setiap tahapan pengerjaan sepatu Anda secara real-time langsung melalui dasbor pelanggan.
                    </p>
                </div>
            </div>

            <div class="flex md:hidden justify-center items-center gap-2.5 mt-5 text-slate-400">
                <i class="fa-solid fa-arrow-left-long text-[10px] animate-pulse"></i>
                <span class="text-[10px] font-black uppercase tracking-[0.3em]">Geser</span>
                <i class="fa-solid fa-arrow-right-long text-[10px] animate-pulse"></i>
            </div>
            
        </div>
    </section>

    <section id="tentang" class="w-full min-h-[100svh] flex flex-col px-6 md:px-8 lg:px-16 xl:px-24 pt-28 pb-10 bg-transparent scroll-mt-0 relative overflow-hidden border-t-2 border-slate-100/50">
        <div class="w-full h-full flex flex-col mx-auto relative z-10 flex-grow justify-center lg:justify-start">
            <div class="flex flex-col lg:grid lg:grid-cols-2 gap-8 md:gap-12 lg:gap-16 xl:gap-24 items-center flex-grow w-full justify-between lg:justify-center">
                
                <div class="text-center md:text-left flex flex-col items-center md:items-start w-full mt-6 md:mt-0" data-aos="fade-right" data-aos-duration="1200">
                    <span class="text-blue-600 font-black uppercase tracking-[0.35em] text-[10px] md:text-xs mb-2 md:mb-5 block">Tentang Kami</span>
                    <h2 class="font-black text-4xl leading-tight sm:text-6xl lg:text-7xl xl:text-8xluppercase uppercase tracking-tighter text-slate-900 md:leading-[1.0] mb-6 md:mb-10">
                        Perawatan Premium. <br class="hidden sm:block"> <span class="text-premium-blue italic">Jaminan Kualitas.</span>
                    </h2>
                    
                    <div class="space-y-3 md:space-y-8 mx-auto md:mx-0 w-full px-2 md:px-0">
                        <p class="text-slate-600 font-medium text-sm sm:text-base md:text-lg lg:text-xl leading-relaxed">Berdiri sejak 2025 di Kabupaten Madiun, <strong class="text-slate-900">ROFF.SHOECLEAN</strong> hadir dari dedikasi tinggi terhadap standar perawatan sepatu premium.</p>
                        <p class="text-slate-600 font-medium text-sm sm:text-base md:text-lg lg:text-xl leading-relaxed">Kami secara eksklusif menggunakan formulasi pembersih premium yang terbukti aman, mengembalikan kondisi terbaik material sepatu kesayangan Anda tanpa merusak tekstur aslinya.</p>
                    </div>
                    
                    <div class="mt-6 md:mt-16 pt-6 md:pt-10 border-t-2 border-slate-100 hidden md:flex items-center justify-start w-full" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
                        <div class="flex items-center gap-4 md:gap-6 p-4 md:p-5 glass-card rounded-2xl md:rounded-3xl w-full md:w-auto hover-lift border-white shadow-xl shadow-blue-500/5 border-2 border-white/80">
                            <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-tr from-cyan-400 via-blue-600 to-purple-600 rounded-xl md:rounded-3xl flex items-center justify-center text-white shadow-lg shadow-blue-500/40 shrink-0 border-2 border-white">
                                <i class="fa-solid fa-user-tie text-xl md:text-3xl"></i>
                            </div>
                            <div class="flex flex-col text-left pr-4 md:pr-16">
                                <p class="font-black text-slate-900 text-lg md:text-2xl leading-none mb-1.5 md:mb-2 tracking-tighter">Rofi'i Alla Yusuffa</p>
                                <p class="text-[9px] md:text-[11px] font-black tracking-[0.25em] text-blue-600 uppercase">Owner</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="relative flex flex-col items-center justify-between lg:justify-end w-full flex-grow gap-6 md:gap-0" data-aos="fade-left" data-aos-duration="1500">
                    
                    <div class="relative w-[60%] max-w-[240px] sm:max-w-[360px] md:w-full md:max-w-[440px] xl:max-w-[500px] aspect-square rounded-[2.5rem] md:rounded-[4rem] overflow-hidden luxury-shadow border-8 md:border-[10px] border-white glass-card flex items-center justify-center p-2 md:p-3 group mx-auto lg:mx-0 my-auto shadow-2xl">
                        {{-- ✅ MENGGUNAKAN VARIABEL GAMBAR DARI DATABASE --}}
                        <img src="{{ $tentangPath }}" alt="Tentang Roff" class="w-full h-full object-cover rounded-[2rem] md:rounded-[3rem] group-hover:scale-105 transition-transform duration-700">
                    </div>

                    <div class="p-5 sm:p-6 glass-card rounded-[2rem] flex items-center gap-4 sm:gap-6 border-2 border-white w-full md:hidden bg-white/75 backdrop-blur-2xl mt-auto shrink-0 shadow-2xl shadow-blue-500/5 glow-shadow-light" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-cyan-500 via-blue-600 to-purple-600 flex items-center justify-center text-white shrink-0 shadow-lg border-2 border-white">
                            <i class="fa-solid fa-user-tie text-lg"></i>
                        </div>
                        <div class="flex flex-col flex-grow text-left">
                            <p class="font-black text-slate-900 text-sm sm:text-base mb-1leading-none tracking-tight">Rofi'i Alla Yusuffa</p>
                            <p class="text-[8px] sm:text-[9px] font-black tracking-[0.25em] text-blue-600 uppercase mt-0.5">Owner & Founder</p>
                        </div>
                        <div class="w-px h-10 bg-slate-300/80 shrink-0 mx-1"></div>
                        <div class="flex flex-col items-center justify-center shrink-0 min-w-[3.5rem] px-1">
                            <span class="font-black text-slate-900 text-lg sm:text-2xl leading-none tracking-tight">5<span class="text-cyan-500 font-black">+</span></span>
                            <span class="text-[8px] font-black text-cyan-500 uppercase tracking-[0.3em] text-center leading-tight mt-1">Tahun<br>Exp</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section id="layanan" class="w-full min-h-[100svh] flex flex-col justify-center px-0 md:px-8 lg:px-16 xl:px-24 pt-24 pb-12 bg-transparent scroll-mt-0 relative border-t-2 border-slate-100/50 overflow-hidden">
        <div class="w-full mx-auto relative z-10 flex flex-col justify-center h-full">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 md:mb-16 gap-5 px-6 md:px-0">
                <div class="text-center md:text-left w-full" data-aos="fade-right" data-aos-duration="1000">
                    <span class="text-blue-600 font-black uppercase tracking-[0.35em] text-[10px] md:text-xs mb-3 block">Katalog Layanan</span>
                    <h2 class="font-black text-4xl sm:text-5xl md:text-7xl uppercase tracking-tighter text-slate-900 leading-[0.85]">
                        Perawatan <br> <span class="text-premium-blue italic">Terbaik.</span>
                    </h2>
                </div>
                <div class="md:text-right w-full md:max-w-2xl border-r-4 border-blue-600 pr-6 py-1 text-left hidden md:block" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                    <p class="text-slate-600 font-medium text-sm md:text-base lg:text-lg leading-relaxed">
                        Kami menggunakan cairan pembersih premium yang terbukti aman untuk merawat segala jenis material sepatu kesayangan Anda.
                    </p>
                </div>
            </div>

            <div class="flex md:grid md:grid-cols-3 gap-6 md:gap-8 lg:gap-12 overflow-x-auto snap-x snap-mandatory no-scrollbar pb-10 px-6 md:pb-0 md:px-0">
                @foreach($layanans as $index => $layanan)
                    @php
                        // Logika Fallback Gambar (Jika di DB kosong)
                        $fallback_image = 'default.png'; 
                        $nama_lower = strtolower($layanan->nama_layanan);
                        if(str_contains($nama_lower, 'fast')) { $fallback_image = 'fastclean.png'; } 
                        elseif(str_contains($nama_lower, 'deep')) { $fallback_image = 'deepclean.png'; } 
                        elseif(str_contains($nama_lower, 'unyellowing')) { $fallback_image = 'unyellowing.png'; }
                        
                        $harga_k = ($layanan->harga / 1000) . 'k';
                    @endphp

                    <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="{{ $index * 200 }}" class="w-[85vw] sm:w-[340px] md:w-auto shrink-0 snap-center glass-card p-8 md:p-10 rounded-[3rem] hover-lift flex flex-col luxury-shadow relative overflow-hidden group border-2 border-white shadow-xl shadow-blue-500/5">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-cyan-400 to-purple-500 opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                        
                        <div class="w-full h-48 lg:h-56 xl:h-64 rounded-[2rem] overflow-hidden mb-8 mt-1 relative bg-white border border-slate-100/50 shadow-inner">
                            <img src="{{ $layanan->gambar ? asset('storage/' . $layanan->gambar) : asset('images/' . $fallback_image) }}" 
                                 alt="{{ $layanan->nama_layanan }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        </div>
                        
                        <div class="px-1 flex flex-col flex-grow">
                            <h4 class="font-black uppercase text-2xl lg:text-3xl mb-4 text-slate-900 leading-none group-hover:text-blue-600 transition-colors tracking-tight">{{ $layanan->nama_layanan }}</h4>
                            <p class="text-slate-600 font-medium text-[12px] md:text-sm leading-relaxed mb-8 flex-grow line-clamp-2">
                                {{ $layanan->deskripsi }}
                            </p>
                            
                            <div class="pt-8 border-t border-slate-200/50 mt-auto">
                                <div class="flex items-baseline gap-2 mb-8 justify-center">
                                    <span class="text-xl font-bold text-slate-400">Rp</span>
                                    <span class="text-5xl lg:text-6xl font-black text-slate-900 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-blue-600 group-hover:to-cyan-500 transition-all tracking-tighter">{{ $harga_k }}</span>
                                </div>
                                <a href="{{ route('reservasi.create') }}" class="w-full flex items-center justify-center gap-4 bg-slate-900 text-white py-5 lg:py-6 rounded-2xl font-black uppercase text-[10px] xl:text-xs tracking-[0.25em] hover:bg-blue-600 transition-all duration-300 shadow-xl shadow-slate-900/10 hover:shadow-blue-500/40 tracking-widest leading-none">
                                    Pilih Layanan
                                    <svg class="w-5 h-5 transform group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="flex md:hidden justify-center items-center gap-2.5 mt-5 text-slate-400">
                <i class="fa-solid fa-arrow-left-long text-[10px] animate-pulse"></i>
                <span class="text-[10px] font-black uppercase tracking-[0.3em]">Geser</span>
                <i class="fa-solid fa-arrow-right-long text-[10px] animate-pulse"></i>
            </div>
            
        </div>
    </section>

    <section id="lokasi" class="relative w-full min-h-[100svh] flex items-center px-6 md:px-8 lg:px-16 xl:px-24 pt-24 pb-12 bg-transparent overflow-hidden scroll-mt-0 border-t-2 border-slate-100/50 outline-none">
        <div class="w-full mx-auto relative z-10">
            <div class="mb-12 lg:mb-16 text-center md:text-left" data-aos="fade-right" data-aos-duration="1000">
                <span class="text-blue-600 font-black uppercase tracking-[0.35em] text-[10px] md:text-xs mb-3 md:mb-4 block">Kunjungi Workshop</span>
                <h2 class="font-black text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xlUppercase uppercase tracking-tighter text-slate-900 leading-[0.85]">
                    Lokasi <span class="text-premium-blue italic">Kami.</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 glass-card rounded-[2.5rem] md:rounded-[4rem] overflow-hidden luxury-shadow h-auto lg:h-[55vh] lg:max-h-[580px] border-2 border-white/80 shadow-2xl" data-aos="fade-up" data-aos-duration="1200">
                <div class="lg:col-span-4 bg-gradient-to-b from-slate-900 to-slate-800 backdrop-blur-md p-8 md:p-10 lg:p-14 flex flex-col justify-between relative overflow-hidden border-b lg:border-b-0 lg:border-r-2 border-slate-700/60 shadow-xl">
                    <div class="absolute top-0 right-0 w-80 h-80 bg-gradient-to-br from-cyan-600/10 to-purple-600/5 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/4"></div>
                    
                    <div class="space-y-10 lg:space-y-14 relative z-10">
                        <div class="flex gap-5 md:gap-6 group">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-cyan-400 via-blue-600 to-purple-600 flex items-center justify-center text-white shrink-0 shadow-lg group-hover:scale-105 transition-transform border-2 border-white/80">
                                <i class="fa-solid fa-location-dot text-2xl"></i>
                            </div>
                            <div class="flex flex-col justify-center">
                                <h4 class="text-cyan-400 font-black uppercase tracking-[0.25em] text-[10px] mb-2 md:mb-2.5">Alamat Workshop</h4>
                                <p class="text-white font-medium text-base md:text-lg leading-snug tracking-tight">
                                    Ds. Purworejo Rt. 05 Rw. 01<br>
                                    <span class="text-slate-400 text-sm md:text-base">Kec. Geger, Kab. Madiun</span>
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-5 md:gap-6 group">
                            <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center text-cyan-400 shrink-0 border-2 border-slate-700/60 group-hover:border-cyan-400/50 transition-colors shadow-inner backdrop-blur-sm">
                                <i class="fa-solid fa-clock text-2xl"></i>
                            </div>
                            <div class="flex flex-col justify-center">
                                <h4 class="text-slate-500 font-black uppercase tracking-[0.25em] text-[10px] mb-2 md:mb-2.5">Jam Operasional</h4>
                                <p class="text-white font-medium text-base md:text-lg leading-snug uppercase tracking-tight">
                                    09:00 — 21:00 WIB<br>
                                    <span class="text-slate-500 text-xs md:text-sm tracking-[0.25em]">Senin - Minggu</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-10 relative z-10">
                        <a href="https://wa.me/6282231259408" target="_blank" class="w-full py-5 md:py-6 bg-gradient-to-r from-blue-600 to-blue-50 hover:from-cyan-500 hover:to-purple-500 text-white rounded-2xl font-black uppercase text-[11px] md:text-xs tracking-[0.25em] flex items-center justify-center gap-4 transition-all duration-300 shadow-xl shadow-blue-500/20 group hover:scale-[1.02] tracking-widest leading-none">
                            <i class="fa-brands fa-whatsapp text-2xl"></i>
                            Hubungi CS
                            <i class="fa-solid fa-arrow-right text-sm group-hover:translate-x-2 transition-transform"></i>
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-8 bg-slate-100 relative min-h-[280px] sm:min-h-[340px] md:min-h-[440px] lg:min-h-full">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3954.264700874315!2d111.516142675883!3d-7.654637775736637!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e79bf3544520935%3A0x673c6a46a6f685c!2sROFF.SHOECLEAN!5e0!3m2!1sid!2sid!4v1709663784534!5m2!1sid!2sid" 
                        class="absolute inset-0 w-full h-full border-0 grayscale hover:grayscale-0 transition-all duration-1000 opacity-90 hover:opacity-100" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                    
                    <div class="absolute top-5 right-5 md:top-8 md:right-8">
                        <a href="https://maps.app.goo.gl/uXpX45G27bW74h8w9" target="_blank" class="bg-white/95 backdrop-blur-md px-6 py-3 md:px-8 md:py-4 rounded-full font-black uppercase text-[10px] md:text-[11px] tracking-[0.2em] text-slate-900 shadow-2xl border-2 border-white hover:bg-slate-950 hover:text-white transition-all duration-300 hover:scale-105 flex items-center gap-3">
                            Buka di Maps <i class="fa-solid fa-external-link"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-[#0b1121] text-white w-full relative z-20 border-t-2 border-slate-800/50 overflow-hidden min-h-[calc(100svh-72px)] md:min-h-0 flex flex-col mt-auto">
        <div class="absolute top-0 right-0 w-[40rem] h-[40rem] bg-gradient-to-br from-blue-600/10 to-purple-600/5 rounded-full blur-[140px] -translate-y-1/2 translate-x-1/2 opacity-60"></div>
        <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-gradient-to-br from-cyan-600/10 to-blue-600/5 rounded-full blur-[120px] translate-y-1/2 -translate-x-1/2 opacity-50"></div>

        <div class="w-full px-5 md:px-12 lg:px-20 mx-auto relative z-10 pt-10 md:pt-20 pb-10 md:pb-16 flex-grow flex flex-col justify-evenly">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-y-10 gap-x-6 md:gap-12 lg:gap-16 w-full my-auto">
                
                <div class="col-span-2 lg:col-span-1 space-y-4 md:space-y-8 w-full">
                    <a href="#" class="inline-block">
                        <h2 class="text-3xl lg:text-4xl font-black tracking-tighter uppercase leading-none">
                            ROFF.<span class="text-blue-500">SHOECLEAN</span>
                        </h2>
                    </a>
                    <p class="text-slate-400 text-[11px] md:text-[13px] leading-relaxed w-full max-w-sm">
                        Solusi perawatan sepatu premium terbaik dengan standar kualitas tinggi. Kami mengembalikan kepercayaan diri Anda melalui sepatu yang bersih dan terawat sempurna.
                    </p>
                </div>

                <div class="col-span-1 space-y-4 md:space-y-8">
                    <h4 class="text-[10px] md:text-[11px] font-black uppercase tracking-[0.25em] text-white/50">Menu Cepat</h4>
                    <ul class="space-y-3 md:space-y-5">
                        <li><a href="#beranda" class="text-slate-400 hover:text-cyan-400 text-[11px] md:text-[13px] font-bold transition-all flex items-center gap-2.5 group"><i class="fa-solid fa-angle-right text-[10px] group-hover:translate-x-1 transition-transform"></i> Beranda</a></li>
                        <li><a href="#alur" class="text-slate-400 hover:text-cyan-400 text-[11px] md:text-[13px] font-bold transition-all flex items-center gap-2.5 group"><i class="fa-solid fa-angle-right text-[10px] group-hover:translate-x-1 transition-transform"></i> Panduan</a></li>
                        <li><a href="#tentang" class="text-slate-400 hover:text-cyan-400 text-[11px] md:text-[13px] font-bold transition-all flex items-center gap-2.5 group"><i class="fa-solid fa-angle-right text-[10px] group-hover:translate-x-1 transition-transform"></i> Tentang Kami</a></li>
                        <li><a href="#layanan" class="text-slate-400 hover:text-cyan-400 text-[11px] md:text-[13px] font-bold transition-all flex items-center gap-2.5 group"><i class="fa-solid fa-angle-right text-[10px] group-hover:translate-x-1 transition-transform"></i> Layanan</a></li>
                        <li><a href="#lokasi" class="text-slate-400 hover:text-cyan-400 text-[11px] md:text-[13px] font-bold transition-all flex items-center gap-2.5 group"><i class="fa-solid fa-angle-right text-[10px] group-hover:translate-x-1 transition-transform"></i> Lokasi</a></li>
                    </ul>
                </div>

                <div class="col-span-1 space-y-4 md:space-y-8">
                    <h4 class="text-[10px] md:text-[11px] font-black uppercase tracking-[0.25em] text-white/50">Ikuti Kami</h4>
                    <div class="flex gap-3 md:gap-5">
                        <a href="https://instagram.com/roff.shoeclean" target="_blank" class="w-10 h-10 md:w-14 md:h-14 rounded-2xl bg-white/5 border-2 border-white/10 flex items-center justify-center text-lg md:text-2xl hover:bg-gradient-to-tr hover:from-purple-600 hover:to-pink-500 hover:border-transparent transition-all duration-500 hover:-translate-y-1.5 shadow-xl shadow-black/20">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a href="https://wa.me/6282231259408" target="_blank" class="w-10 h-10 md:w-14 md:h-14 rounded-2xl bg-white/5 border-2 border-white/10 flex items-center justify-center text-lg md:text-2xl hover:bg-emerald-500 hover:border-transparent transition-all duration-500 hover:-translate-y-1.5 shadow-xl shadow-black/20">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                        <a href="mailto:admin@roffshoeclean.com" class="w-10 h-10 md:w-14 md:h-14 rounded-2xl bg-white/5 border-2 border-white/10 flex items-center justify-center text-lg md:text-2xl hover:bg-blue-600 hover:border-transparent transition-all duration-500 hover:-translate-y-1.5 shadow-xl shadow-black/20">
                            <i class="fa-solid fa-envelope"></i>
                        </a>
                    </div>
                </div>

                <div class="col-span-2 lg:col-span-1 space-y-4 md:space-y-8 pt-4 md:pt-0 w-full border-t lg:border-t-0 border-slate-800/50">
                    <h4 class="text-[10px] md:text-[11px] font-black uppercase tracking-[0.25em] text-white/50 pt-6 lg:pt-0">Hubungi Kami</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-3 md:gap-6 w-full">
                        <div class="flex items-start gap-3.5 w-full">
                            <i class="fa-solid fa-location-dot text-cyan-400 mt-1 text-sm md:text-lg"></i>
                            <p class="text-slate-400 text-[11px] md:text-[13px] font-medium leading-relaxed">Ds. Purworejo Rt.05 Rw.01, Kec. Geger, Madiun</p>
                        </div>
                        <div class="flex items-center gap-3.5 w-full">
                            <i class="fa-solid fa-phone text-cyan-400 text-sm md:text-lg"></i>
                            <p class="text-slate-400 text-[11px] md:text-[13px] font-medium">+62 822-3125-9408</p>
                        </div>
                        <div class="flex items-center gap-3.5 w-full sm:col-span-2 lg:col-span-1">
                            <i class="fa-solid fa-clock text-cyan-400 text-sm md:text-lg"></i>
                            <p class="text-slate-400 text-[11px] md:text-[13px] font-medium">Setiap Hari: 09:00 - 21:00 WIB</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full bg-white/[0.02] border-t-2 border-white/10 mt-auto shrink-0 backdrop-blur-sm">
            <div class="w-full px-5 md:px-12 lg:px-20 mx-auto relative z-10 py-6 md:py-8">
                <div class="flex flex-row justify-between items-center gap-3">
                    <p class="text-slate-400 text-[9px] md:text-[11px] font-black uppercase tracking-[0.25em] md:tracking-[0.35em] leading-none">
                        © 2026 <span class="text-white/90">ROFF.SHOECLEAN</span> <span class="hidden md:inline">— ALL RIGHTS RESERVED.</span>
                    </p>
                    <div class="flex items-center gap-3 md:gap-4 bg-white/5 px-4 md:px-6 py-2 md:py-3.5 rounded-full border-2 border-white/10 shadow-inner">
                        <div class="relative flex h-2 w-2 md:h-2.5 md:w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 md:h-2.5 md:w-2.5 bg-emerald-500 glow-shadow-light"></span>
                        </div>
                        <span class="text-[8px] md:text-[10px] font-black uppercase tracking-[0.3em] text-slate-300 leading-none">Status: Online</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            AOS.init({
                once: true,
                offset: 80,
                easing: 'ease-out-cubic',
            });

            const sections = document.querySelectorAll("header[id], section[id]");
            const navItems = document.querySelectorAll(".nav-item");

            window.addEventListener("scroll", () => {
                let current = "";

                sections.forEach((section) => {
                    const sectionTop = section.offsetTop;
                    if (scrollY >= (sectionTop - 300)) { 
                        current = section.getAttribute("id");
                    }
                });

                navItems.forEach((item) => {
                    item.classList.remove("text-slate-900");
                    item.classList.add("text-slate-400");
                    
                    if (item.getAttribute("href") === "#" + current) {
                        item.classList.remove("text-slate-400");
                        item.classList.add("text-slate-900");
                    }
                });
            });
        });
    </script>
</body>
</html>