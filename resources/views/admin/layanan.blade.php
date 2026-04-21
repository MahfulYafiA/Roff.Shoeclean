<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Layanan - ROFF.MANAGEMENT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0f172a; overflow: hidden; }
        .glass-panel { background: rgba(30, 41, 59, 0.4); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    </style>
</head>

@php
    // LOGIKA BUNGLON (Warna sesuai Role)
    $isSuper = auth()->user()->id_role == 1 || auth()->user()->role === 'superadmin';
    $accent = $isSuper ? 'emerald' : 'blue';

    // 🔥 LOGIKA MENGAMBIL FOTO DARI DATABASE 🔥
    
    // 1. Ambil Foto Banner
    $pengaturanBanner = \Illuminate\Support\Facades\DB::table('ms_pengaturan')->where('key', 'hero_image')->first();
    $bannerTersimpan = ($pengaturanBanner && $pengaturanBanner->value) ? asset('storage/' . $pengaturanBanner->value) : '';
    $hasBanner = !empty($bannerTersimpan);

    // 2. Ambil Foto Tentang Kami
    $pengaturanTentang = \Illuminate\Support\Facades\DB::table('ms_pengaturan')->where('key', 'tentang_image')->first();
    $tentangTersimpan = ($pengaturanTentang && $pengaturanTentang->value) ? asset('storage/' . $pengaturanTentang->value) : '';
    $hasTentang = !empty($tentangTersimpan);
@endphp

<body class="text-slate-200 antialiased flex h-screen overflow-hidden relative selection:bg-{{ $accent }}-500 selection:text-white">

    <main class="flex-1 flex flex-col min-w-0 bg-[#0f172a] relative z-10 h-screen">
        
        <header class="bg-[#0f172a]/40 backdrop-blur-xl border-b border-white/5 px-6 md:px-12 py-4 flex justify-between items-center shrink-0 z-40">
            <div class="flex items-center gap-3 md:gap-4">
                <a href="{{ $isSuper ? route('superadmin.dashboard') : route('admin.dashboard') }}" class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-slate-800/50 border border-slate-700 text-slate-400 hover:text-{{ $accent }}-400 hover:bg-slate-800 transition-all flex items-center justify-center shadow-sm group active:scale-95">
                    <i class="fa-solid fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <h1 class="block font-black text-xl md:text-2xl uppercase tracking-tighter italic text-white leading-tight">
                    ROFF.<span class="text-{{ $accent }}-500">{{ $isSuper ? 'SUPER' : 'ADMIN' }}</span>
                </h1>
            </div>
            <div class="flex items-center gap-5">
                <div class="flex items-center bg-slate-800/40 border border-slate-700 p-1 pr-4 rounded-full shadow-inner">
                    <div class="w-8 h-8 rounded-full overflow-hidden bg-{{ $accent }}-600 flex items-center justify-center text-[10px] font-black text-white border border-slate-700 shadow-xl shadow-{{ $accent }}-500/20">
                        @if(auth()->user()->foto_profil)
                            <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" class="w-full h-full object-cover">
                        @else
                            {{ $isSuper ? 'SU' : strtoupper(substr(auth()->user()->nama, 0, 2)) }}
                        @endif
                    </div>
                    <div class="ml-3 hidden md:block">
                        <p class="text-[10px] font-black text-white uppercase tracking-widest leading-none">{{ explode(' ', auth()->user()->nama)[0] }}</p>
                        <p class="text-[7px] font-bold text-{{ $accent }}-400/80 uppercase mt-0.5 tracking-tighter">{{ $isSuper ? 'Owner Access' : 'Staff Access' }}</p>
                    </div>
                </div>
            </div>
        </header>

        <div class="p-6 md:p-12 flex-1 overflow-y-auto custom-scroll relative">
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-{{ $accent }}-600/10 blur-[120px] rounded-full pointer-events-none -translate-y-1/2 translate-x-1/4"></div>

            @if(session('success'))
                <div class="bg-{{ $accent }}-500/20 border border-{{ $accent }}-500/50 text-{{ $accent }}-400 px-6 py-4 rounded-2xl mb-8 text-xs font-bold shadow-lg flex items-center gap-3 backdrop-blur-sm relative z-10">
                    <i class="fa-solid fa-circle-check text-lg"></i> {{ session('success') }}
                </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-8 md:mb-10 relative z-10">
                <div>
                    <div class="inline-flex items-center gap-2 bg-{{ $accent }}-500/10 border border-{{ $accent }}-500/20 px-4 py-1.5 rounded-full mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-{{ $accent }}-500 animate-pulse shadow-[0_0_10px_currentColor]"></span>
                        <p class="text-[8px] md:text-[9px] font-black text-{{ $accent }}-400 uppercase tracking-[0.4em]">Service & Banner Management</p>
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black text-white tracking-tighter leading-none mb-2">
                        Katalog <span class="italic text-transparent bg-clip-text bg-gradient-to-r from-{{ $accent }}-400 to-teal-200">Layanan.</span>
                    </h1>
                    <p class="text-slate-400 font-medium text-sm">Kelola daftar harga, layanan, dan banner halaman depan web.</p>
                </div>

                <button onclick="openAddModal()" class="w-full md:w-auto bg-{{ $accent }}-600 hover:bg-{{ $accent }}-500 text-white px-8 py-4 md:py-4 rounded-[1.5rem] font-black uppercase text-[10px] tracking-[0.2em] transition-all flex items-center justify-center gap-3 shadow-lg shadow-{{ $accent }}-500/20 hover:shadow-{{ $accent }}-500/40 hover:-translate-y-1 active:scale-95 shrink-0 group">
                    <i class="fa-solid fa-plus group-hover:rotate-90 transition-transform"></i> Tambah Layanan Baru
                </button>
            </div>

            {{-- SECTION UPDATE TAMPILAN (BANNER & TENTANG KAMI DIBUAT SEJAJAR) --}}
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-10 relative z-10">
                
                {{-- KOTAK 1: BANNER WEBSITE UTAMA --}}
                <div class="glass-panel rounded-[2rem] p-6 md:p-8 shadow-xl relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-48 h-48 bg-{{ $accent }}-600/10 rounded-full blur-[60px] -translate-y-1/2 -translate-x-1/4 opacity-50"></div>
                    <div class="relative z-10 flex flex-col sm:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-4 w-full sm:w-auto">
                            <div class="relative w-12 h-12 bg-slate-800 text-{{ $accent }}-400 rounded-2xl flex items-center justify-center text-xl shadow-inner border border-slate-700 overflow-hidden shrink-0">
                                <i id="banner_logo_icon" class="fa-solid fa-image relative z-10 {{ $hasBanner ? 'hidden' : '' }}"></i>
                                <img id="banner_logo_preview" src="{{ $bannerTersimpan }}" class="absolute inset-0 w-full h-full object-cover z-20 {{ $hasBanner ? '' : 'hidden' }}">
                            </div>
                            <div>
                                <h3 class="text-sm font-black uppercase tracking-tight text-white">BANNER WEB</h3>
                                <p class="text-[8px] text-{{ $accent }}-400/80 font-bold uppercase tracking-widest mt-1">Gambar Utama Beranda</p>
                            </div>
                        </div>
                        <form action="{{ route('update.hero.universal') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                            @csrf
                            <div class="relative group/preview w-full sm:w-auto">
                                <input type="file" name="hero_image" id="hero_input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" onchange="previewHero(event)" {{ $hasBanner ? '' : 'required' }}>
                                <div id="hero_preview_container" class="w-full sm:w-40 h-16 sm:h-20 bg-slate-800/50 border-2 {{ $hasBanner ? 'border-'.$accent.'-500 border-solid' : 'border-dashed border-slate-700' }} rounded-2xl flex flex-col items-center justify-center overflow-hidden transition-all hover:border-{{ $accent }}-500 relative shadow-inner">
                                    <div id="hero_placeholder" class="flex flex-col items-center gap-1 text-center {{ $hasBanner ? 'hidden' : '' }}">
                                        <i class="fa-solid fa-upload text-slate-500 group-hover/preview:text-{{ $accent }}-400"></i>
                                    </div>
                                    <img id="hero_image_preview" src="{{ $bannerTersimpan }}" class="w-full h-full object-cover absolute inset-0 z-10 {{ $hasBanner ? '' : 'hidden' }}">
                                    <div id="hero_overlay" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] z-10 items-center justify-center {{ $hasBanner ? 'flex' : 'hidden' }}">
                                        <span class="bg-{{ $accent }}-600 px-3 py-1.5 rounded-full text-[7px] font-black uppercase text-white shadow-lg">Ganti</span>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="w-full sm:w-auto bg-slate-800 border border-slate-700 text-white px-6 py-3.5 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-{{ $accent }}-600 hover:border-{{ $accent }}-500 transition-all shadow-lg active:scale-95">Update</button>
                        </form>
                    </div>
                </div>

                {{-- KOTAK 2: GAMBAR TENTANG KAMI --}}
                <div class="glass-panel rounded-[2rem] p-6 md:p-8 shadow-xl relative overflow-hidden group border-indigo-500/20 hover:border-indigo-500/40 transition-colors">
                    <div class="absolute top-0 right-0 w-48 h-48 bg-indigo-600/10 rounded-full blur-[60px] -translate-y-1/2 translate-x-1/4 opacity-50"></div>
                    <div class="relative z-10 flex flex-col sm:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-4 w-full sm:w-auto">
                            <div class="relative w-12 h-12 bg-slate-800 text-indigo-400 rounded-2xl flex items-center justify-center text-xl shadow-inner border border-slate-700 overflow-hidden shrink-0">
                                <i id="tentang_logo_icon" class="fa-solid fa-users relative z-10 {{ $hasTentang ? 'hidden' : '' }}"></i>
                                <img id="tentang_logo_preview" src="{{ $tentangTersimpan }}" class="absolute inset-0 w-full h-full object-cover z-20 {{ $hasTentang ? '' : 'hidden' }}">
                            </div>
                            <div>
                                <h3 class="text-sm font-black uppercase tracking-tight text-white">FOTO TENTANG</h3>
                                <p class="text-[8px] text-indigo-400/80 font-bold uppercase tracking-widest mt-1">Gambar Profil Toko</p>
                            </div>
                        </div>
                        <form action="{{ route('update.tentang.universal') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                            @csrf
                            <div class="relative group/preview w-full sm:w-auto">
                                <input type="file" name="tentang_image" id="tentang_input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" onchange="previewTentang(event)" {{ $hasTentang ? '' : 'required' }}>
                                <div id="tentang_preview_container" class="w-full sm:w-40 h-16 sm:h-20 bg-slate-800/50 border-2 {{ $hasTentang ? 'border-indigo-500 border-solid' : 'border-dashed border-slate-700' }} rounded-2xl flex flex-col items-center justify-center overflow-hidden transition-all hover:border-indigo-500 relative shadow-inner">
                                    <div id="tentang_placeholder" class="flex flex-col items-center gap-1 text-center {{ $hasTentang ? 'hidden' : '' }}">
                                        <i class="fa-solid fa-upload text-slate-500 group-hover/preview:text-indigo-400"></i>
                                    </div>
                                    <img id="tentang_image_preview" src="{{ $tentangTersimpan }}" class="w-full h-full object-cover absolute inset-0 z-10 {{ $hasTentang ? '' : 'hidden' }}">
                                    <div id="tentang_overlay" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] z-10 items-center justify-center {{ $hasTentang ? 'flex' : 'hidden' }}">
                                        <span class="bg-indigo-600 px-3 py-1.5 rounded-full text-[7px] font-black uppercase text-white shadow-lg">Ganti</span>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="w-full sm:w-auto bg-slate-800 border border-slate-700 text-white px-6 py-3.5 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:border-indigo-500 transition-all shadow-lg active:scale-95">Update</button>
                        </form>
                    </div>
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
                            <tr class="hover:bg-slate-800/30 transition-all group {{ $l->is_active ? '' : 'opacity-40 hover:opacity-100 grayscale hover:grayscale-0' }}">
                                <td class="px-8 py-5">
                                    @if($l->gambar)
                                        <img src="{{ asset('storage/' . $l->gambar) }}" class="w-14 h-14 md:w-16 md:h-16 object-cover rounded-[1rem] shadow-md border border-slate-700">
                                    @else
                                        <div class="w-14 h-14 md:w-16 md:h-16 bg-slate-800 border border-slate-700 rounded-[1rem] flex items-center justify-center text-slate-500">
                                            <i class="fa-solid fa-image text-xl"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-8 py-5 font-black text-white uppercase italic tracking-tighter">
                                    {{ $l->nama_layanan }}
                                    @if(!$l->is_active)
                                        <span class="ml-2 bg-red-500/20 text-red-400 text-[8px] px-2 py-0.5 rounded-full not-italic tracking-widest border border-red-500/30">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-slate-400 max-w-xs truncate text-xs">{{ $l->deskripsi }}</td>
                                <td class="px-8 py-5 font-black text-{{ $accent }}-400 text-base tracking-tighter">Rp {{ number_format($l->harga, 0, ',', '.') }}</td>
                                <td class="px-8 py-5 text-right whitespace-nowrap">
                                    <div class="flex justify-end gap-2">
                                        
                                        {{-- 🚨 TOMBOL TOGGLE AKTIF/NONAKTIF 🚨 --}}
                                        <form action="{{ $isSuper ? route('superadmin.layanan.toggle', $l->id_layanan) : route('admin.layanan.toggle', $l->id_layanan) }}" method="POST" class="m-0 inline-block">
                                            @csrf
                                            <button type="submit" 
                                                class="w-10 h-10 rounded-full transition-all flex items-center justify-center shadow-lg active:scale-90 border border-slate-700 hover:border-transparent 
                                                {{ $l->is_active ? 'bg-slate-800 text-green-400 hover:bg-green-500 hover:text-white' : 'bg-slate-800/50 text-slate-500 hover:bg-slate-600 hover:text-white' }}" 
                                                title="{{ $l->is_active ? 'Nonaktifkan Layanan' : 'Aktifkan Layanan' }}">
                                                <i class="fa-solid {{ $l->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }} text-base"></i>
                                            </button>
                                        </form>

                                        {{-- Tombol Edit --}}
                                        <button onclick="openEditModal('{{ $l->id_layanan }}', '{{ $l->nama_layanan }}', '{{ $l->harga }}', '{{ $l->deskripsi }}', '{{ $l->gambar ? asset('storage/' . $l->gambar) : '' }}')" class="w-10 h-10 rounded-full bg-slate-800 text-slate-400 hover:bg-{{ $accent }}-600 hover:text-white border border-slate-700 hover:border-transparent transition-all flex items-center justify-center shadow-lg active:scale-90">
                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </button>
                                        
                                        {{-- Tombol Hapus --}}
                                        <form action="{{ $isSuper ? route('superadmin.layanan.destroy', $l->id_layanan) : route('admin.layanan.destroy', $l->id_layanan) }}" method="POST" onsubmit="return confirm('Hapus layanan ini beserta foto yang tertaut?');" class="m-0 inline-block">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-10 h-10 rounded-full bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white border border-red-500/20 hover:border-transparent transition-all flex items-center justify-center shadow-lg active:scale-90">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
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
            
            <div class="mt-auto pt-6 pb-2 border-t border-white/5 flex justify-center items-center opacity-40 shrink-0 relative z-10">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] w-full text-center text-white">© 2026 ROFF.MASTER PANEL CONTROL</p>
            </div>
        </div>
    </main>

    {{-- MODAL TAMBAH & EDIT --}}
    <div id="modalLayanan" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="toggleModal()"></div>
        <div class="relative w-full max-w-lg bg-slate-900 border border-slate-700 rounded-[2.5rem] shadow-2xl p-8 md:p-10 max-h-[90vh] overflow-y-auto custom-scroll">
            <div class="flex justify-between items-center mb-8">
                <h3 id="modalTitle" class="text-2xl font-black uppercase tracking-tight text-white">Tambah <span class="text-{{ $accent }}-500 italic">Layanan.</span></h3>
                <button onclick="toggleModal()" class="w-10 h-10 rounded-full bg-slate-800 text-slate-400 hover:bg-red-500 hover:text-white transition-colors flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <form id="layananForm" method="POST" enctype="multipart/form-data" class="space-y-5 m-0">
                @csrf
                <div id="methodField"></div>
                
                <div class="flex justify-center mb-2">
                    <div class="relative group/item">
                        <img id="item_preview" src="" class="hidden w-28 h-28 object-cover rounded-[1.5rem] border-4 border-slate-800 shadow-xl">
                        <div id="item_placeholder" class="w-28 h-28 bg-slate-800/50 border-2 border-dashed border-slate-700 rounded-[1.5rem] flex flex-col items-center justify-center text-slate-500">
                            <i class="fa-solid fa-image text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Nama Layanan</label>
                    <input type="text" name="nama_layanan" id="inputNama" required placeholder="Contoh: Fast Clean" class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-{{ $accent }}-500 focus:bg-slate-800 outline-none transition-all placeholder:text-slate-600">
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Harga (Rp)</label>
                        <input type="number" name="harga" id="inputHarga" required placeholder="35000" class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-{{ $accent }}-500 focus:bg-slate-800 outline-none transition-all placeholder:text-slate-600">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Pilih Foto</label>
                        <div class="relative w-full h-[52px] bg-slate-800/50 border border-slate-700 rounded-xl overflow-hidden flex items-center px-4 group/file hover:border-{{ $accent }}-500 transition-colors mt-2">
                            <input type="file" name="gambar" id="inputGambar" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewItem(event)">
                            <div class="flex items-center gap-3 text-slate-400 pointer-events-none group-hover/file:text-{{ $accent }}-400 transition-colors">
                                <i class="fa-solid fa-image"></i> <span class="text-xs font-bold truncate max-w-[100px]" id="add_file_name">Browse file...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Deskripsi</label>
                    <textarea name="deskripsi" id="inputDeskripsi" rows="3" required placeholder="Detail proses pencucian..." class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white h-28 resize-none focus:border-{{ $accent }}-500 focus:bg-slate-800 outline-none transition-all placeholder:text-slate-600"></textarea>
                </div>
                
                <div class="pt-6 mt-6 border-t border-slate-800">
                    <button type="submit" class="w-full bg-{{ $accent }}-600 hover:bg-{{ $accent }}-500 text-white py-4 rounded-[1.2rem] font-black uppercase text-[11px] tracking-widest transition-all shadow-lg shadow-{{ $accent }}-500/20 active:scale-95">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewHero(event) {
            const input = event.target;
            const preview = document.getElementById('hero_image_preview');
            const placeholder = document.getElementById('hero_placeholder');
            const overlay = document.getElementById('hero_overlay');
            const container = document.getElementById('hero_preview_container');
            const logoIcon = document.getElementById('banner_logo_icon');
            const logoPreview = document.getElementById('banner_logo_preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const resultData = e.target.result;
                    preview.src = resultData;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    overlay.classList.remove('hidden');
                    overlay.classList.add('flex');
                    container.classList.add('border-{{ $accent }}-500', 'border-solid');
                    container.classList.remove('border-dashed');
                    container.classList.remove('border-slate-700');

                    logoPreview.src = resultData;
                    logoPreview.classList.remove('hidden');
                    logoIcon.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewTentang(event) {
            const input = event.target;
            const preview = document.getElementById('tentang_image_preview');
            const placeholder = document.getElementById('tentang_placeholder');
            const overlay = document.getElementById('tentang_overlay');
            const container = document.getElementById('tentang_preview_container');
            const logoIcon = document.getElementById('tentang_logo_icon');
            const logoPreview = document.getElementById('tentang_logo_preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const resultData = e.target.result;
                    preview.src = resultData;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    overlay.classList.remove('hidden');
                    overlay.classList.add('flex');
                    container.classList.add('border-indigo-500', 'border-solid');
                    container.classList.remove('border-dashed');
                    container.classList.remove('border-slate-700');

                    logoPreview.src = resultData;
                    logoPreview.classList.remove('hidden');
                    logoIcon.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewItem(event) {
            const preview = document.getElementById('item_preview');
            const placeholder = document.getElementById('item_placeholder');
            const nameLabel = document.getElementById('add_file_name');
            if (event.target.files && event.target.files[0]) {
                nameLabel.innerText = event.target.files[0].name;
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(event.target.files[0]);
            }
        }

        function openAddModal() {
            document.getElementById('modalTitle').innerHTML = 'Tambah <span class="text-{{ $accent }}-500 italic">Layanan.</span>';
            document.getElementById('layananForm').action = "{{ $isSuper ? route('superadmin.layanan.store') : route('admin.layanan.store') }}";
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('inputNama').value = '';
            document.getElementById('inputHarga').value = '';
            document.getElementById('inputDeskripsi').value = '';
            document.getElementById('item_preview').classList.add('hidden');
            document.getElementById('item_placeholder').classList.remove('hidden');
            document.getElementById('add_file_name').innerText = 'Browse file...';
            toggleModal();
        }

        function openEditModal(id, nama, harga, deskripsi, gambarUrl) {
            document.getElementById('modalTitle').innerHTML = 'Edit <span class="text-{{ $accent }}-500 italic">Layanan.</span>';
            // ✅ UPDATE: Menyesuaikan route update dengan role pengguna
            const updateUrl = "{{ $isSuper ? '/superadmin/layanan/' : '/admin/layanan/' }}" + id;
            document.getElementById('layananForm').action = updateUrl;
            
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">'; 
            document.getElementById('inputNama').value = nama;
            document.getElementById('inputHarga').value = harga;
            document.getElementById('inputDeskripsi').value = deskripsi;
            if(gambarUrl) {
                document.getElementById('item_preview').src = gambarUrl;
                document.getElementById('item_preview').classList.remove('hidden');
                document.getElementById('item_placeholder').classList.add('hidden');
            } else {
                document.getElementById('item_preview').classList.add('hidden');
                document.getElementById('item_placeholder').classList.remove('hidden');
            }
            document.getElementById('add_file_name').innerText = 'Ganti file (Opsional)';
            toggleModal();
        }

        function toggleModal() {
            const modal = document.getElementById('modalLayanan');
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
            document.body.style.overflow = modal.classList.contains('hidden') ? 'auto' : 'hidden';
        }
    </script>
</body>
</html>