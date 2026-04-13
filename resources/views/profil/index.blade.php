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
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            overflow-x: hidden;
        }

        /* 🚨 THEME: LIGHT (Customer) 🚨 */
        body:not(.theme-dark) {
            background-color: #f8fafc; 
            color: #0f172a;
        }
        body:not(.theme-dark) .bg-gradient-light {
            position: fixed; inset: 0; z-index: -3;
            background: 
                radial-gradient(at 10% 20%, hsla(215, 98%, 95%, 1) 0px, transparent 50%),
                radial-gradient(at 80% 0%, hsla(230, 96%, 96%, 1) 0px, transparent 50%),
                radial-gradient(at 0% 80%, hsla(240, 80%, 97%, 1) 0px, transparent 50%),
                radial-gradient(at 90% 90%, hsla(220, 90%, 96%, 1) 0px, transparent 50%);
        }
        body:not(.theme-dark) .bg-dots {
            position: fixed; inset: 0; z-index: -2;
            background-image: radial-gradient(#94a3b8 1px, transparent 1px);
            background-size: 24px 24px; 
            opacity: 0.3; 
        }
        body:not(.theme-dark) .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 24px 48px -12px rgba(15, 23, 42, 0.05);
        }

        /* 🚨 THEME: DARK (Superadmin & Admin) 🚨 */
        body.theme-dark {
            background-color: #0f172a; 
            color: #f1f5f9;
        }
        body.theme-dark .bg-gradient-dark {
            position: fixed; inset: 0; z-index: -3;
            background: #0f172a;
        }
        body.theme-dark .bg-glow-emerald {
            position: fixed; top: 0; right: 0; width: 600px; height: 600px; 
            background: rgba(16, 185, 129, 0.1); filter: blur(120px); border-radius: 50%; 
            z-index: -2; transform: translate(30%, -30%); pointer-events: none;
        }
        body.theme-dark .bg-glow-blue {
            position: fixed; top: 0; right: 0; width: 600px; height: 600px; 
            background: rgba(59, 130, 246, 0.15); filter: blur(120px); border-radius: 50%; 
            z-index: -2; transform: translate(30%, -30%); pointer-events: none;
        }
        body.theme-dark .glass-card {
            background: rgba(30, 41, 59, 0.4); 
            backdrop-filter: blur(12px); 
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05); 
            box-shadow: 0 24px 48px -12px rgba(0, 0, 0, 0.5);
        }
        
        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        body:not(.theme-dark) .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; }
    </style>
