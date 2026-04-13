<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrean Pesanan - ROFF.ADMIN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0f172a; overflow: hidden; }
        
        /* Glassmorphism Panel */
        .glass-panel { 
            background: rgba(30, 41, 59, 0.4); 
            backdrop-filter: blur(12px); 
            border: 1px solid rgba(255, 255, 255, 0.05); 
        }

        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        
        .modal-active { overflow: hidden; }
        
        /* Animasi Modal Detail */
        .detail-modal-bg { transition: all 0.3s ease; }
        .detail-modal-content { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); transform: translateY(20px) scale(0.95); opacity: 0; }
        #detailModal.active .detail-modal-bg { opacity: 1; visibility: visible; }
        #detailModal.active .detail-modal-content { transform: translateY(0) scale(1); opacity: 1; }
    </style>
</head>
<body class="text-slate-200 antialiased flex h-screen overflow-hidden relative">

    {{-- KONTEN UTAMA (TANPA SIDEBAR) --}}
    <main class="flex-1 flex flex-col min-w-0 bg-[#0f172a] relative z-10 h-screen">
        
        {{-- TOP NAVIGATION --}}
        <header class="bg-[#0f172a]/40 backdrop-blur-xl border-b border-white/5 px-6 md:px-12 py-4 flex justify-between items-center shrink-0 z-40">
            
            {{-- LOGO & TOMBOL KEMBALI KIRI --}}
            <div class="flex items-center gap-3 md:gap-4">
                <a href="{{ route('admin.dashboard') }}" class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-slate-800/50 border border-slate-700 text-slate-400 hover:text-blue-400 hover:bg-slate-800 transition-all flex items-center justify-center shadow-sm group active:scale-95" title="Kembali ke Dasbor">
                    <i class="fa-solid fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
                </a>
                
                <h1 class="block font-black text-xl md:text-2xl uppercase tracking-tighter italic text-white leading-tight">
                    ROFF.<span class="text-blue-500">ADMIN</span>
                </h1>
            </div>
            
            {{-- PROFIL KANAN ATAS --}}
            <div class="flex items-center gap-5">
                <div class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-800/50 px-3 md:px-4 py-2 rounded-full border border-slate-700 hidden sm:block shadow-inner">
                    Hari ini: <span class="text-white">{{ now()->format('d M Y') }}</span>
                </div>
                <div class="flex items-center bg-slate-800/40 border border-slate-700 p-1 pr-4 rounded-full shadow-inner">
                    <div class="w-8 h-8 rounded-full overflow-hidden bg-blue-600 flex items-center justify-center text-[10px] font-black text-white border border-slate-700 shadow-xl shadow-blue-500/20">
                        @if(auth()->user()->foto_profil)
                            <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->nama, 0, 2)) }}
                        @endif
                    </div>
                    <div class="ml-3 hidden md:block">
                        <p class="text-[10px] font-black text-white uppercase tracking-widest leading-none">{{ explode(' ', auth()->user()->nama)[0] }}</p>
                        <p class="text-[7px] font-bold text-blue-400/80 uppercase mt-0.5 tracking-tighter">Staff Access</p>
                    </div>
                </div>
            </div>
        </header>

        {{-- AREA SCROLLABLE --}}
        <div class="p-6 md:p-12 flex-1 overflow-y-auto custom-scroll relative">
            
            {{-- Background Glow Biru --}}
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-600/10 blur-[120px] rounded-full pointer-events-none -translate-y-1/2 translate-x-1/4"></div>

            {{-- HEADER HALAMAN --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-8 md:mb-10 relative z-10">
                <div>
                    <div class="inline-flex items-center gap-2 bg-blue-500/10 border border-blue-500/20 px-4 py-1.5 rounded-full mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse shadow-[0_0_10px_rgba(59,130,246,1)]"></span>
                        <p class="text-[8px] md:text-[9px] font-black text-blue-400 uppercase tracking-[0.4em]">Queue Manager</p>
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black text-white tracking-tighter leading-none mb-2">
                        Antrean <span class="italic text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-200">Pesanan.</span>
                    </h1>
                    <p class="text-slate-400 font-medium text-sm">Monitor dan perbarui status pesanan pelanggan secara real-time.</p>
                </div>
            </div>

            {{-- KOTAK STATISTIK --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-8 md:mb-10 relative z-10">
                <div class="glass-panel p-6 md:p-8 rounded-[2rem] flex flex-col justify-center transition-all hover:border-blue-500/50 hover:bg-slate-800/80 group">
                    <p class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 group-hover:text-slate-300">Total Antrean</p>
                    <h3 class="text-3xl md:text-4xl font-black text-white italic tracking-tighter">{{ $semuaPesanan->count() }} <span class="text-[10px] md:text-[12px] font-bold text-slate-500 not-italic uppercase tracking-widest ml-1">Order</span></h3>
                </div>
                <div class="glass-panel relative overflow-hidden p-6 md:p-8 rounded-[2rem] flex flex-col justify-center transition-all border-blue-500/30">
                    <div class="absolute inset-0 bg-blue-500/10"></div>
                    <div class="relative z-10">
                        <p class="text-[9px] md:text-[10px] font-black text-blue-400 uppercase tracking-[0.2em] mb-2">Perlu Konfirmasi</p>
                        <h3 class="text-3xl md:text-4xl font-black text-blue-400 italic tracking-tighter">{{ $semuaPesanan->where('status', 'Menunggu Konfirmasi')->count() }}</h3>
                    </div>
                </div>
                <div class="glass-panel p-6 md:p-8 rounded-[2rem] flex flex-col justify-center transition-all hover:border-emerald-500/50 hover:bg-slate-800/80 group">
                    <p class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 group-hover:text-emerald-400 transition-colors">Sudah Selesai</p>
                    <h3 class="text-3xl md:text-4xl font-black text-emerald-500 italic tracking-tighter">{{ $semuaPesanan->where('status', 'Selesai')->count() }}</h3>
                </div>
            </div>

            {{-- TABEL DATA --}}
            <div class="glass-panel rounded-[2.5rem] border border-white/5 shadow-2xl overflow-hidden mb-10 relative z-10">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px] md:min-w-0">
                        <thead>
                            <tr class="bg-slate-800/50 border-b border-slate-700 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                                <th class="px-6 md:px-8 py-5 md:py-6">ID Order</th>
                                <th class="px-6 md:px-8 py-5 md:py-6">Pelanggan</th>
                                <th class="px-6 md:px-8 py-5 md:py-6">Layanan</th>
                                <th class="px-6 md:px-8 py-5 md:py-6">Pembayaran</th>
                                <th class="px-6 md:px-8 py-5 md:py-6 text-center">Status</th>
                                <th class="px-6 md:px-8 py-5 md:py-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50 text-sm">
                            @forelse($semuaPesanan as $p)
                            @php
                                $layananNama = $p->detail->first()->layanan->nama_layanan ?? 'N/A';
                                $metodeBayar = $p->pembayaran->metode_pembayaran ?? 'Cash';
                            @endphp
                            <tr class="group hover:bg-slate-800/30 transition-all cursor-pointer">
                                <td onclick="showDetail('{{ $p->id_reservasi }}', '{{ addslashes($p->user?->nama ?? 'N/A') }}', '{{ addslashes($layananNama) }}', '{{ $p->jumlah_sepatu }}', '{{ addslashes($p->alamat_jemput ?? 'Ambil Sendiri') }}', '{{ $p->status }}', '{{ $metodeBayar }}')" class="px-6 md:px-8 py-5 md:py-6 font-black text-blue-400 italic uppercase tracking-tighter">
                                    #{{ $p->id_reservasi }}
                                </td>

                                <td onclick="showDetail('{{ $p->id_reservasi }}', '{{ addslashes($p->user?->nama ?? 'N/A') }}', '{{ addslashes($layananNama) }}', '{{ $p->jumlah_sepatu }}', '{{ addslashes($p->alamat_jemput ?? 'Ambil Sendiri') }}', '{{ $p->status }}', '{{ $metodeBayar }}')" class="px-6 md:px-8 py-5 md:py-6">
                                    <p class="font-black text-white uppercase italic leading-tight group-hover:text-blue-400 transition-colors">{{ $p->user?->nama ?? 'N/A' }}</p>
                                    <p class="text-[9px] font-bold text-slate-500 mt-1 uppercase tracking-widest truncate max-w-[150px]">{{ $p->alamat_jemput ?? 'Ambil di Toko' }}</p>
                                </td>

                                <td onclick="showDetail('{{ $p->id_reservasi }}', '{{ addslashes($p->user?->nama ?? 'N/A') }}', '{{ addslashes($layananNama) }}', '{{ $p->jumlah_sepatu }}', '{{ addslashes($p->alamat_jemput ?? 'Ambil Sendiri') }}', '{{ $p->status }}', '{{ $metodeBayar }}')" class="px-6 md:px-8 py-5 md:py-6">
                                    <span class="font-black text-slate-300 uppercase tracking-tighter">{{ $layananNama }}</span>
                                    <span class="block text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-1">{{ $p->jumlah_sepatu }} PASANG</span>
                                </td>

                                <td class="px-6 md:px-8 py-5 md:py-6">
                                    <div class="flex flex-col gap-2 items-start">
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest bg-slate-800 border border-slate-700 px-3 py-1.5 rounded-full">
                                            {{ $metodeBayar }}
                                        </span>
                                        @if($p->pembayaran && $p->pembayaran->bukti_pembayaran)
                                            <button onclick="openImageModal('{{ asset('storage/' . $p->pembayaran->bukti_pembayaran) }}')" 
                                                class="text-blue-400 hover:text-blue-300 transition-colors font-black text-[9px] uppercase tracking-widest flex items-center gap-1.5 px-1 py-0.5">
                                                <i class="fa-solid fa-image text-xs"></i> Cek Bukti
                                            </button>
                                        @endif
                                    </div>
                                </td>

                                <td onclick="showDetail('{{ $p->id_reservasi }}', '{{ addslashes($p->user?->nama ?? 'N/A') }}', '{{ addslashes($layananNama) }}', '{{ $p->jumlah_sepatu }}', '{{ addslashes($p->alamat_jemput ?? 'Ambil Sendiri') }}', '{{ $p->status }}', '{{ $metodeBayar }}')" class="px-6 md:px-8 py-5 md:py-6 text-center">
                                    @php
                                        $statusClass = match($p->status) {
                                            'Selesai' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                            'Dicuci' => 'bg-amber-500/20 text-amber-400 border-amber-500/30',
                                            'Diproses' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                            default => 'bg-slate-700 text-slate-300 border-slate-600',
                                        };
                                    @endphp
                                    <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] border {{ $statusClass }} shadow-sm">
                                        {{ $p->status }}
                                    </span>
                                </td>

                                <td class="px-6 md:px-8 py-5 md:py-6 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <form action="{{ route('admin.reservasi.update', $p->id_reservasi) }}" method="POST">
                                            @csrf
                                            <select name="status" onchange="this.form.submit()" 
                                                class="text-[9px] font-black uppercase bg-slate-800 border border-slate-700 text-white rounded-xl px-3 py-2.5 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 cursor-pointer shadow-sm transition-all hover:bg-slate-700">
                                                <option value="Menunggu Konfirmasi" {{ $p->status == 'Menunggu Konfirmasi' ? 'selected' : '' }}>Konfirmasi</option>
                                                <option value="Diproses" {{ $p->status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                                <option value="Dicuci" {{ $p->status == 'Dicuci' ? 'selected' : '' }}>Dicuci</option>
                                                <option value="Selesai" {{ $p->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                            </select>
                                        </form>

                                        <form action="{{ route('admin.reservasi.destroy', $p->id_reservasi) }}" method="POST" onsubmit="return confirm('Yakin hapus permanen data ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-9 h-9 rounded-full bg-red-500/10 text-red-500 border border-red-500/20 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center active:scale-90">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-24 text-center">
                                    <div class="flex flex-col items-center opacity-30">
                                        <i class="fa-solid fa-clipboard-list text-6xl mb-4 text-slate-400"></i>
                                        <p class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-400">Belum Ada Antrean</p>
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
                <p class="text-[9px] font-black uppercase tracking-[0.2em] w-full text-center text-white">© 2026 ROFF.ADMIN PANEL CONTROL</p>
            </div>

        </div>
    </main>

    {{-- MODAL DETAIL (DARK THEME) --}}
    <div id="detailModal" class="fixed inset-0 z-[100] invisible">
        <div class="detail-modal-bg absolute inset-0 bg-black/80 backdrop-blur-sm opacity-0" onclick="closeDetail()"></div>
        <div class="detail-modal-content relative top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[90%] max-w-lg bg-slate-900 border border-slate-700 rounded-[2.5rem] shadow-2xl p-8 md:p-10 max-h-[90vh] overflow-y-auto custom-scroll">
            
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-black text-white italic uppercase tracking-tight">Detail <span class="text-blue-500">Order.</span></h3>
                <button onclick="closeDetail()" class="w-10 h-10 rounded-full bg-slate-800 text-slate-400 hover:bg-red-500 hover:text-white transition-colors flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <div class="space-y-5">
                <div class="bg-blue-500/10 p-5 md:p-6 rounded-3xl border border-blue-500/20 text-center">
                    <p class="text-[9px] font-black text-blue-400 uppercase tracking-widest mb-1">ID Reservasi</p>
                    <p id="detID" class="text-2xl md:text-3xl font-black text-blue-400 italic tracking-tighter">#000</p>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div class="bg-slate-800/50 p-5 rounded-2xl border border-slate-700">
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Pelanggan</p>
                        <p id="detNama" class="font-black text-white uppercase italic text-sm md:text-base leading-tight">---</p>
                    </div>
                    <div class="bg-slate-800/50 p-5 rounded-2xl border border-slate-700">
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Status Order</p>
                        <p id="detStatus" class="font-black text-emerald-400 uppercase italic text-sm md:text-base">---</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div class="bg-slate-800/50 p-5 rounded-2xl border border-slate-700">
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Layanan</p>
                        <p id="detLayanan" class="font-black text-slate-300 uppercase tracking-tighter text-sm">---</p>
                    </div>
                    <div class="bg-slate-800/50 p-5 rounded-2xl border border-slate-700">
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Jumlah</p>
                        <p id="detJumlah" class="font-black text-slate-300 uppercase tracking-widest text-sm">--- Pasang</p>
                    </div>
                </div>

                <div class="bg-slate-800/50 p-5 rounded-2xl border border-slate-700">
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Alamat Penjemputan</p>
                    <p id="detAlamat" class="text-xs font-bold text-slate-300 leading-relaxed uppercase">---</p>
                </div>

                <div class="pt-6 mt-6 border-t border-slate-800 flex flex-col sm:flex-row justify-between items-center gap-5">
                    <div class="text-center sm:text-left w-full sm:w-auto bg-slate-800/50 px-5 py-3 rounded-2xl border border-slate-700">
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Metode Bayar</p>
                        <p id="detMetode" class="font-black text-blue-400 italic uppercase text-sm">---</p>
                    </div>
                    <button onclick="closeDetail()" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-500 text-white px-10 py-4 rounded-xl font-black uppercase text-[10px] tracking-widest transition-all shadow-lg active:scale-95">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL BUKTI TRANSFER --}}
    <div id="imageModal" class="fixed inset-0 z-[110] hidden bg-black/90 backdrop-blur-md flex items-center justify-center p-4 md:p-6 opacity-0 transition-opacity duration-300">
        <button onclick="closeImageModal()" class="absolute top-6 right-6 text-white bg-slate-800 hover:bg-red-500 transition-colors w-12 h-12 flex items-center justify-center rounded-full shadow-lg border border-slate-700">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <img id="modalImage" src="" alt="Bukti Transfer" class="rounded-[2rem] shadow-2xl border-4 border-slate-800 max-h-[85vh] w-auto object-contain">
    </div>

    <script>
        function showDetail(id, nama, layanan, jumlah, alamat, status, metode) {
            document.getElementById('detID').innerText = '#' + id;
            document.getElementById('detNama').innerText = nama;
            document.getElementById('detLayanan').innerText = layanan;
            document.getElementById('detJumlah').innerText = jumlah + ' PASANG';
            document.getElementById('detAlamat').innerText = alamat;
            document.getElementById('detStatus').innerText = status;
            document.getElementById('detMetode').innerText = metode;

            document.getElementById('detailModal').classList.add('active', 'visible');
            document.getElementById('detailModal').classList.remove('invisible');
            document.body.classList.add('modal-active');
        }

        function closeDetail() {
            document.getElementById('detailModal').classList.remove('active', 'visible');
            setTimeout(() => { document.getElementById('detailModal').classList.add('invisible'); }, 300);
            document.body.classList.remove('modal-active');
        }

        function openImageModal(imgSrc) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modalImg.src = imgSrc;
            modal.classList.remove('hidden');
            setTimeout(() => { modal.classList.add('flex', 'opacity-100'); }, 10);
            document.body.classList.add('modal-active');
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.remove('opacity-100');
            setTimeout(() => { modal.classList.add('hidden', 'flex'); }, 300);
            document.body.classList.remove('modal-active');
        }
    </script>
</body>
</html>