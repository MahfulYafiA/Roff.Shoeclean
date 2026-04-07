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
            background-color: #f8fafc; 
            color: #0f172a;
            overflow-x: hidden;
        }
        
        .bg-gradient-light {
            position: fixed; inset: 0; z-index: -3;
            background: 
                radial-gradient(at 10% 20%, hsla(215, 98%, 95%, 1) 0px, transparent 50%),
                radial-gradient(at 80% 0%, hsla(230, 96%, 96%, 1) 0px, transparent 50%),
                radial-gradient(at 0% 80%, hsla(240, 80%, 97%, 1) 0px, transparent 50%),
                radial-gradient(at 90% 90%, hsla(220, 90%, 96%, 1) 0px, transparent 50%);
        }
        .bg-dots {
            position: fixed; inset: 0; z-index: -2;
            background-image: radial-gradient(#94a3b8 1px, transparent 1px);
            background-size: 24px 24px; 
            opacity: 0.3; 
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 24px 48px -12px rgba(15, 23, 42, 0.05);
        }
    </style>
</head>
<body class="antialiased selection:bg-blue-600 selection:text-white min-h-screen relative pb-12 flex flex-col">

    {{-- Layer Background --}}
    <div class="bg-gradient-light"></div>
    <div class="bg-dots"></div>

    {{-- NAVBAR SEDERHANA --}}
    <nav class="w-full bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
        <div class="w-full px-6 md:px-10 py-4 flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-[10px] md:text-xs font-black text-slate-500 hover:text-blue-600 uppercase tracking-widest transition-colors group">
                <div class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center group-hover:border-blue-600 group-hover:bg-blue-50 transition-all shadow-sm">
                    <i class="fa-solid fa-chevron-left"></i>
                </div>
                Kembali
            </a>
            
            <div class="text-right">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Pengaturan</p>
                <h1 class="text-xs md:text-sm font-black text-slate-900 uppercase">Kelola Akun</h1>
            </div>
        </div>
    </nav>

    {{-- NOTIFIKASI --}}
    <div class="w-full px-6 md:px-10 mt-8">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-6 py-4 rounded-2xl mb-2 text-xs md:text-sm font-bold flex items-center gap-3 shadow-sm animate-pulse">
                <i class="fa-solid fa-circle-check text-lg"></i>
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-6 py-4 rounded-2xl mb-2 text-xs md:text-sm font-bold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-exclamation text-lg"></i>
                Terdapat kesalahan pada input Anda. Silakan periksa kembali.
            </div>
        @endif
    </div>

    {{-- KONTEN UTAMA --}}
    <main class="w-full px-6 md:px-10 mt-4 flex-grow flex flex-col">
        
        <div class="text-center mb-10">
            <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tighter text-slate-900 leading-tight">
                Kelola <span class="text-blue-600 italic">Profil</span>
            </h1>
            <p class="text-slate-500 font-medium text-xs md:text-sm mt-2">Perbarui data diri dan kata sandi akun Anda di sini.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 xl:gap-8 items-stretch">
            
            {{-- KOLOM 1: AVATAR --}}
            <div class="glass-card rounded-[2.5rem] p-8 md:p-10 flex flex-col h-full relative overflow-hidden group">
                <div class="flex-grow flex flex-col items-center">
                    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-32 h-32 bg-blue-500/20 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="w-32 h-32 rounded-full border-4 border-white shadow-xl overflow-hidden bg-gradient-to-tr from-blue-600 to-cyan-500 text-white flex items-center justify-center font-black text-5xl mb-4 relative group-hover:scale-105 transition-transform duration-500 mt-4 shrink-0">
                        @if(auth()->user()->foto_profil)
                            <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" alt="Foto Profil" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                        @endif
                    </div>

                    <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight mb-2">{{ auth()->user()->nama }}</h3>
                    <span class="inline-block bg-blue-50 text-blue-600 px-4 py-1.5 rounded-full text-[10px] uppercase font-black tracking-widest border border-blue-100 shadow-sm">
                        Customer
                    </span>
                    
                    <div class="w-full h-px bg-slate-200/60 my-8"></div>

                    <form action="{{ route('profil.updateFoto') }}" method="POST" enctype="multipart/form-data" id="form-upload" class="w-full text-left">
                        @csrf
                        @method('PATCH')
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Ganti Foto</label>
                        
                        <div class="relative w-full group/dropzone">
                            <div class="w-full bg-slate-50/50 hover:bg-blue-50/50 border-2 border-dashed border-slate-300 hover:border-blue-400 rounded-2xl p-5 flex flex-col items-center justify-center transition-all duration-300 cursor-pointer shadow-sm hover:shadow-md">
                                <div class="w-10 h-10 bg-white rounded-full shadow-sm flex items-center justify-center text-slate-400 group-hover/dropzone:text-blue-600 group-hover/dropzone:scale-110 transition-all mb-3 border border-slate-100">
                                    <i class="fa-solid fa-cloud-arrow-up text-lg"></i>
                                </div>
                                <p id="file-name" class="text-[11px] font-bold text-slate-600 group-hover/dropzone:text-blue-600 transition-colors text-center truncate w-full px-2">Cari foto disini</p>
                                <p class="text-[9px] font-medium text-slate-400 mt-1 text-center">JPG, PNG (Maks. 2MB)</p>
                            </div>
                            <input type="file" name="foto_profil" id="foto" required accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                onchange="document.getElementById('file-name').innerText = this.files[0] ? this.files[0].name : 'Cari foto disini';">
                        </div>
                    </form>
                </div>

                <div class="pt-6 mt-auto shrink-0 space-y-3">
                    <button type="submit" form="form-upload" class="w-full bg-slate-900 text-white py-4 rounded-xl font-black uppercase text-[10px] xl:text-[11px] tracking-widest hover:bg-blue-600 transition-colors shadow-lg">
                        Upload Foto
                    </button>
                    @if(auth()->user()->foto_profil)
                        <form action="{{ route('profil.hapusFoto') }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin ingin menghapus foto profil?')" class="w-full bg-red-50 text-red-600 border border-red-100 py-3.5 rounded-xl font-black uppercase text-[10px] tracking-widest hover:bg-red-600 hover:text-white transition-colors">
                                Hapus Foto
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- KOLOM 2: INFORMASI PRIBADI --}}
            <div class="glass-card rounded-[2.5rem] p-8 md:p-10 flex flex-col h-full">
                <div class="flex items-center gap-4 mb-8 shrink-0">
                    <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-600/30">
                        <i class="fa-solid fa-user text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg xl:text-xl font-black uppercase tracking-tight text-slate-900">Informasi Pribadi</h2>
                        <p class="text-[10px] xl:text-[11px] font-medium text-slate-500 mt-1">Perbarui data identitas utama Anda.</p>
                    </div>
                </div>

                <form action="{{ route('profil.update') }}" method="POST" class="flex flex-col flex-grow">
                    @csrf
                    @method('PATCH')
                    
                    <div class="space-y-5 flex-grow">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama', auth()->user()->nama) }}" required 
                                class="w-full bg-slate-50 border border-slate-200 px-5 py-3.5 xl:py-4 rounded-xl text-xs xl:text-sm font-bold text-slate-900 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required 
                                class="w-full bg-slate-50 border border-slate-200 px-5 py-3.5 xl:py-4 rounded-xl text-xs xl:text-sm font-bold text-slate-900 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">No. WhatsApp</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', auth()->user()->no_telp) }}" placeholder="08xxxxx" 
                                class="w-full bg-slate-50 border border-slate-200 px-5 py-3.5 xl:py-4 rounded-xl text-xs xl:text-sm font-bold text-slate-900 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Alamat Lengkap</label>
                            <textarea name="alamat" rows="2" placeholder="Masukkan alamat..."
                                class="w-full bg-slate-50 border border-slate-200 px-5 py-3.5 xl:py-4 rounded-xl text-xs xl:text-sm font-bold text-slate-900 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all resize-none">{{ old('alamat', auth()->user()->alamat) }}</textarea>
                        </div>
                    </div>

                    <div class="pt-6 mt-auto shrink-0">
                        <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-xl font-black uppercase text-[10px] xl:text-[11px] tracking-widest hover:bg-slate-900 shadow-lg hover:shadow-blue-600/30 transition-all duration-300">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- KOLOM 3: GANTI PASSWORD --}}
            <div class="glass-card rounded-[2.5rem] p-8 md:p-10 flex flex-col h-full">
                <div class="flex items-center gap-4 mb-8 shrink-0">
                    <div class="w-12 h-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center shadow-lg">
                        <i class="fa-solid fa-lock text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg xl:text-xl font-black uppercase tracking-tight text-slate-900">Ganti Kata Sandi</h2>
                        <p class="text-[10px] xl:text-[11px] font-medium text-slate-500 mt-1">Pastikan akun Anda tetap aman.</p>
                    </div>
                </div>

                <form action="{{ route('profil.updatePassword') }}" method="POST" class="flex flex-col flex-grow">
                    @csrf
                    @method('PATCH')
                    
                    <div class="space-y-5 flex-grow">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Password Saat Ini</label>
                            <input type="password" name="current_password" required placeholder="Masukkan password lama..."
                                class="w-full bg-slate-50 border border-slate-200 px-5 py-3.5 xl:py-4 rounded-xl text-xs xl:text-sm font-bold text-slate-900 focus:outline-none focus:bg-white focus:border-slate-500 focus:ring-4 focus:ring-slate-500/10 transition-all">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Password Baru</label>
                            <input type="password" name="new_password" required placeholder="Minimal 8 karakter"
                                class="w-full bg-slate-50 border border-slate-200 px-5 py-3.5 xl:py-4 rounded-xl text-xs xl:text-sm font-bold text-slate-900 focus:outline-none focus:bg-white focus:border-slate-500 focus:ring-4 focus:ring-slate-500/10 transition-all">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Ulangi Password Baru</label>
                            <input type="password" name="new_password_confirmation" required placeholder="Ketik ulang..."
                                class="w-full bg-slate-50 border border-slate-200 px-5 py-3.5 xl:py-4 rounded-xl text-xs xl:text-sm font-bold text-slate-900 focus:outline-none focus:bg-white focus:border-slate-500 focus:ring-4 focus:ring-slate-500/10 transition-all">
                        </div>
                    </div>

                    <div class="pt-6 mt-auto shrink-0">
                        <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-xl font-black uppercase text-[10px] xl:text-[11px] tracking-widest hover:bg-blue-600 shadow-lg transition-all duration-300">
                            Perbarui Password
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </main>

</body>
</html>