</head>
<body class="antialiased min-h-screen relative pb-12 flex flex-col custom-scroll {{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'theme-dark selection:text-white ' . (auth()->user()->role == 'superadmin' ? 'selection:bg-emerald-500' : 'selection:bg-blue-500') : 'selection:bg-blue-600 selection:text-white' }}">

    {{-- Layer Background Dinamis --}}
    @if(in_array(auth()->user()->role, ['superadmin', 'admin']))
        <div class="bg-gradient-dark"></div>
        <div class="{{ auth()->user()->role == 'superadmin' ? 'bg-glow-emerald' : 'bg-glow-blue' }}"></div>
    @else
        <div class="bg-gradient-light"></div>
        <div class="bg-dots"></div>
    @endif

    {{-- NAVBAR DINAMIS --}}
    @if(in_array(auth()->user()->role, ['superadmin', 'admin']))
        {{-- NAVBAR DARK (SUPERADMIN & ADMIN) --}}
        <header class="w-full bg-[#0f172a]/40 backdrop-blur-xl border-b border-white/5 sticky top-0 z-50">
            <div class="w-full px-6 md:px-12 py-4 flex justify-between items-center">
                <div class="flex items-center gap-3 md:gap-4">
                    <a href="{{ url('/') }}" class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-slate-800/50 border border-slate-700 text-slate-400 {{ auth()->user()->role == 'superadmin' ? 'hover:text-emerald-400' : 'hover:text-blue-400' }} hover:bg-slate-800 transition-all flex items-center justify-center shadow-sm group active:scale-95" title="Kembali ke Beranda Utama">
                        <i class="fa-solid fa-house text-sm group-hover:scale-110 transition-transform"></i>
                    </a>
                    <h1 class="block font-black text-xl md:text-2xl uppercase tracking-tighter italic text-white leading-tight">
                        ROFF.<span class="{{ auth()->user()->role == 'superadmin' ? 'text-emerald-500' : 'text-blue-500' }}">{{ strtoupper(auth()->user()->role) }}</span>
                    </h1>
                </div>
                <div class="text-right hidden sm:block">
                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-[0.4em] mb-0.5">Panel Kendali</p>
                    <h1 class="text-xs md:text-sm font-black text-white uppercase tracking-widest">{{ auth()->user()->role == 'superadmin' ? 'Owner Profile' : 'Admin Profile' }}</h1>
                </div>
            </div>
        </header>
    @else
        {{-- NAVBAR CUSTOMER (LIGHT) --}}
        <nav class="w-full bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
            <div class="w-full px-6 md:px-10 py-4 flex justify-between items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-3 text-[10px] md:text-xs font-black text-slate-500 hover:text-blue-600 uppercase tracking-widest transition-colors group">
                    <div class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center group-hover:border-blue-600 group-hover:bg-blue-50 transition-all shadow-sm">
                        <i class="fa-solid fa-house text-xs group-hover:scale-110 transition-transform"></i>
                    </div>
                    Beranda Utama
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
            <div class="{{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? (auth()->user()->role == 'superadmin' ? 'bg-emerald-500/20 border-emerald-500/50 text-emerald-400' : 'bg-blue-500/20 border-blue-500/50 text-blue-400') : 'bg-emerald-50 border-emerald-200 text-emerald-600' }} border px-6 py-4 rounded-2xl mb-2 text-xs md:text-sm font-bold flex items-center gap-3 shadow-sm animate-pulse">
                <i class="fa-solid fa-circle-check text-lg"></i> {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="{{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'bg-red-500/20 border-red-500/50 text-red-400' : 'bg-red-50 border-red-200 text-red-600' }} border px-6 py-4 rounded-2xl mb-2 text-xs md:text-sm font-bold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-exclamation text-lg"></i> Terdapat kesalahan pada input Anda. Silakan periksa kembali.
            </div>
        @endif
    </div>

    {{-- KONTEN UTAMA --}}
    <main class="w-full px-6 md:px-10 mt-4 flex-grow flex flex-col">
        
        <div class="text-center mb-10">
            <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tighter {{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'text-white' : 'text-slate-900' }} leading-tight">
                Kelola <span class="{{ auth()->user()->role == 'superadmin' ? 'text-emerald-500' : (auth()->user()->role == 'admin' ? 'text-blue-500' : 'text-blue-600') }} italic">Profil</span>
            </h1>
            <p class="{{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'text-slate-400' : 'text-slate-500' }} font-medium text-xs md:text-sm mt-2 tracking-wide">Perbarui data diri dan keamanan akun Anda di sini.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 xl:gap-8 items-stretch">
            
            {{-- KOLOM 1: AVATAR --}}
            <div class="glass-card rounded-[2.5rem] p-8 md:p-10 flex flex-col h-full relative overflow-hidden group">
                <div class="flex-grow flex flex-col items-center">
                    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-32 h-32 {{ auth()->user()->role == 'superadmin' ? 'bg-emerald-500/20' : 'bg-blue-500/20' }} rounded-full blur-2xl pointer-events-none"></div>

                    <div class="w-32 h-32 rounded-full border-4 {{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'border-slate-700 bg-slate-800' : 'border-white bg-gradient-to-tr from-blue-600 to-cyan-500' }} shadow-xl overflow-hidden text-white flex items-center justify-center font-black text-5xl mb-4 relative group-hover:scale-105 transition-transform duration-500 mt-4 shrink-0">
                        @if(auth()->user()->foto_profil)
                            <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" alt="Foto Profil" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                        @endif
                    </div>

                    <h3 class="text-xl font-black {{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'text-white' : 'text-slate-900' }} uppercase tracking-tight mb-2">{{ auth()->user()->nama }}</h3>
                    <span class="inline-block {{ auth()->user()->role == 'superadmin' ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : (auth()->user()->role == 'admin' ? 'bg-blue-500/20 text-blue-400 border-blue-500/30' : 'bg-blue-50 text-blue-600 border-blue-100') }} px-4 py-1.5 rounded-full text-[10px] uppercase font-black tracking-widest border shadow-sm">
                        {{ auth()->user()->role ?? 'Customer' }}
                    </span>
                    
                    <div class="w-full h-px {{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'bg-white/10' : 'bg-slate-200/60' }} my-8"></div>

                    <form action="{{ route('profil.updateFoto') }}" method="POST" enctype="multipart/form-data" id="form-upload" class="w-full text-left">
                        @csrf
                        @method('PATCH')
                        <label class="block text-[10px] font-black uppercase tracking-widest {{ auth()->user()->role == 'superadmin' ? 'text-emerald-500/80' : (auth()->user()->role == 'admin' ? 'text-blue-400/80' : 'text-slate-400') }} mb-2 ml-1">Ganti Foto</label>
                        
                        <div class="relative w-full group/dropzone">
                            <div class="w-full {{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'bg-slate-800/50 hover:bg-slate-800 border-slate-700 '.(auth()->user()->role == 'superadmin' ? 'hover:border-emerald-500' : 'hover:border-blue-500') : 'bg-slate-50/50 hover:bg-blue-50/50 border-slate-300 hover:border-blue-400' }} border-2 border-dashed rounded-2xl p-5 flex flex-col items-center justify-center transition-all duration-300 cursor-pointer shadow-sm hover:shadow-md">
                                <div class="w-10 h-10 {{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'bg-slate-700 text-slate-400 '.(auth()->user()->role == 'superadmin' ? 'group-hover/dropzone:text-emerald-400' : 'group-hover/dropzone:text-blue-400').' border-slate-600' : 'bg-white text-slate-400 group-hover/dropzone:text-blue-600 border-slate-100' }} rounded-full shadow-sm flex items-center justify-center group-hover/dropzone:scale-110 transition-all mb-3 border">
                                    <i class="fa-solid fa-cloud-arrow-up text-lg"></i>
                                </div>
                                <p id="file-name" class="text-[11px] font-bold {{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'text-slate-300 group-hover/dropzone:text-white' : 'text-slate-600 group-hover/dropzone:text-blue-600' }} transition-colors text-center truncate w-full px-2">Cari foto disini</p>
                                <p class="text-[9px] font-medium {{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'text-slate-500' : 'text-slate-400' }} mt-1 text-center">JPG, PNG (Maks. 2MB)</p>
                            </div>
                            <input type="file" name="foto_profil" id="foto" required accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="document.getElementById('file-name').innerText = this.files[0] ? this.files[0].name : 'Cari foto disini';">
                        </div>
                    </form>
                </div>

                <div class="pt-6 mt-auto shrink-0 space-y-3">
                    {{-- TOMBOL UPLOAD FOTO (MENGGUNAKAN UNIFIED COLOR: BIRU -> HITAM) --}}
                    <button type="submit" form="form-upload" class="w-full bg-blue-600 hover:bg-slate-900 text-white py-4 rounded-xl font-black uppercase text-[10px] xl:text-[11px] tracking-widest transition-all shadow-lg active:scale-95">
                        Upload Foto
                    </button>
                    @if(auth()->user()->foto_profil)
                        <form action="{{ route('profil.hapusFoto') }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            {{-- Tombol Hapus Foto tetap menggunakan warna Role-specific (Merah) karena sifat destruktifnya --}}
                            <button type="submit" onclick="return confirm('Yakin ingin menghapus foto profil?')" class="w-full {{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'bg-red-500/10 text-red-500 border-red-500/30 hover:bg-red-500 hover:text-white' : 'bg-red-50 text-red-600 border-red-100 hover:bg-red-600 hover:text-white' }} border py-3.5 rounded-xl font-black uppercase text-[10px] tracking-widest transition-colors">
                                Hapus Foto
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- KOLOM 2: INFORMASI PRIBADI --}}
            <div class="glass-card rounded-[2.5rem] p-8 md:p-10 flex flex-col h-full">
                <div class="flex items-center gap-4 mb-8 shrink-0">
                    <div class="w-12 h-12 rounded-2xl {{ auth()->user()->role == 'superadmin' ? 'bg-emerald-500 text-white shadow-emerald-500/30' : 'bg-blue-600 text-white shadow-blue-600/30' }} flex items-center justify-center shadow-lg">
                        <i class="fa-solid fa-user text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg xl:text-xl font-black uppercase tracking-tight {{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'text-white' : 'text-slate-900' }}">Informasi Pribadi</h2>
                        <p class="text-[10px] xl:text-[11px] font-medium {{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'text-slate-400' : 'text-slate-500' }} mt-1">Perbarui data identitas utama Anda.</p>
                    </div>
                </div>

                <form action="{{ route('profil.update') }}" method="POST" class="flex flex-col flex-grow">
                    @csrf
                    @method('PATCH')
                    
                    <div class="space-y-5 flex-grow">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest {{ auth()->user()->role == 'superadmin' ? 'text-emerald-500/80' : (auth()->user()->role == 'admin' ? 'text-blue-400/80' : 'text-slate-400') }} mb-2 ml-1">Nama</label>
                            <input type="text" name="nama" value="{{ old('nama', auth()->user()->nama) }}" required 
                                class="w-full {{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'bg-slate-800/50 border-slate-700 text-white focus:bg-slate-800 focus:border-'.(auth()->user()->role == 'superadmin' ? 'emerald' : 'blue').'-500 placeholder:text-slate-600' : 'bg-slate-50 border-slate-200 text-slate-900 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10' }} border px-5 py-3.5 xl:py-4 rounded-xl text-xs xl:text-sm font-bold outline-none transition-all">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest {{ auth()->user()->role == 'superadmin' ? 'text-emerald-500/80' : (auth()->user()->role == 'admin' ? 'text-blue-400/80' : 'text-slate-400') }} mb-2 ml-1">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required 
                                class="w-full {{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'bg-slate-800/50 border-slate-700 text-white focus:bg-slate-800 focus:border-'.(auth()->user()->role == 'superadmin' ? 'emerald' : 'blue').'-500 placeholder:text-slate-600' : 'bg-slate-50 border-slate-200 text-slate-900 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10' }} border px-5 py-3.5 xl:py-4 rounded-xl text-xs xl:text-sm font-bold outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest {{ auth()->user()->role == 'superadmin' ? 'text-emerald-500/80' : (auth()->user()->role == 'admin' ? 'text-blue-400/80' : 'text-slate-400') }} mb-2 ml-1">No. WhatsApp</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', auth()->user()->no_telp) }}" placeholder="08xxxxx" 
                                class="w-full {{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'bg-slate-800/50 border-slate-700 text-white focus:bg-slate-800 focus:border-'.(auth()->user()->role == 'superadmin' ? 'emerald' : 'blue').'-500 placeholder:text-slate-600' : 'bg-slate-50 border-slate-200 text-slate-900 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10' }} border px-5 py-3.5 xl:py-4 rounded-xl text-xs xl:text-sm font-bold outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest {{ auth()->user()->role == 'superadmin' ? 'text-emerald-500/80' : (auth()->user()->role == 'admin' ? 'text-blue-400/80' : 'text-slate-400') }} mb-2 ml-1">Alamat Lengkap</label>
                            <textarea name="alamat" rows="2" placeholder="Masukkan alamat..."
                                class="w-full {{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'bg-slate-800/50 border-slate-700 text-white focus:bg-slate-800 focus:border-'.(auth()->user()->role == 'superadmin' ? 'emerald' : 'blue').'-500 placeholder:text-slate-600' : 'bg-slate-50 border-slate-200 text-slate-900 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10' }} border px-5 py-3.5 xl:py-4 rounded-xl text-xs xl:text-sm font-bold outline-none transition-all resize-none">{{ old('alamat', auth()->user()->alamat) }}</textarea>
                        </div>
                    </div>

                    <div class="pt-6 mt-auto shrink-0">
                        {{-- TOMBOL SIMPAN PERUBAHAN (MENGGUNAKAN UNIFIED COLOR: BIRU -> HITAM) --}}
                        <button type="submit" class="w-full bg-blue-600 hover:bg-slate-900 text-white py-4 rounded-xl font-black uppercase text-[10px] xl:text-[11px] tracking-widest shadow-lg shadow-blue-600/30 transition-all duration-300 active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- KOLOM 3: GANTI PASSWORD --}}
            <div class="glass-card rounded-[2.5rem] p-8 md:p-10 flex flex-col h-full">
                <div class="flex items-center gap-4 mb-8 shrink-0">
                    <div class="w-12 h-12 rounded-2xl {{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'bg-slate-800 text-white border border-slate-700' : 'bg-slate-900 text-white' }} flex items-center justify-center shadow-lg">
                        <i class="fa-solid fa-lock text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg xl:text-xl font-black uppercase tracking-tight {{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'text-white' : 'text-slate-900' }}">Ganti Kata Sandi</h2>
                        <p class="text-[10px] xl:text-[11px] font-medium {{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'text-slate-400' : 'text-slate-500' }} mt-1">Pastikan akun Anda tetap aman.</p>
                    </div>
                </div>

                <form action="{{ route('profil.updatePassword') }}" method="POST" class="flex flex-col flex-grow">
                    @csrf
                    @method('PATCH')
                    
                    <div class="space-y-5 flex-grow">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest {{ auth()->user()->role == 'superadmin' ? 'text-emerald-500/80' : (auth()->user()->role == 'admin' ? 'text-blue-400/80' : 'text-slate-400') }} mb-2 ml-1">Password Saat Ini</label>
                            <input type="password" name="current_password" required placeholder="Masukkan password lama..."
                                class="w-full {{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'bg-slate-800/50 border-slate-700 text-white focus:bg-slate-800 focus:border-'.(auth()->user()->role == 'superadmin' ? 'emerald' : 'blue').'-500 placeholder:text-slate-600' : 'bg-slate-50 border-slate-200 text-slate-900 focus:bg-white focus:border-slate-500 focus:ring-4 focus:ring-slate-500/10' }} border px-5 py-3.5 xl:py-4 rounded-xl text-xs xl:text-sm font-bold outline-none transition-all">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest {{ auth()->user()->role == 'superadmin' ? 'text-emerald-500/80' : (auth()->user()->role == 'admin' ? 'text-blue-400/80' : 'text-slate-400') }} mb-2 ml-1">Password Baru</label>
                            <input type="password" name="new_password" required placeholder="Minimal 8 karakter"
                                class="w-full {{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'bg-slate-800/50 border-slate-700 text-white focus:bg-slate-800 focus:border-'.(auth()->user()->role == 'superadmin' ? 'emerald' : 'blue').'-500 placeholder:text-slate-600' : 'bg-slate-50 border-slate-200 text-slate-900 focus:bg-white focus:border-slate-500 focus:ring-4 focus:ring-slate-500/10' }} border px-5 py-3.5 xl:py-4 rounded-xl text-xs xl:text-sm font-bold outline-none transition-all">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest {{ auth()->user()->role == 'superadmin' ? 'text-emerald-500/80' : (auth()->user()->role == 'admin' ? 'text-blue-400/80' : 'text-slate-400') }} mb-2 ml-1">Ulangi Password Baru</label>
                            <input type="password" name="new_password_confirmation" required placeholder="Ketik ulang..."
                                class="w-full {{ in_array(auth()->user()->role, ['superadmin', 'admin']) ? 'bg-slate-800/50 border-slate-700 text-white focus:bg-slate-800 focus:border-'.(auth()->user()->role == 'superadmin' ? 'emerald' : 'blue').'-500 placeholder:text-slate-600' : 'bg-slate-50 border-slate-200 text-slate-900 focus:bg-white focus:border-slate-500 focus:ring-4 focus:ring-slate-500/10' }} border px-5 py-3.5 xl:py-4 rounded-xl text-xs xl:text-sm font-bold outline-none transition-all">
                        </div>
                    </div>

                    <div class="pt-6 mt-auto shrink-0">
                        {{-- TOMBOL PERBARUI PASSWORD (MENGGUNAKAN UNIFIED COLOR: BIRU -> HITAM) --}}
                        <button type="submit" class="w-full bg-blue-600 hover:bg-slate-900 text-white py-4 rounded-xl font-black uppercase text-[10px] xl:text-[11px] tracking-widest shadow-lg shadow-blue-600/30 transition-all duration-300 active:scale-95">
                            Perbarui Password
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </main>

</body>
</html>