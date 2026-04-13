<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Layanan - ROFF.SUPERADMIN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0f172a; overflow: hidden; }
        
        /* Style Pill Hijau Aktif */
        .sidebar-active-emerald { 
            background: #10b981; 
            color: white !important; 
            box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.4); 
        }
        
        /* Glassmorphism Panel */
        .glass-panel { 
            background: rgba(30, 41, 59, 0.4); 
            backdrop-filter: blur(12px); 
            border: 1px solid rgba(255, 255, 255, 0.05); 
        }

        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    </style>
</head>
<body class="text-slate-200 antialiased flex h-screen overflow-hidden relative">

    {{-- MAIN CONTENT (DARK THEME) --}}
    <main class="flex-1 flex flex-col min-w-0 bg-[#0f172a] relative z-10">
        
        {{-- TOP NAVIGATION --}}
        <header class="bg-[#0f172a]/40 backdrop-blur-xl border-b border-white/5 px-6 md:px-12 py-4 flex justify-between items-center shrink-0 z-40">
            
            {{-- LOGO & TOMBOL KEMBALI KIRI --}}
            <div class="flex items-center gap-3 md:gap-4">
                {{-- 🚨 TOMBOL PANAH KEMBALI KE DASBOR 🚨 --}}
                <a href="{{ auth()->user()->role == 'superadmin' ? route('superadmin.dashboard') : route('admin.dashboard') }}" class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-slate-800/50 border border-slate-700 text-slate-400 hover:{{ auth()->user()->role == 'superadmin' ? 'text-emerald-400' : 'text-blue-400' }} hover:bg-slate-800 transition-all flex items-center justify-center shadow-sm group active:scale-95" title="Kembali ke Dasbor">
                    <i class="fa-solid fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
                </a>
                
                <h1 class="block font-black text-xl md:text-2xl uppercase tracking-tighter italic text-white leading-tight">
                    ROFF.<span class="{{ auth()->user()->role == 'superadmin' ? 'text-emerald-500' : 'text-blue-600' }}">{{ auth()->user()->role == 'superadmin' ? 'SUPER' : 'ADMIN' }}</span>
                </h1>
            </div>
            
            {{-- PROFIL KANAN ATAS --}}
            <div class="flex items-center gap-5">
                <div class="flex items-center bg-slate-800/40 border border-slate-700 p-1 pr-4 rounded-full shadow-inner">
                    <div class="w-8 h-8 rounded-full overflow-hidden {{ auth()->user()->role == 'superadmin' ? 'bg-emerald-500' : 'bg-blue-600' }} flex items-center justify-center text-[10px] font-black text-white border border-slate-700 shadow-xl">
                        {{ auth()->user()->role == 'superadmin' ? 'SU' : strtoupper(substr(auth()->user()->nama, 0, 2)) }}
                    </div>
                    <div class="ml-3 hidden md:block">
                        <p class="text-[10px] font-black text-white uppercase tracking-widest leading-none">{{ auth()->user()->role == 'superadmin' ? 'Superadmin' : explode(' ', auth()->user()->nama)[0] }}</p>
                        <p class="text-[7px] font-bold {{ auth()->user()->role == 'superadmin' ? 'text-emerald-500/60' : 'text-blue-500/60' }} uppercase mt-0.5 tracking-tighter">Verified Access</p>
                    </div>
                </div>
            </div>
        </header>

        {{-- AREA SCROLLABLE --}}
        <div class="p-6 md:p-12 flex-1 overflow-y-auto custom-scroll relative">
            
            {{-- Background Glow --}}
            <div class="absolute top-0 right-0 w-[500px] h-[500px] {{ auth()->user()->role == 'superadmin' ? 'bg-emerald-500/10' : 'bg-blue-600/10' }} blur-[120px] rounded-full pointer-events-none -translate-y-1/2 translate-x-1/4"></div>

            @if(session('success'))
                <div class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-400 px-6 py-4 rounded-2xl mb-8 text-xs font-bold shadow-lg flex items-center gap-3 backdrop-blur-sm relative z-10">
                    <i class="fa-solid fa-circle-check text-lg"></i> {{ session('success') }}
                </div>
            @endif

            {{-- HEADER HALAMAN & TOMBOL TAMBAH --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-8 md:mb-10 relative z-10">
                <div>
                    <div class="inline-flex items-center gap-2 {{ auth()->user()->role == 'superadmin' ? 'bg-emerald-500/10 border-emerald-500/20' : 'bg-blue-500/10 border-blue-500/20' }} border px-4 py-1.5 rounded-full mb-4">
                        <span class="w-1.5 h-1.5 rounded-full {{ auth()->user()->role == 'superadmin' ? 'bg-emerald-500' : 'bg-blue-500' }} animate-pulse"></span>
                        <p class="text-[8px] md:text-[9px] font-black {{ auth()->user()->role == 'superadmin' ? 'text-emerald-400' : 'text-blue-400' }} uppercase tracking-[0.4em]">Service & Banner Management</p>
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black text-white tracking-tighter leading-none mb-2">
                        Katalog <span class="italic text-transparent bg-clip-text {{ auth()->user()->role == 'superadmin' ? 'bg-gradient-to-r from-emerald-400 to-teal-200' : 'bg-gradient-to-r from-blue-400 to-indigo-200' }}">Layanan.</span>
                    </h1>
                    <p class="text-slate-400 font-medium text-sm">Kelola daftar harga, layanan, dan banner halaman depan web.</p>
                </div>

                <button onclick="openAddModal()" class="w-full md:w-auto {{ auth()->user()->role == 'superadmin' ? 'bg-emerald-500 shadow-emerald-500/20 hover:shadow-emerald-500/40' : 'bg-blue-600 shadow-blue-600/20 hover:shadow-blue-600/40' }} text-white px-8 py-4 md:py-4 rounded-[1.5rem] font-black uppercase text-[10px] tracking-[0.2em] transition-all flex items-center justify-center gap-3 shadow-lg hover:-translate-y-1 active:scale-95 shrink-0 group">
                    <i class="fa-solid fa-plus group-hover:rotate-90 transition-transform"></i> Tambah Layanan Baru
                </button>
            </div>

            {{-- SECTION BANNER --}}
            <div class="glass-panel rounded-[2rem] md:rounded-[2.5rem] p-6 md:p-10 shadow-2xl mb-10 relative overflow-hidden group z-10">
                <div class="absolute top-0 left-0 w-64 h-64 {{ auth()->user()->role == 'superadmin' ? 'bg-emerald-500/5' : 'bg-blue-500/5' }} rounded-full blur-[80px] -translate-y-1/2 -translate-x-1/4 opacity-50"></div>
                
                <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-8">
                    <div class="flex items-center gap-4 md:gap-6 w-full lg:w-auto">
                        <div class="w-12 h-12 md:w-16 md:h-16 bg-slate-800 {{ auth()->user()->role == 'superadmin' ? 'text-emerald-400' : 'text-blue-400' }} rounded-2xl md:rounded-3xl flex items-center justify-center text-xl md:text-2xl shadow-inner border border-slate-700">
                            <i class="fa-solid fa-image"></i>
                        </div>
                        <div>
                            <h3 class="text-base md:text-xl font-black uppercase tracking-tight text-white">BANNER WEBSITE</h3>
                            <p class="text-[8px] md:text-[10px] {{ auth()->user()->role == 'superadmin' ? 'text-emerald-500/80' : 'text-blue-500/80' }} font-bold uppercase tracking-[0.15em] mt-1">Update gambar utama Landing Page</p>
                        </div>
                    </div>

                    <form action="{{ route('update.hero.universal') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row items-center gap-6 w-full lg:w-auto">
                        @csrf
                        <div class="relative group/preview w-full md:w-auto">
                            <input type="file" name="hero_image" id="hero_input" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" onchange="previewHero(event)">
                            <div id="hero_preview_container" class="w-full md:w-64 h-28 md:h-32 bg-slate-800/50 border-2 border-dashed border-slate-700 rounded-[1.5rem] md:rounded-[2rem] flex flex-col items-center justify-center overflow-hidden transition-all duration-500 hover:border-slate-500 hover:bg-slate-800 relative shadow-inner">
                                <div id="hero_placeholder" class="flex flex-col items-center gap-2 text-center">
                                    <i class="fa-solid fa-cloud-arrow-up text-slate-500 text-2xl group-hover/preview:text-slate-400 transition-colors"></i>
                                    <p class="text-[8px] md:text-[9px] font-black text-slate-500 uppercase tracking-widest px-2 group-hover/preview:text-slate-400">Klik untuk Pilih Gambar</p>
                                </div>
                                <img id="hero_image_preview" class="hidden w-full h-full object-cover absolute inset-0 z-10">
                                <div id="hero_overlay" class="hidden absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] z-10 items-center justify-center">
                                    <span class="{{ auth()->user()->role == 'superadmin' ? 'bg-emerald-500' : 'bg-blue-600' }} px-4 py-2 rounded-full text-[8px] font-black uppercase tracking-widest text-white shadow-lg">Ganti Gambar</span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="w-full md:w-auto bg-slate-800 border border-slate-700 text-white px-10 py-5 rounded-[1.2rem] md:rounded-[1.5rem] text-[10px] font-black uppercase tracking-[0.2em] {{ auth()->user()->role == 'superadmin' ? 'hover:bg-emerald-500 hover:border-emerald-400' : 'hover:bg-blue-600 hover:border-blue-400' }} hover:-translate-y-1 transition-all shadow-xl active:scale-95 whitespace-nowrap">
                            Update Banner <i class="fa-solid fa-arrow-right ml-2"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- TABEL LAYANAN --}}
            <div class="glass-panel rounded-[2.5rem] shadow-2xl overflow-hidden mb-10 relative z-10">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[700px] md:min-w-0">
                        <thead>
                            <tr class="bg-slate-800/50 border-b border-slate-700 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                                <th class="px-8 py-6">Foto</th>
                                <th class="px-8 py-6">Layanan</th>
                                <th class="px-8 py-6">Deskripsi</th>
                                <th class="px-8 py-6">Harga</th>
                                <th class="px-8 py-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50 text-sm">
                            @forelse($layanans as $l)
                            <tr class="hover:bg-slate-800/30 transition-all group">
                                <td class="px-8 py-5">
                                    @if($l->gambar)
                                        <img src="{{ asset('storage/' . $l->gambar) }}" class="w-14 h-14 md:w-16 md:h-16 object-cover rounded-[1rem] shadow-md border border-slate-700">
                                    @else
                                        <div class="w-14 h-14 md:w-16 md:h-16 bg-slate-800 border border-slate-700 rounded-[1rem] flex items-center justify-center text-slate-500">
                                            <i class="fa-solid fa-image text-xl"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-8 py-5 font-black text-white uppercase italic tracking-tighter">{{ $l->nama_layanan }}</td>
                                <td class="px-8 py-5 text-slate-400 max-w-xs truncate text-xs">{{ $l->deskripsi }}</td>
                                <td class="px-8 py-5 font-black {{ auth()->user()->role == 'superadmin' ? 'text-emerald-400' : 'text-blue-400' }} text-base tracking-tighter">Rp {{ number_format($l->harga, 0, ',', '.') }}</td>
                                <td class="px-8 py-5 text-right whitespace-nowrap">
                                    <div class="flex justify-end gap-3">
                                        <button onclick="openEditModal({{ $l->id_layanan }})" class="w-10 h-10 rounded-full bg-slate-800 text-slate-400 {{ auth()->user()->role == 'superadmin' ? 'hover:bg-emerald-500' : 'hover:bg-blue-600' }} hover:text-white border border-slate-700 hover:border-transparent transition-all flex items-center justify-center shadow-lg active:scale-90">
                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </button>
                                        <form action="{{ route('superadmin.layanan.destroy', $l->id_layanan) }}" method="POST" onsubmit="return confirm('Hapus layanan ini beserta foto yang tertaut?');" class="m-0">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-10 h-10 rounded-full bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white border border-red-500/20 hover:border-transparent transition-all flex items-center justify-center shadow-lg active:scale-90">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- MODAL EDIT (DARK THEME) --}}
                            <div id="modalEdit{{ $l->id_layanan }}" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
                                <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeEditModal({{ $l->id_layanan }})"></div>
                                <div class="relative w-full max-w-lg bg-slate-900 border border-slate-700 rounded-[2.5rem] shadow-2xl p-8 md:p-10 max-h-[90vh] overflow-y-auto custom-scroll">
                                    <div class="flex justify-between items-center mb-8">
                                        <h3 class="text-2xl font-black uppercase tracking-tight text-white">Edit <span class="{{ auth()->user()->role == 'superadmin' ? 'text-emerald-500' : 'text-blue-500' }} italic">Layanan.</span></h3>
                                        <button onclick="closeEditModal({{ $l->id_layanan }})" class="w-10 h-10 rounded-full bg-slate-800 text-slate-400 hover:bg-red-500 hover:text-white transition-colors flex items-center justify-center">
                                            <i class="fa-solid fa-xmark text-xl"></i>
                                        </button>
                                    </div>
                                    <form action="{{ route('superadmin.layanan.update', $l->id_layanan) }}" method="POST" enctype="multipart/form-data" class="space-y-5 m-0">
                                        @csrf @method('PUT')
                                        <div class="flex justify-center mb-2">
                                            <img id="edit_preview_{{ $l->id_layanan }}" src="{{ $l->gambar ? asset('storage/' . $l->gambar) : '' }}" class="w-28 h-28 object-cover rounded-[1.5rem] border-4 border-slate-800 shadow-xl {{ $l->gambar ? '' : 'hidden' }}">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Nama Layanan</label>
                                            <input type="text" name="nama_layanan" value="{{ $l->nama_layanan }}" required class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-{{ auth()->user()->role == 'superadmin' ? 'emerald-500' : 'blue-500' }} focus:bg-slate-800 outline-none transition-all placeholder:text-slate-600">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Deskripsi</label>
                                            <textarea name="deskripsi" required class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white h-28 resize-none focus:border-{{ auth()->user()->role == 'superadmin' ? 'emerald-500' : 'blue-500' }} focus:bg-slate-800 outline-none transition-all">{{ $l->deskripsi }}</textarea>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                            <div>
                                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Harga (Rp)</label>
                                                <input type="number" name="harga" value="{{ $l->harga }}" required class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-{{ auth()->user()->role == 'superadmin' ? 'emerald-500' : 'blue-500' }} focus:bg-slate-800 outline-none transition-all">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Ganti Foto (Opsional)</label>
                                                <div class="relative w-full h-[52px] bg-slate-800/50 border border-slate-700 rounded-xl overflow-hidden flex items-center px-4">
                                                    <input type="file" name="gambar" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewEdit(event, {{ $l->id_layanan }})">
                                                    <div class="flex items-center gap-3 text-slate-400 pointer-events-none">
                                                        <i class="fa-solid fa-image"></i> <span class="text-xs font-bold truncate max-w-[100px]" id="file_name_{{ $l->id_layanan }}">Pilih file foto</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pt-6 mt-6 border-t border-slate-800">
                                            <button type="submit" class="w-full {{ auth()->user()->role == 'superadmin' ? 'bg-emerald-500 hover:bg-emerald-400 shadow-emerald-500/20' : 'bg-blue-600 hover:bg-blue-500 shadow-blue-600/20' }} text-white py-4 rounded-[1.2rem] font-black uppercase text-[11px] tracking-widest transition-all shadow-lg active:scale-95">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="5" class="p-24 text-center">
                                    <div class="flex flex-col items-center opacity-30">
                                        <i class="fa-solid fa-tags text-6xl mb-4 text-slate-400"></i>
                                        <p class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-400">Belum ada layanan terdaftar</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- FOOTER --}}
            <div class="mt-auto pt-6 pb-2 border-t border-white/5 flex justify-center items-center opacity-40 shrink-0 relative z-10">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] w-full text-center text-white">© 2026 ROFF.MASTER PANEL CONTROL</p>
            </div>
        </div>
    </main>

    {{-- MODAL TAMBAH (DARK THEME) --}}
    <div id="modalAdd" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeAddModal()"></div>
        <div class="relative w-full max-w-lg bg-slate-900 border border-slate-700 rounded-[2.5rem] shadow-2xl p-8 md:p-10 max-h-[90vh] overflow-y-auto custom-scroll">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-black uppercase tracking-tight text-white">Tambah <span class="{{ auth()->user()->role == 'superadmin' ? 'text-emerald-500' : 'text-blue-500' }} italic">Layanan.</span></h3>
                <button onclick="closeAddModal()" class="w-10 h-10 rounded-full bg-slate-800 text-slate-400 hover:bg-red-500 hover:text-white transition-colors flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form action="{{ route('superadmin.layanan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5 m-0">
                @csrf
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Nama Layanan</label>
                    <input type="text" name="nama_layanan" required placeholder="Contoh: Fast Clean" class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-{{ auth()->user()->role == 'superadmin' ? 'emerald-500' : 'blue-500' }} focus:bg-slate-800 outline-none transition-all placeholder:text-slate-600">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Deskripsi</label>
                    <textarea name="deskripsi" required placeholder="Detail proses pencucian..." class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white h-28 resize-none focus:border-{{ auth()->user()->role == 'superadmin' ? 'emerald-500' : 'blue-500' }} focus:bg-slate-800 outline-none transition-all placeholder:text-slate-600"></textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Harga (Rp)</label>
                        <input type="number" name="harga" required placeholder="35000" class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-{{ auth()->user()->role == 'superadmin' ? 'emerald-500' : 'blue-500' }} focus:bg-slate-800 outline-none transition-all placeholder:text-slate-600">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Pilih Foto</label>
                        <div class="relative w-full h-[52px] bg-slate-800/50 border border-slate-700 rounded-xl overflow-hidden flex items-center px-4 group/file hover:border-slate-500 transition-colors">
                            <input type="file" name="gambar" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="document.getElementById('add_file_name').innerText = this.files[0].name;">
                            <div class="flex items-center gap-3 text-slate-400 pointer-events-none group-hover/file:text-white transition-colors">
                                <i class="fa-solid fa-image"></i> <span class="text-xs font-bold truncate max-w-[100px]" id="add_file_name">Browse file...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pt-6 mt-6 border-t border-slate-800">
                    <button type="submit" class="w-full {{ auth()->user()->role == 'superadmin' ? 'bg-emerald-500 hover:bg-emerald-400 shadow-emerald-500/20' : 'bg-blue-600 hover:bg-blue-500 shadow-blue-600/20' }} text-white py-4 rounded-[1.2rem] font-black uppercase text-[11px] tracking-widest transition-all shadow-lg active:scale-95">Simpan Layanan Baru</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Preview Form Update Banner
        function previewHero(event) {
            const input = event.target;
            const preview = document.getElementById('hero_image_preview');
            const placeholder = document.getElementById('hero_placeholder');
            const overlay = document.getElementById('hero_overlay');
            const container = document.getElementById('hero_preview_container');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    overlay.classList.remove('hidden');
                    overlay.classList.add('flex');
                    container.classList.add('border-emerald-500', 'border-solid');
                    container.classList.remove('border-dashed');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Preview Form Update Layanan
        function previewEdit(event, id) {
            const preview = document.getElementById('edit_preview_' + id);
            const nameLabel = document.getElementById('file_name_' + id);
            if (event.target.files && event.target.files[0]) {
                nameLabel.innerText = event.target.files[0].name;
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(event.target.files[0]);
            }
        }

        function openAddModal() { 
            document.getElementById('modalAdd').classList.remove('hidden'); 
            document.getElementById('modalAdd').classList.add('flex'); 
        }
        function closeAddModal() { 
            document.getElementById('modalAdd').classList.add('hidden'); 
            document.getElementById('modalAdd').classList.remove('flex'); 
        }
        function openEditModal(id) { 
            document.getElementById('modalEdit' + id).classList.remove('hidden'); 
            document.getElementById('modalEdit' + id).classList.add('flex'); 
        }
        function closeEditModal(id) { 
            document.getElementById('modalEdit' + id).classList.add('hidden'); 
            document.getElementById('modalEdit' + id).classList.remove('flex'); 
        }
    </script>
</body>
</html>