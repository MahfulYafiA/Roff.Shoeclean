<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Layanan - Admin ROFF</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fafafa; }
        .luxury-shadow { box-shadow: 0 24px 48px -12px rgba(15, 23, 42, 0.05); }
    </style>
</head>
<body class="text-slate-900 antialiased min-h-screen flex flex-col md:flex-row">

    {{-- SIDEBAR --}}
    <aside class="hidden md:flex w-72 bg-white border-r border-slate-200 p-8 flex-col shrink-0 h-screen sticky top-0">
        <a href="{{ route('admin.dashboard') }}" class="font-black text-2xl tracking-tighter mb-12 block shrink-0">
            ROFF.<span class="text-blue-600">ADMIN</span>
        </a>
        <nav class="space-y-3 flex-grow overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 px-6 py-4 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-2xl font-bold transition-all text-sm">Dashboard</a>
            {{-- 🚨 RUTE DIPERBARUI KE admin.layanan.index 🚨 --}}
            <a href="{{ route('admin.layanan.index') }}" class="flex items-center gap-4 px-6 py-4 text-blue-600 bg-blue-50 rounded-2xl font-bold transition-all text-sm">Katalog Layanan</a>
            <a href="{{ route('admin.users') }}" class="flex items-center gap-4 px-6 py-4 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-2xl font-bold transition-all text-sm">Data Pelanggan</a>
        </nav>
        <form method="POST" action="{{ route('logout') }}" class="pt-8 border-t border-slate-100 m-0">
            @csrf
            <button type="submit" class="text-red-500 font-black uppercase text-[10px] tracking-widest hover:text-red-700 transition-colors">Logout</button>
        </form>
    </aside>

    {{-- KONTEN UTAMA --}}
    <main class="flex-grow p-6 md:p-12 min-w-0">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 md:mb-12 gap-6">
            <div class="shrink-0">
                <span class="text-blue-600 font-black uppercase tracking-[0.3em] text-[9px] md:text-[10px] mb-1 md:mb-2 block">Modul Layanan</span>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight leading-tight">Katalog <span class="text-blue-600 italic">Layanan.</span></h1>
            </div>
            <button onclick="openAddModal()" class="w-full md:w-auto bg-slate-900 text-white px-8 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-blue-600 transition-all shadow-lg active:scale-95 whitespace-nowrap">
                + Tambah Layanan
            </button>
        </div>

        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-600 p-4 md:p-5 rounded-2xl font-bold mb-8 border border-emerald-100 text-xs md:text-sm flex items-center gap-3">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 text-red-600 p-4 md:p-5 rounded-2xl font-bold mb-8 border border-red-100 text-xs md:text-sm flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        {{-- Tabel Container --}}
        <div class="bg-white rounded-[1.5rem] md:rounded-[2.5rem] border border-slate-200 overflow-hidden luxury-shadow">
            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[700px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 md:px-8 py-4 md:py-6 text-[9px] md:text-[10px] font-black uppercase tracking-widest text-slate-400">Nama Layanan</th>
                            <th class="px-6 md:px-8 py-4 md:py-6 text-[9px] md:text-[10px] font-black uppercase tracking-widest text-slate-400">Deskripsi</th>
                            <th class="px-6 md:px-8 py-4 md:py-6 text-[9px] md:text-[10px] font-black uppercase tracking-widest text-slate-400">Harga</th>
                            <th class="px-6 md:px-8 py-4 md:py-6 text-[9px] md:text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs md:text-sm">
                        @forelse($layanans as $l)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 md:px-8 py-4 md:py-6 font-bold text-slate-900 uppercase italic">{{ $l->nama_layanan }}</td>
                            <td class="px-6 md:px-8 py-4 md:py-6 text-slate-500 max-w-xs truncate">{{ $l->deskripsi }}</td>
                            <td class="px-6 md:px-8 py-4 md:py-6 font-black text-blue-600 text-base">Rp {{ number_format($l->harga, 0, ',', '.') }}</td>
                            <td class="px-6 md:px-8 py-4 md:py-6 text-right whitespace-nowrap">
                                <div class="flex justify-end gap-2">
                                    {{-- Tombol Edit --}}
                                    <button onclick="openEditModal('{{ $l->id_layanan }}', '{{ $l->nama_layanan }}', '{{ $l->harga }}', '{{ $l->deskripsi }}')" 
                                        class="text-blue-600 hover:bg-blue-50 w-8 h-8 rounded-lg flex items-center justify-center transition-all">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    
                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('admin.layanan.destroy', $l->id_layanan) }}" method="POST" onsubmit="return confirm('Hapus layanan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:bg-red-50 w-8 h-8 rounded-lg flex items-center justify-center transition-all">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-16 text-center text-slate-400 font-medium italic">Belum ada data layanan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    {{-- MODAL (SATU MODAL UNTUK TAMBAH & EDIT) --}}
    <div id="modalLayanan" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="toggleModal()"></div>
        <div class="relative w-full max-w-lg bg-white rounded-[2rem] md:rounded-[3rem] p-8 md:p-12 luxury-shadow">
            <h2 id="modalTitle" class="text-xl md:text-2xl font-black mb-8 uppercase tracking-tight italic">Tambah <span class="text-blue-600">Layanan</span></h2>
            
            <form id="layananForm" method="POST" class="space-y-6 m-0">
                @csrf
                <div id="methodField"></div> {{-- Tempat untuk PUT method saat edit --}}
                
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Layanan</label>
                    <input type="text" name="nama_layanan" id="inputNama" required placeholder="Contoh: Deep Cleaning" 
                        class="w-full bg-slate-50 border border-slate-200 p-4 rounded-2xl focus:border-blue-500 outline-none font-bold text-sm transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Harga (Rp)</label>
                    <input type="number" name="harga" id="inputHarga" required placeholder="0" 
                        class="w-full bg-slate-50 border border-slate-200 p-4 rounded-2xl focus:border-blue-500 outline-none font-bold text-sm transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Deskripsi Singkat</label>
                    <textarea name="deskripsi" id="inputDeskripsi" rows="3" required placeholder="Jelaskan detail layanan..." 
                        class="w-full bg-slate-50 border border-slate-200 p-4 rounded-2xl focus:border-blue-500 outline-none font-bold text-sm resize-none transition-all"></textarea>
                </div>
                
                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="toggleModal()" class="flex-grow bg-slate-100 text-slate-500 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-slate-200 transition-all">Batal</button>
                    <button type="submit" class="flex-grow bg-blue-600 text-white py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-slate-900 transition-all shadow-lg">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalLayanan');
        const form = document.getElementById('layananForm');
        const modalTitle = document.getElementById('modalTitle');
        const methodField = document.getElementById('methodField');

        // Fungsi Buka Modal Tambah
        function openAddModal() {
            modalTitle.innerHTML = 'Tambah <span class="text-blue-600">Layanan</span>';
            form.action = "{{ route('admin.layanan.store') }}";
            methodField.innerHTML = ''; // Kosongkan method field (POST biasa)
            
            // Reset Input
            document.getElementById('inputNama').value = '';
            document.getElementById('inputHarga').value = '';
            document.getElementById('inputDeskripsi').value = '';
            
            toggleModal();
        }

        // Fungsi Buka Modal Edit
        function openEditModal(id, nama, harga, deskripsi) {
            modalTitle.innerHTML = 'Edit <span class="text-blue-600">Layanan</span>';
            form.action = `/admin/layanan/${id}`; // Arahkan ke update route
            
            // 🚨 DIPERBARUI: Menggunakan format HTML murni untuk spoofing method PUT di Laravel
            methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">'; 
            
            // Isi Data
            document.getElementById('inputNama').value = nama;
            document.getElementById('inputHarga').value = harga;
            document.getElementById('inputDeskripsi').value = deskripsi;
            
            toggleModal();
        }

        function toggleModal() {
            modal.classList.toggle('hidden');
            document.body.style.overflow = modal.classList.contains('hidden') ? 'auto' : 'hidden';
        }
    </script>
</body>
</html>