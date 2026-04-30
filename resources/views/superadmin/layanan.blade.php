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

    @php
        $isSuper = auth()->user()->role === 'superadmin';
        $accent = $isSuper ? 'emerald' : 'blue';
        
        $dbHero = Illuminate\Support\Facades\DB::table('ms_pengaturan')->where('key', 'hero_image')->first();
        $dbTentang = Illuminate\Support\Facades\DB::table('ms_pengaturan')->where('key', 'tentang_image')->first();

        // KITA CEK APAKAH VALUE ADA DI DATABASE DAN ADA DI FOLDER
        $currentHero = ($dbHero && $dbHero->value && file_exists(public_path('storage/' . $dbHero->value))) 
                       ? asset('storage/' . $dbHero->value) . '?t=' . time() 
                       : 'https://placehold.co/600x400/1e293b/475569?text=Kosong';

        $currentAbout = ($dbTentang && $dbTentang->value && file_exists(public_path('storage/' . $dbTentang->value))) 
                        ? asset('storage/' . $dbTentang->value) . '?t=' . time() 
                        : 'https://placehold.co/600x400/1e293b/475569?text=Kosong';
    @endphp

    <main class="flex-1 flex flex-col min-w-0 bg-[#0f172a] relative z-10">
        
        {{-- TOP NAVIGATION --}}
        <header class="bg-[#0f172a]/40 backdrop-blur-xl border-b border-white/5 px-6 md:px-12 py-4 flex justify-between items-center shrink-0 z-40">
            <div class="flex items-center gap-3">
                <a href="{{ $isSuper ? route('superadmin.dashboard') : route('admin.dashboard') }}" class="w-10 h-10 rounded-full bg-slate-800/50 border border-slate-700 text-slate-400 hover:text-{{ $accent }}-400 flex items-center justify-center group transition-all">
                    <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <h1 class="font-black text-xl md:text-2xl uppercase tracking-tighter italic text-white leading-tight">
                    ROFF.<span class="text-{{ $accent }}-500">{{ $isSuper ? 'SUPER' : 'ADMIN' }}</span>
                </h1>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="flex items-center bg-slate-800/40 border border-slate-700 p-1 pr-4 rounded-full shadow-inner">
                    <div class="w-8 h-8 rounded-full bg-{{ $accent }}-500 flex items-center justify-center text-[10px] font-black text-white shadow-lg">SU</div>
                    <p class="ml-3 text-[10px] font-black text-white uppercase hidden md:block">SUPERADMIN</p>
                </div>
            </div>
        </header>

        <div class="p-6 md:p-12 flex-1 overflow-y-auto custom-scroll relative">
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-{{ $accent }}-600/10 blur-[120px] rounded-full pointer-events-none"></div>

            @if(session('success'))
                <div class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-400 px-6 py-4 rounded-2xl mb-8 text-xs font-bold shadow-lg flex items-center gap-3 backdrop-blur-sm relative z-10">
                    <i class="fa-solid fa-circle-check text-lg"></i> {{ session('success') }}
                </div>
            @endif

            {{-- HEADER HALAMAN --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-10 relative z-10">
                <div>
                    <h1 class="text-3xl md:text-5xl font-black text-white tracking-tighter leading-none mb-2">
                        Katalog <span class="italic text-transparent bg-clip-text bg-gradient-to-r from-{{ $accent }}-400 to-teal-200">Layanan</span>
                    </h1>
                    <p class="text-slate-400 font-medium text-sm uppercase tracking-widest opacity-60">Visual & Content Management</p>
                </div>

                <button onclick="openAddModal()" class="w-full md:w-auto bg-{{ $accent }}-600 text-white px-8 py-4 rounded-[1.5rem] font-black uppercase text-[10px] tracking-[0.2em] transition-all flex items-center justify-center gap-3 shadow-lg hover:-translate-y-1 active:scale-95 group">
                    <i class="fa-solid fa-plus group-hover:rotate-90 transition-transform"></i> Tambah Layanan Baru
                </button>
            </div>

            {{-- SECTION PENGATURAN GAMBAR (BANNER & TENTANG) - KEMBALI HADIR! --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12 relative z-10">
                
                {{-- CARD 1: BANNER WEBSITE --}}
                <div class="glass-panel rounded-[2.5rem] p-8 shadow-2xl relative overflow-hidden group border border-white/5">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-slate-800 text-{{ $accent }}-400 rounded-xl flex items-center justify-center text-xl border border-slate-700 shadow-lg"><i class="fa-solid fa-image"></i></div>
                        <h3 class="text-base font-black uppercase text-white tracking-widest">Banner Website</h3>
                    </div>

                    <form action="{{ route('update.hero.universal') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex items-center justify-around gap-4 bg-slate-900/50 p-6 rounded-[2rem] border border-slate-700 shadow-inner mb-6">
                            <div class="text-center space-y-3">
                                <div class="w-28 h-20 rounded-xl border-2 border-slate-700 overflow-hidden bg-slate-800 flex items-center justify-center">
                                    <img src="{{ $currentHero }}" class="w-full h-full object-cover opacity-80" onerror="this.src='https://placehold.co/600x400/1e293b/475569?text=Kosong'">
                                </div>
                                <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest">Lama</p>
                            </div>
                            <i class="fa-solid fa-right-long text-slate-700 text-xl animate-pulse"></i>
                            <div class="text-center space-y-3">
                                <div class="w-28 h-20 rounded-xl border-2 border-dashed border-{{ $accent }}-500/50 overflow-hidden bg-slate-800 flex items-center justify-center">
                                    <img id="preview_banner_img" src="" class="hidden w-full h-full object-cover">
                                    <i id="placeholder_banner" class="fa-solid fa-cloud-arrow-up text-{{ $accent }}-500/20 text-xl"></i>
                                </div>
                                <p class="text-[8px] font-black text-{{ $accent }}-500 uppercase tracking-widest">Baru</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <input type="file" name="hero_image" required class="w-full bg-slate-800 border border-slate-700 px-5 py-3 rounded-2xl text-[10px] text-slate-400 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-{{ $accent }}-600 file:text-white" onchange="previewFile(event, 'preview_banner_img', 'placeholder_banner')">
                            <button type="submit" class="w-full bg-slate-800 border border-slate-700 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-{{ $accent }}-600 transition-all shadow-xl">Update Banner Sekarang</button>
                        </div>
                    </form>
                </div>

                {{-- CARD 2: FOTO TENTANG KAMI --}}
                <div class="glass-panel rounded-[2.5rem] p-8 shadow-2xl relative overflow-hidden group border border-white/5">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-slate-800 text-{{ $accent }}-400 rounded-xl flex items-center justify-center text-xl border border-slate-700 shadow-lg"><i class="fa-solid fa-users"></i></div>
                        <h3 class="text-base font-black uppercase text-white tracking-widest">Foto Tentang</h3>
                    </div>

                    <form action="{{ route('update.tentang.universal') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex items-center justify-around gap-4 bg-slate-900/50 p-6 rounded-[2rem] border border-slate-700 shadow-inner mb-6">
                            <div class="text-center space-y-3">
                                <div class="w-28 h-20 rounded-xl border-2 border-slate-700 overflow-hidden bg-slate-800 flex items-center justify-center">
                                    <img src="{{ $currentAbout }}" class="w-full h-full object-cover opacity-80" onerror="this.src='https://placehold.co/600x400/1e293b/475569?text=Kosong'">
                                </div>
                                <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest">Lama</p>
                            </div>
                            <i class="fa-solid fa-right-long text-slate-700 text-xl animate-pulse"></i>
                            <div class="text-center space-y-3">
                                <div class="w-28 h-20 rounded-xl border-2 border-dashed border-{{ $accent }}-500/50 overflow-hidden bg-slate-800 flex items-center justify-center">
                                    <img id="preview_tentang_img" src="" class="hidden w-full h-full object-cover">
                                    <i id="placeholder_tentang" class="fa-solid fa-cloud-arrow-up text-{{ $accent }}-500/20 text-xl"></i>
                                </div>
                                <p class="text-[8px] font-black text-{{ $accent }}-500 uppercase tracking-widest">Baru</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <input type="file" name="tentang_image" required class="w-full bg-slate-800 border border-slate-700 px-5 py-3 rounded-2xl text-[10px] text-slate-400 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-{{ $accent }}-600 file:text-white" onchange="previewFile(event, 'preview_tentang_img', 'placeholder_tentang')">
                            <button type="submit" class="w-full bg-slate-800 border border-slate-700 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-{{ $accent }}-600 transition-all shadow-xl">Update Profil Sekarang</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- TABEL LAYANAN --}}
            <div class="glass-panel rounded-[2.5rem] shadow-2xl overflow-hidden mb-10 relative z-10 border border-white/5">
                <div class="overflow-x-auto pb-4">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-slate-800/50 border-b border-slate-700 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                                <th class="px-8 py-6 text-center">Foto</th>
                                <th class="px-8 py-6">Layanan</th>
                                <th class="px-8 py-6 text-center">Status</th>
                                <th class="px-8 py-6">Harga</th>
                                <th class="px-8 py-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50 text-sm relative">
                            @forelse($layanans as $l)
                            <tr class="hover:bg-slate-800/30 transition-all group">
                                <td class="px-8 py-5 flex justify-center">
                                    @if($l->gambar)
                                        <img src="{{ asset('storage/' . $l->gambar) }}" class="w-14 h-14 object-cover rounded-[1rem] border border-slate-700 shadow-md">
                                    @else
                                        <div class="w-14 h-14 bg-slate-800 rounded-[1rem] flex items-center justify-center text-slate-500 border border-slate-700"><i class="fa-solid fa-image"></i></div>
                                    @endif
                                </td>
                                <td class="px-8 py-5">
                                    <p class="font-black text-white uppercase italic tracking-tighter text-base">{{ $l->nama_layanan }}</p>
                                    <p class="text-slate-500 text-[10px] uppercase font-bold mt-1 tracking-widest max-w-[250px] truncate">{{ $l->deskripsi }}</p>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span class="{{ $l->status == 'Aktif' ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'bg-slate-700 text-slate-400 border-slate-600' }} px-4 py-1.5 rounded-full text-[9px] font-black uppercase border shadow-inner">
                                        {{ $l->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 font-black text-{{ $accent }}-400 text-lg tracking-tighter">Rp {{ number_format($l->harga, 0, ',', '.') }}</td>
                                <td class="px-8 py-5 text-right whitespace-nowrap">
                                    <div class="flex justify-end gap-3">
                                        <button onclick='openEditModal({{ $l->id_layanan }}, "{{ $l->nama_layanan }}", "{{ $l->deskripsi }}", {{ $l->harga }}, "{{ asset('storage/' . $l->gambar) }}")' class="w-11 h-11 rounded-full bg-slate-800 text-slate-400 hover:bg-{{ $accent }}-500 hover:text-white border border-slate-700 transition-all flex items-center justify-center shadow-lg hover:scale-110 active:scale-95">
                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </button>
                                        <form action="{{ $isSuper ? route('superadmin.layanan.destroy', $l->id_layanan) : route('admin.layanan.destroy', $l->id_layanan) }}" method="POST" onsubmit="return confirm('Hapus layanan?');" class="m-0">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-11 h-11 rounded-full bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white border border-red-500/20 transition-all flex items-center justify-center shadow-lg hover:scale-110 active:scale-95">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="p-24 text-center opacity-30 italic text-sm">Belum ada layanan terdaftar</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-auto pt-6 pb-2 border-t border-white/5 opacity-40 text-center">
                <p class="text-[10px] font-black uppercase tracking-[0.4em] text-white">© 2026 ROFF.MASTER PANEL CONTROL</p>
            </div>
        </div>
    </main>

    {{-- MODAL EDIT LAYANAN (MODERN 2-COLUMN) --}}
    <div id="modalEdit" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" onclick="closeEditModal()"></div>
        <div class="relative w-full max-w-5xl bg-slate-900 border border-slate-700 rounded-[3rem] shadow-[0_0_100px_rgba(0,0,0,0.8)] overflow-hidden">
            
            <div class="bg-slate-800/50 px-10 py-6 border-b border-slate-700 flex justify-between items-center">
                <h3 class="text-2xl font-black uppercase text-white tracking-tighter italic">Edit <span class="text-{{ $accent }}-500">Layanan</span></h3>
                <button onclick="closeEditModal()" class="w-10 h-10 rounded-full bg-slate-700 text-white hover:bg-red-500 transition-all flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form id="formEdit" action="" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="p-10 grid grid-cols-1 lg:grid-cols-2 gap-12">
                    
                    {{-- Kolom Kiri: Visual & Dasar --}}
                    <div class="space-y-8">
                        <div class="bg-slate-800/30 p-6 rounded-[2rem] border border-white/5">
                            <label class="block text-[10px] font-black uppercase text-{{ $accent }}-500 mb-6 tracking-[0.2em] text-center">Pratinjau Foto Layanan</label>
                            <div class="flex items-center justify-around gap-4 bg-slate-900/50 p-6 rounded-2xl border border-slate-700 shadow-inner">
                                <div class="text-center space-y-3">
                                    <img id="edit_old_preview" src="" class="w-24 h-24 rounded-2xl object-cover border-2 border-slate-700">
                                    <p class="text-[8px] font-black text-slate-500 uppercase">Lama</p>
                                </div>
                                <i class="fa-solid fa-right-long text-slate-700 text-xl"></i>
                                <div class="text-center space-y-3">
                                    <div class="w-24 h-24 rounded-2xl border-2 border-dashed border-{{ $accent }}-500/50 flex items-center justify-center bg-slate-800 overflow-hidden">
                                        <img id="edit_new_preview" src="" class="hidden w-full h-full object-cover">
                                        <i id="preview_edit_placeholder" class="fa-solid fa-cloud-arrow-up text-{{ $accent }}-500/30 text-2xl"></i>
                                    </div>
                                    <p class="text-[8px] font-black text-{{ $accent }}-500 uppercase">Baru</p>
                                </div>
                            </div>
                            <input type="file" name="gambar" class="mt-6 w-full bg-slate-800 border border-slate-700 px-6 py-3 rounded-2xl text-[10px] text-slate-400 file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-{{ $accent }}-600 file:text-white" onchange="previewFile(event, 'edit_new_preview', 'preview_edit_placeholder')">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-500 mb-2 ml-1">Nama Layanan</label>
                                <input type="text" id="edit_nama" name="nama_layanan" required class="w-full bg-slate-800 border border-slate-700 px-6 py-4 rounded-2xl text-sm font-bold text-white outline-none focus:border-{{ $accent }}-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-500 mb-2 ml-1">Harga (Tanpa Titik)</label>
                                <div class="relative">
                                    <span class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-500 font-black text-sm">Rp</span>
                                    <input type="number" id="edit_harga" name="harga" required class="w-full bg-slate-800 border border-slate-700 pl-14 pr-6 py-4 rounded-2xl text-sm font-bold text-white outline-none focus:border-{{ $accent }}-500 transition-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Deskripsi --}}
                    <div class="flex flex-col">
                        <label class="block text-[10px] font-black uppercase text-{{ $accent }}-500 mb-4 ml-1 tracking-[0.2em]">Deskripsi Layanan</label>
                        <textarea id="edit_deskripsi" name="deskripsi" required class="flex-1 w-full bg-slate-800 border border-slate-700 px-8 py-8 rounded-[2.5rem] text-sm font-medium text-slate-300 leading-relaxed outline-none focus:border-{{ $accent }}-500 transition-all resize-none min-h-[350px] custom-scroll" placeholder="Tulis deskripsi detail..."></textarea>
                    </div>
                </div>

                <div class="bg-slate-800/30 px-10 py-8 border-t border-slate-700 flex justify-end">
                    <button type="submit" class="w-full md:w-auto bg-{{ $accent }}-600 hover:bg-{{ $accent }}-500 text-white px-16 py-5 rounded-2xl font-black uppercase text-xs tracking-[0.2em] shadow-xl active:scale-95 transition-all">
                        Update Data Layanan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <div id="modalAdd" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" onclick="closeAddModal()"></div>
        <div class="relative w-full max-w-lg bg-slate-900 border border-slate-700 rounded-[2.5rem] shadow-2xl p-10">
            <h3 class="text-2xl font-black uppercase text-white mb-8 italic">Tambah <span class="text-{{ $accent }}-500">Layanan.</span></h3>
            <form action="{{ $isSuper ? route('superadmin.layanan.store') : route('admin.layanan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div><label class="block text-[10px] font-black uppercase text-slate-500 mb-2">Nama Layanan</label><input type="text" name="nama_layanan" required placeholder="Contoh: Deep Clean" class="w-full bg-slate-800 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white outline-none focus:border-{{ $accent }}-500"></div>
                <div><label class="block text-[10px] font-black uppercase text-slate-500 mb-2">Deskripsi</label><textarea name="deskripsi" required class="w-full bg-slate-800 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white h-24 resize-none outline-none focus:border-{{ $accent }}-500"></textarea></div>
                <div><label class="block text-[10px] font-black uppercase text-slate-500 mb-2">Harga (Tanpa Titik)</label><input type="number" name="harga" required placeholder="30000" class="w-full bg-slate-800 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white outline-none focus:border-{{ $accent }}-500"></div>
                <div><label class="block text-[10px] font-black uppercase text-slate-500 mb-2">Foto</label><input type="file" name="gambar" class="w-full bg-slate-800 border border-slate-700 px-5 py-3 rounded-xl text-xs text-white"></div>
                <div class="pt-4"><button type="submit" class="w-full bg-{{ $accent }}-600 hover:bg-{{ $accent }}-500 text-white py-4 rounded-xl font-black uppercase text-xs tracking-widest shadow-lg">Simpan Layanan</button></div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, nama, deskripsi, harga, currentImage) {
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_deskripsi').value = deskripsi;
            document.getElementById('edit_harga').value = harga;
            document.getElementById('edit_old_preview').src = currentImage;
            document.getElementById('edit_new_preview').classList.add('hidden');
            document.getElementById('preview_edit_placeholder').classList.remove('hidden');
            
            let url = "{{ $isSuper ? route('superadmin.layanan.update', ':id') : route('admin.layanan.update', ':id') }}";
            document.getElementById('formEdit').action = url.replace(':id', id);
            document.getElementById('modalEdit').classList.replace('hidden', 'flex');
        }

        function previewFile(event, previewId, placeholderId) {
            const input = event.target;
            const preview = document.getElementById(previewId);
            const placeholder = document.getElementById(placeholderId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => { 
                    preview.src = e.target.result; 
                    preview.classList.remove('hidden'); 
                    if(placeholder) placeholder.classList.add('hidden'); 
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function closeEditModal() { document.getElementById('modalEdit').classList.replace('flex', 'hidden'); }
        function openAddModal() { document.getElementById('modalAdd').classList.replace('hidden', 'flex'); }
        function closeAddModal() { document.getElementById('modalAdd').classList.replace('flex', 'hidden'); }
    </script>
</body>
</html>