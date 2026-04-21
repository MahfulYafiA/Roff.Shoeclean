<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Profil - ROFF.SHOECLEAN</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }

        /* 🚨 THEME: LIGHT (Customer) 🚨 */
        body:not(.theme-dark) { background-color: #f8fafc; color: #0f172a; }
        body:not(.theme-dark) .bg-gradient-light {
            position: fixed; inset: 0; z-index: -3;
            background: radial-gradient(at 10% 20%, hsla(215, 98%, 95%, 1) 0px, transparent 50%),
                        radial-gradient(at 80% 0%, hsla(230, 96%, 96%, 1) 0px, transparent 50%);
        }
        body:not(.theme-dark) .glass-card { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.9); box-shadow: 0 24px 48px -12px rgba(15, 23, 42, 0.05); }

        /* 🚨 THEME: DARK (Superadmin & Admin) 🚨 */
        body.theme-dark { background-color: #0f172a; color: #f1f5f9; }
        body.theme-dark .bg-gradient-dark { position: fixed; inset: 0; z-index: -3; background: #0f172a; }
        body.theme-dark .bg-glow-accent {
            position: fixed; top: 0; right: 0; width: 600px; height: 600px; 
            filter: blur(120px); border-radius: 50%; z-index: -2; transform: translate(30%, -30%); pointer-events: none;
        }
        body.theme-dark .glass-card { background: rgba(30, 41, 59, 0.4); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.05); box-shadow: 0 24px 48px -12px rgba(0, 0, 0, 0.5); }
        
        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        body:not(.theme-dark) .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; }
    </style>
</head>

@php
    // ✅ LOGIKA DINAMIS
    $roleId = auth()->user()->id_role;
    $isSuper = $roleId == 1;
    $isAdmin = $roleId == 2;
    $isStaff = $isSuper || $isAdmin;
    
    // Warna Aksen
    $accent = $isSuper ? 'emerald' : 'blue';
    $glowColor = $isSuper ? 'rgba(16, 185, 129, 0.1)' : 'rgba(59, 130, 246, 0.15)';
@endphp

