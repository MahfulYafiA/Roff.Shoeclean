<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor Pelanggan - ROFF.SHOECLEAN</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
        
        /* --- BACKGROUND PREMIUM GLOBAL --- */
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

        /* --- GLASSMORPHISM --- */
        .glass-nav { 
            background: rgba(255, 255, 255, 0.65); 
            backdrop-filter: blur(28px); 
            -webkit-backdrop-filter: blur(28px); 
            border-bottom: 1px solid rgba(255, 255, 255, 0.5); 
        }
        
        .glass-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.5));
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 16px 48px -12px rgba(31, 38, 135, 0.08);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .glass-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 32px 64px -16px rgba(37, 99, 235, 0.15);
            border-color: rgba(37, 99, 235, 0.3);
        }

        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        /* SweetAlert Light Theme */
        .swal2-popup { border-radius: 2.5rem !important; padding: 2rem !important; font-family: 'Plus Jakarta Sans', sans-serif; }
        .swal2-styled.swal2-confirm { background-color: #2563eb !important; border-radius: 1rem !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; padding: 1rem 2rem !important; }
    </style>
</head>
<body class="antialiased flex h-screen overflow-hidden relative selection:bg-blue-600 selection:text-white">

    {{-- BACKGROUND ELEMENTS --}}
    <div class="noise"></div>
    <div class="bg-mesh"></div>
    <div class="orb-1"></div>
    <div class="orb-2"></div>
    <div class="orb-3"></div>

    <main class="flex-1 flex flex-col h-screen min-w-0 relative z-10">
        
        {{-- TOP NAVIGATION --}}
        <header class="glass-nav px-6 md:px-12 py-4 flex justify-between items-center shrink-0 z-40 relative">
            
            {{-- LOGO & TOMBOL HOME KIRI --}}
            <div class="flex items-center gap-3 md:gap-4">
                <a href="{{ url('/') }}" class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all flex items-center justify-center shadow-sm group active:scale-95" title="Kembali ke Beranda Utama">
                    <i class="fa-solid fa-house text-sm group-hover:scale-110 transition-transform"></i>
                </a>
                
                <h1 class="block font-black text-xl md:text-2xl uppercase tracking-tighter italic text-slate-900 leading-tight">
                    ROFF.<span class="text-blue-600">MEMBER</span>
                </h1>
            </div>
            
            {{-- PROFIL KANAN ATAS --}}
            <div class="flex items-center gap-5">
                <div class="flex items-center bg-white/80 border border-slate-200 p-1 pr-4 rounded-full shadow-sm hover:shadow-md transition-all">
                    <div class="w-8 h-8 rounded-full overflow-hidden bg-gradient-to-tr from-blue-600 to-cyan-500 flex items-center justify-center text-[10px] font-black text-white border border-white/80 shadow-sm">
                        @if(auth()->user()->foto_profil)
                            <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->nama, 0, 2)) }}
                        @endif
                    </div>
                    <div class="ml-3 hidden sm:block">
                        <p class="text-[10px] font-black text-slate-900 uppercase tracking-widest leading-none truncate max-w-[90px]">{{ explode(' ', auth()->user()->nama)[0] }}</p>
                        <p class="text-[7px] font-bold text-blue-500 uppercase mt-0.5 tracking-widest">VIP Access</p>
                    </div>
                    <div class="w-px h-5 bg-slate-300 mx-4 hidden sm:block"></div>
                    <form method="POST" action="{{ route('logout') }}" class="m-0 flex items-center">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-600 hover:scale-110 transition-all flex items-center justify-center ml-2 sm:ml-0" title="Keluar">
                            <i class="fa-solid fa-power-off text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- CONTENT AREA --}}
        <div class="flex-1 flex flex-col justify-start lg:justify-center p-5 md:p-8 lg:p-12 overflow-y-auto lg:overflow-hidden custom-scroll z-10 relative">

            {{-- HERO SECTION --}}
            <div class="relative rounded-[2rem] md:rounded-[3rem] p-8 md:p-10 lg:p-14 overflow-hidden mb-6 md:mb-10 shadow-2xl bg-slate-900 group shrink-0">
                <div class="absolute top-0 right-0 w-64 h-64 md:w-96 md:h-96 bg-blue-600/30 rounded-full blur-[80px] md:blur-[120px] pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-cyan-500/20 blur-[60px] rounded-full translate-y-1/2 -translate-x-1/4 pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 bg-blue-500/20 border border-blue-400/30 px-4 py-1.5 rounded-full mb-4 md:mb-6">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse shadow-[0_0_10px_rgba(6,182,212,1)]"></span>
                        <p class="text-[8px] md:text-[9px] font-black text-cyan-100 uppercase tracking-[0.4em]">Member Lounge</p>
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white tracking-tighter mb-4 md:mb-6 leading-none">
                        Selamat Datang, <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-400 italic">{{ auth()->user()->nama }}!</span>
                    </h1>
                    <p class="text-slate-300 font-medium text-sm md:text-base max-w-3xl leading-relaxed mb-0">
                        Pusat layanan personal Anda. Ciptakan reservasi baru, lacak status perawatan sepatu kesayangan Anda, dan atur preferensi akun dari satu tempat.
                    </p>
                </div>
            </div>

            {{-- GRID MENU (TEMA CUSTOMER - LIGHT GLASS) --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 md:gap-8 shrink-0">
                
                {{-- KOTAK 1: KELOLA PROFIL --}}
                <a href="{{ route('profil.index') }}" class="glass-card group p-6 lg:p-10 rounded-[2rem] flex flex-col justify-center items-start h-full relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                    <div class="w-14 h-14 lg:w-16 lg:h-16 bg-blue-50 text-blue-600 rounded-[1.2rem] flex items-center justify-center mb-6 border border-blue-100 shadow-inner group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-500 relative z-10">
                        <i class="fa-solid fa-id-card-clip text-2xl"></i>
                    </div>
                    <h3 class="font-black text-xl lg:text-2xl text-slate-900 mb-2 uppercase tracking-tighter leading-none group-hover:text-blue-600 transition-colors relative z-10">Kelola Profil</h3>
                    <p class="text-slate-500 text-[10px] lg:text-[11px] font-bold uppercase tracking-widest opacity-80 relative z-10">Update Data Diri & Alamat</p>
                </a>

                {{-- KOTAK 2: BUAT RESERVASI (IKON SUDAH DIPERBAIKI) --}}
                <a href="{{ route('reservasi.create') }}" class="glass-card group p-6 lg:p-10 rounded-[2rem] flex flex-col justify-center items-start h-full border-blue-200 relative overflow-hidden">
                    <div class="absolute inset-0 bg-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none z-0"></div>
                    <div class="w-14 h-14 lg:w-16 lg:h-16 bg-blue-600 text-white rounded-[1.2rem] flex items-center justify-center mb-6 shadow-lg shadow-blue-500/30 group-hover:scale-110 group-hover:bg-white group-hover:text-blue-600 transition-all duration-500 relative z-10">
                        {{-- Ikon Baru yang dijamin tidak hilang --}}
                        <i class="fa-solid fa-calendar-plus text-2xl"></i>
                    </div>
                    <h3 class="font-black text-xl lg:text-2xl text-slate-900 mb-2 uppercase tracking-tighter leading-none group-hover:text-white transition-colors relative z-10">Buat Reservasi</h3>
                    <p class="text-slate-500 text-[10px] lg:text-[11px] font-bold uppercase tracking-widest opacity-80 group-hover:text-blue-100 relative z-10">Pesan Layanan Sekarang</p>
                </a>

                {{-- KOTAK 3: SEMUA RIWAYAT --}}
                <a href="{{ route('reservasi.riwayat') }}" class="glass-card group p-6 lg:p-10 rounded-[2rem] flex flex-col justify-center items-start h-full relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                    <div class="w-14 h-14 lg:w-16 lg:h-16 bg-cyan-50 text-cyan-600 rounded-[1.2rem] flex items-center justify-center mb-6 border border-cyan-100 shadow-inner group-hover:scale-110 group-hover:bg-cyan-500 group-hover:text-white transition-all duration-500 relative z-10">
                        <i class="fa-solid fa-clock-rotate-left text-2xl"></i>
                    </div>
                    <h3 class="font-black text-xl lg:text-2xl text-slate-900 mb-2 uppercase tracking-tighter leading-none group-hover:text-cyan-600 transition-colors relative z-10">Semua Riwayat</h3>
                    <p class="text-slate-500 text-[10px] lg:text-[11px] font-bold uppercase tracking-widest opacity-80 relative z-10">Pantau Status & Tagihan</p>
                </a>

            </div>

            {{-- FOOTER --}}
            <div class="mt-auto pt-8 pb-2 flex justify-center items-center shrink-0 z-10">
                <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 text-center">© 2026 ROFF.MEMBER LOUNGE</p>
            </div>
        </div>
    </main>

    {{-- SCRIPT NOTIFIKASI MEWAH (SweetAlert2) --}}
    @if(session('success'))
    <script>
        let pesan = "{{ session('success') }}";
        let judul = "BERHASIL!"; 
        
        let textLower = pesan.toLowerCase();
        
        if(textLower.includes('reservasi') || textLower.includes('hore')) {
            judul = "RESERVASI BERHASIL!";
        } else if(textLower.includes('datang') || textLower.includes('daftar') || textLower.includes('registrasi')) {
            judul = "PENDAFTARAN BERHASIL!";
        } else if(textLower.includes('profil') || textLower.includes('update') || textLower.includes('disimpan')) {
            judul = "BERHASIL DISIMPAN!";
        }

        Swal.fire({
            title: judul,
            text: pesan,
            icon: 'success',
            confirmButtonText: 'OKE', // ✅ UBAH DISINI: Sudah jadi OKE
            buttonsStyling: true,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });
    </script>
    @endif

</body>
</html>