<body class="antialiased min-h-screen relative pb-12 flex flex-col custom-scroll {{ $isStaff ? 'theme-dark selection:text-white selection:bg-'.$accent.'-500' : 'selection:bg-blue-600 selection:text-white' }}">

    {{-- Layer Background Dinamis --}}
    @if($isStaff)
        <div class="bg-gradient-dark"></div>
        <div class="bg-glow-accent" style="background: {{ $glowColor }};"></div>
    @else
        <div class="bg-gradient-light"></div>
        <div class="bg-dots" style="position: fixed; inset: 0; z-index: -2; background-image: radial-gradient(#94a3b8 1px, transparent 1px); background-size: 24px 24px; opacity: 0.3;"></div>
    @endif

    {{-- NAVBAR DINAMIS --}}
    @if($isStaff)
        <header class="w-full bg-[#0f172a]/40 backdrop-blur-xl border-b border-white/5 sticky top-0 z-50">
            <div class="w-full px-6 md:px-12 py-4 flex justify-between items-center">
                <div class="flex items-center gap-3 md:gap-4">
                    <a href="{{ $isSuper ? route('superadmin.dashboard') : route('admin.dashboard') }}" class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-slate-800/50 border border-slate-700 text-slate-400 hover:text-{{ $accent }}-400 hover:bg-slate-800 transition-all flex items-center justify-center shadow-sm group active:scale-95">
                        <i class="fa-solid fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
                    </a>
                    <h1 class="block font-black text-xl md:text-2xl uppercase tracking-tighter italic text-white leading-tight">
                        ROFF.<span class="text-{{ $accent }}-500">{{ $isSuper ? 'SUPER' : 'ADMIN' }}</span>
                    </h1>
                </div>
                <div class="text-right hidden sm:block">
                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-[0.4em] mb-0.5">Pengaturan Akun</p>
                    <h1 class="text-xs md:text-sm font-black text-white uppercase tracking-widest">{{ $isSuper ? 'Owner Settings' : 'Staff Access' }}</h1>
                </div>
            </div>
        </header>
    @else
        <nav class="w-full bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
            <div class="w-full px-6 md:px-10 py-4 flex justify-between items-center">
                <a href="{{ url('/dashboard') }}" class="flex items-center gap-3 text-[10px] md:text-xs font-black text-slate-500 hover:text-blue-600 uppercase tracking-widest transition-colors group">
                    <div class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center group-hover:border-blue-600 group-hover:bg-blue-50 transition-all">
                        <i class="fa-solid fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                    </div>
                    Dashboard
                </a>
                <div class="text-right">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Pengaturan</p>
                    <h1 class="text-xs md:text-sm font-black text-slate-900 uppercase">Kelola Akun</h1>
                </div>
            </div>
        </nav>
    @endif

    {{-- NOTIFIKASI --}}
    <div class="w-full px-6 md:px-10 mt-8">
        @if(session('success'))
            <div class="{{ $isStaff ? 'bg-emerald-500/20 border-emerald-500/50 text-emerald-400' : 'bg-emerald-50 border-emerald-200 text-emerald-600' }} border px-6 py-4 rounded-2xl mb-2 text-xs md:text-sm font-bold flex items-center gap-3 shadow-sm animate-pulse">
                <i class="fa-solid fa-circle-check text-lg"></i> {{ session('success') }}
            </div>
        @endif
    </div>

    {{-- KONTEN UTAMA --}}
    <main class="w-full px-6 md:px-10 mt-4 flex-grow flex flex-col">
        
        <div class="text-center mb-10">
            <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tighter {{ $isStaff ? 'text-white' : 'text-slate-900' }} leading-none">
                Kelola <span class="text-{{ $accent }}-500 italic">Profil</span>
            </h1>
            <p class="{{ $isStaff ? 'text-slate-400' : 'text-slate-500' }} font-medium text-xs md:text-sm mt-3">Data diri Anda aman di sistem ROFF.SHOECLEAN.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 xl:gap-8 items-stretch max-w-7xl mx-auto w-full">
            
            {{-- KOLOM 1: AVATAR --}}
            <div class="glass-card rounded-[2.5rem] p-8 md:p-10 flex flex-col h-full relative overflow-hidden group">
                <div class="flex-grow flex flex-col items-center">
                    <div class="w-32 h-32 rounded-full border-4 {{ $isStaff ? 'border-slate-700 bg-slate-800' : 'border-white bg-blue-600' }} shadow-xl overflow-hidden text-white flex items-center justify-center font-black text-5xl mb-4 relative group-hover:scale-105 transition-all duration-500">
                        @if(auth()->user()->foto_profil)
                            <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" alt="Foto" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                        @endif
                    </div>

                    <h3 class="text-xl font-black {{ $isStaff ? 'text-white' : 'text-slate-900' }} uppercase tracking-tight mb-2 text-center">{{ auth()->user()->nama }}</h3>
                    <span class="{{ $isSuper ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : ($isAdmin ? 'bg-blue-500/20 text-blue-400 border-blue-500/30' : 'bg-blue-50 text-blue-600 border-blue-100') }} px-4 py-1.5 rounded-full text-[10px] uppercase font-black tracking-widest border">
                        {{ $isSuper ? 'SUPERADMIN' : ($isAdmin ? 'ADMIN' : 'CUSTOMER') }}
                    </span>
                    
                    <div class="w-full h-px {{ $isStaff ? 'bg-white/10' : 'bg-slate-200/60' }} my-8"></div>

                    <form action="{{ route('profil.updateFoto') }}" method="POST" enctype="multipart/form-data" id="form-upload" class="w-full text-left">
                        @csrf @method('PATCH')
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1 text-center">Ganti Foto Profil</label>
                        <div class="relative w-full group/dropzone">
                            <div class="w-full {{ $isStaff ? 'bg-slate-800/50 hover:border-'.$accent.'-500' : 'bg-slate-50 hover:border-blue-400' }} border-2 border-dashed border-slate-700 rounded-2xl p-5 flex flex-col items-center justify-center transition-all cursor-pointer">
                                <i class="fa-solid fa-cloud-arrow-up text-xl text-slate-500 mb-2"></i>
                                <p id="file-name" class="text-[10px] font-bold text-slate-400 text-center truncate w-full px-2">Cari foto...</p>
                            </div>
                            <input type="file" name="foto_profil" id="foto" required accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="document.getElementById('file-name').innerText = this.files[0].name">
                        </div>
                    </form>
                </div>

                <div class="pt-6 mt-auto space-y-3">
                    <button type="submit" form="form-upload" class="w-full bg-{{ $accent }}-600 hover:bg-slate-900 text-white py-4 rounded-xl font-black uppercase text-[10px] tracking-widest transition-all shadow-lg active:scale-95">Upload Foto</button>
                    @if(auth()->user()->foto_profil)
                        <form action="{{ route('profil.hapusFoto') }}" method="POST" class="w-full">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus foto?')" class="w-full bg-red-500/10 text-red-500 border border-red-500/20 hover:bg-red-500 hover:text-white py-3.5 rounded-xl font-black uppercase text-[10px] tracking-widest transition-all">Hapus Foto</button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- KOLOM 2: INFORMASI PRIBADI --}}
            <div class="glass-card rounded-[2.5rem] p-8 md:p-10 flex flex-col h-full">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-{{ $accent }}-500 text-white flex items-center justify-center shadow-lg"><i class="fa-solid fa-user-pen"></i></div>
                    <div>
                        <h2 class="text-lg font-black uppercase tracking-tight {{ $isStaff ? 'text-white' : 'text-slate-900' }}">Data Diri</h2>
                        <p class="text-[10px] font-medium text-slate-500">Update identitas utama Anda.</p>
                    </div>
                </div>

                <form action="{{ route('profil.update') }}" method="POST" class="flex flex-col flex-grow">
                    @csrf @method('PATCH')
                    <div class="space-y-5 flex-grow">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ auth()->user()->nama }}" required class="w-full {{ $isStaff ? 'bg-slate-800/50 border-slate-700 text-white focus:border-'.$accent.'-500' : 'bg-slate-50 border-slate-200 focus:border-blue-500' }} border px-5 py-4 rounded-xl text-xs font-bold outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Email</label>
                            <input type="email" name="email" value="{{ auth()->user()->email }}" required class="w-full {{ $isStaff ? 'bg-slate-800/50 border-slate-700 text-white focus:border-'.$accent.'-500' : 'bg-slate-50 border-slate-200 focus:border-blue-500' }} border px-5 py-4 rounded-xl text-xs font-bold outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">No. WhatsApp</label>
                            <input type="text" name="no_hp" value="{{ auth()->user()->no_telp }}" class="w-full {{ $isStaff ? 'bg-slate-800/50 border-slate-700 text-white focus:border-'.$accent.'-500' : 'bg-slate-50 border-slate-200 focus:border-blue-500' }} border px-5 py-4 rounded-xl text-xs font-bold outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Alamat</label>
                            <textarea name="alamat" rows="2" class="w-full {{ $isStaff ? 'bg-slate-800/50 border-slate-700 text-white focus:border-'.$accent.'-500' : 'bg-slate-50 border-slate-200 focus:border-blue-500' }} border px-5 py-4 rounded-xl text-xs font-bold outline-none resize-none">{{ auth()->user()->alamat }}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="w-full mt-8 bg-{{ $accent }}-600 hover:bg-slate-900 text-white py-4 rounded-xl font-black uppercase text-[10px] tracking-widest transition-all shadow-lg active:scale-95">Simpan Perubahan</button>
                </form>
            </div>

            {{-- KOLOM 3: GANTI PASSWORD --}}
            <div class="glass-card rounded-[2.5rem] p-8 md:p-10 flex flex-col h-full">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-slate-800 text-white border border-slate-700 flex items-center justify-center shadow-lg"><i class="fa-solid fa-shield-halved"></i></div>
                    <div>
                        <h2 class="text-lg font-black uppercase tracking-tight {{ $isStaff ? 'text-white' : 'text-slate-900' }}">Keamanan</h2>
                        <p class="text-[10px] font-medium text-slate-500">Perbarui kata sandi secara berkala.</p>
                    </div>
                </div>

                <form action="{{ route('profil.updatePassword') }}" method="POST" class="flex flex-col flex-grow">
                    @csrf @method('PATCH')
                    <div class="space-y-5 flex-grow">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Password Saat Ini</label>
                            <input type="password" name="current_password" required class="w-full {{ $isStaff ? 'bg-slate-800/50 border-slate-700 text-white focus:border-'.$accent.'-500' : 'bg-slate-50 border-slate-200 focus:border-blue-500' }} border px-5 py-4 rounded-xl text-xs font-bold outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Password Baru</label>
                            <input type="password" name="new_password" required class="w-full {{ $isStaff ? 'bg-slate-800/50 border-slate-700 text-white focus:border-'.$accent.'-500' : 'bg-slate-50 border-slate-200 focus:border-blue-500' }} border px-5 py-4 rounded-xl text-xs font-bold outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Konfirmasi Password</label>
                            <input type="password" name="new_password_confirmation" required class="w-full {{ $isStaff ? 'bg-slate-800/50 border-slate-700 text-white focus:border-'.$accent.'-500' : 'bg-slate-50 border-slate-200 focus:border-blue-500' }} border px-5 py-4 rounded-xl text-xs font-bold outline-none">
                        </div>
                    </div>
                    <button type="submit" class="w-full mt-8 bg-{{ $accent }}-600 hover:bg-slate-900 text-white py-4 rounded-xl font-black uppercase text-[10px] tracking-widest transition-all shadow-lg active:scale-95">Perbarui Password</button>
                </form>
            </div>

        </div>
    </main>
</body>
</html>