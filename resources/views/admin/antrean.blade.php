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
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .sidebar-active { background: #2563eb; color: white !important; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3); }
        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .modal-active { overflow: hidden; }
        
        /* Animasi Modal Detail */
        .detail-modal-bg { transition: all 0.3s ease; }
        .detail-modal-content { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); transform: translateY(20px) scale(0.95); opacity: 0; }
        #detailModal.active .detail-modal-bg { opacity: 1; visibility: visible; }
        #detailModal.active .detail-modal-content { transform: translateY(0) scale(1); opacity: 1; }
    </style>
</head>
<body class="text-slate-900 antialiased flex h-screen overflow-hidden">

    {{-- SIDEBAR ADMIN --}}
    <aside class="w-72 bg-white border-r border-slate-200 flex flex-col h-full shrink-0 z-50">
        <div class="p-8 border-b border-slate-50">
            <h1 class="font-black text-2xl uppercase tracking-tighter italic text-slate-900">
                ROFF.<span class="text-blue-600">ADMIN</span>
            </h1>
        </div>

        <nav class="flex-1 px-4 space-y-2 mt-4">
            <a href="{{ route('admin.antrean') }}" class="flex items-center gap-4 sidebar-active px-6 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all">
                <i class="fa-solid fa-clipboard-list text-lg"></i>
                Antrean Pesanan
            </a>
            
            <div class="pt-6 border-t border-slate-100 mt-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 text-slate-400 hover:text-blue-600 px-6 py-4 rounded-2xl font-bold text-[11px] uppercase tracking-widest transition-all group">
                    <i class="fa-solid fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
                    Dasbor Utama
                </a>
            </div>
        </nav>
        {{-- Tombol Log Out telah dihapus dari sini --}}
    </aside>

    {{-- KONTEN UTAMA --}}
    <main class="flex-1 flex flex-col min-w-0 bg-[#f8fafc]">
        <header class="bg-white border-b border-slate-200 px-10 py-6 flex justify-between items-center shrink-0">
            <div>
                <h2 class="text-xl font-black text-slate-900 tracking-tight uppercase italic">Manajemen <span class="text-blue-600">Antrean.</span></h2>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-[0.2em] mt-1 italic">Monitor & Update Status Pesanan</p>
            </div>
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-4 py-2 rounded-full border border-slate-100">
                Hari ini: <span class="text-slate-900">{{ now()->format('d M Y') }}</span>
            </div>
        </header>

        <div class="p-10 flex-1 overflow-y-auto custom-scroll">
            
            {{-- KOTAK STATISTIK --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm transition-all hover:border-blue-200">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Antrean</p>
                    <h3 class="text-3xl font-black text-slate-900 italic">{{ $semuaPesanan->count() }} <span class="text-xs font-medium text-slate-400 not-italic">Order</span></h3>
                </div>
                <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm transition-all hover:border-blue-200">
                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-[0.2em] mb-1">Perlu Konfirmasi</p>
                    <h3 class="text-3xl font-black text-blue-600 italic">{{ $semuaPesanan->where('status', 'Menunggu Konfirmasi')->count() }}</h3>
                </div>
                <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm transition-all hover:border-emerald-200">
                    <p class="text-[10px] font-black text-emerald-400 uppercase tracking-[0.2em] mb-1">Sudah Selesai</p>
                    <h3 class="text-3xl font-black text-emerald-600 italic">{{ $semuaPesanan->where('status', 'Selesai')->count() }}</h3>
                </div>
            </div>

            {{-- TABEL DATA --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                <th class="px-8 py-6">ID Order</th>
                                <th class="px-8 py-6">Pelanggan</th>
                                <th class="px-8 py-6">Layanan</th>
                                <th class="px-8 py-6">Pembayaran</th>
                                <th class="px-8 py-6 text-center">Status</th>
                                <th class="px-8 py-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($semuaPesanan as $p)
                            <tr class="group hover:bg-blue-50/30 transition-all cursor-pointer">
                                <td onclick="showDetail('{{ $p->id_reservasi }}', '{{ $p->user?->nama ?? 'PELANGGAN TERHAPUS' }}', '{{ $p->detail->layanan->nama_layanan ?? 'N/A' }}', '{{ $p->jumlah_sepatu }}', '{{ $p->alamat_jemput ?? 'Ambil Sendiri' }}', '{{ $p->status }}', '{{ $p->pembayaran->metode_pembayaran ?? 'Cash' }}')" class="px-8 py-6 font-black text-blue-600 italic uppercase">#{{ $p->id_reservasi }}</td>

                                <td onclick="showDetail('{{ $p->id_reservasi }}', '{{ $p->user?->nama ?? 'PELANGGAN TERHAPUS' }}', '{{ $p->detail->layanan->nama_layanan ?? 'N/A' }}', '{{ $p->jumlah_sepatu }}', '{{ $p->alamat_jemput ?? 'Ambil Sendiri' }}', '{{ $p->status }}', '{{ $p->pembayaran->metode_pembayaran ?? 'Cash' }}')" class="px-8 py-6">
                                    <p class="font-black text-slate-900 uppercase italic leading-tight">{{ $p->user?->nama ?? 'PELANGGAN TERHAPUS' }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-tighter">{{ $p->alamat_jemput ?? 'Ambil di Toko' }}</p>
                                </td>

                                <td onclick="showDetail('{{ $p->id_reservasi }}', '{{ $p->user?->nama ?? 'PELANGGAN TERHAPUS' }}', '{{ $p->detail->layanan->nama_layanan ?? 'N/A' }}', '{{ $p->jumlah_sepatu }}', '{{ $p->alamat_jemput ?? 'Ambil Sendiri' }}', '{{ $p->status }}', '{{ $p->pembayaran->metode_pembayaran ?? 'Cash' }}')" class="px-8 py-6 italic font-bold text-slate-600">
                                    <span class="text-blue-600">{{ $p->detail->layanan->nama_layanan ?? 'N/A' }}</span>
                                    <span class="block text-[9px] not-italic text-slate-400 font-black uppercase tracking-widest mt-1">{{ $p->jumlah_sepatu }} PASANG</span>
                                </td>

                                <td class="px-8 py-6">
                                    <div class="flex flex-col gap-2">
                                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest bg-slate-100 px-2 py-1 rounded-md w-max">
                                            {{ $p->pembayaran->metode_pembayaran ?? 'Cash' }}
                                        </span>
                                        @if($p->pembayaran && $p->pembayaran->bukti_pembayaran)
                                            <button onclick="openImageModal('{{ asset('storage/' . $p->pembayaran->bukti_pembayaran) }}')" 
                                                class="text-blue-600 hover:text-blue-800 transition-colors font-black text-[9px] uppercase tracking-widest flex items-center gap-1">
                                                <i class="fa-solid fa-image"></i> Lihat Bukti
                                            </button>
                                        @endif
                                    </div>
                                </td>

                                <td onclick="showDetail('{{ $p->id_reservasi }}', '{{ $p->user?->nama ?? 'PELANGGAN TERHAPUS' }}', '{{ $p->detail->layanan->nama_layanan ?? 'N/A' }}', '{{ $p->jumlah_sepatu }}', '{{ $p->alamat_jemput ?? 'Ambil Sendiri' }}', '{{ $p->status }}', '{{ $p->pembayaran->metode_pembayaran ?? 'Cash' }}')" class="px-8 py-6 text-center">
                                    @php
                                        $statusClass = match($p->status) {
                                            'Selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'Dicuci' => 'bg-amber-50 text-amber-600 border-amber-100',
                                            'Diproses' => 'bg-blue-50 text-blue-600 border-blue-100',
                                            default => 'bg-slate-50 text-slate-400 border-slate-100',
                                        };
                                    @endphp
                                    <span class="px-4 py-2 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $statusClass }}">
                                        {{ $p->status }}
                                    </span>
                                </td>

                                <td class="px-8 py-6">
                                    <div class="flex items-center justify-center gap-3">
                                        <form action="{{ route('admin.reservasi.update', $p->id_reservasi) }}" method="POST">
                                            @csrf
                                            <select name="status" onchange="this.form.submit()" 
                                                class="text-[9px] font-black uppercase tracking-[0.1em] bg-white border border-slate-200 rounded-xl px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer shadow-sm transition-all hover:border-blue-400">
                                                <option value="Menunggu Konfirmasi" {{ $p->status == 'Menunggu Konfirmasi' ? 'selected' : '' }}>Konfirmasi</option>
                                                <option value="Diproses" {{ $p->status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                                <option value="Dicuci" {{ $p->status == 'Dicuci' ? 'selected' : '' }}>Dicuci</option>
                                                <option value="Selesai" {{ $p->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                            </select>
                                        </form>

                                        <form action="{{ route('admin.reservasi.destroy', $p->id_reservasi) }}" method="POST" onsubmit="return confirm('Yakin hapus permanen data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-300 hover:text-red-600 transition-all hover:scale-110">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-32 text-center text-slate-300 italic">Antrean Kosong.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    {{-- MODAL DETAIL PESANAN --}}
    <div id="detailModal" class="fixed inset-0 z-[100] invisible">
        <div class="detail-modal-bg absolute inset-0 bg-slate-900/60 backdrop-blur-sm opacity-0" onclick="closeDetail()"></div>
        <div class="detail-modal-content relative top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg bg-white rounded-[2.5rem] shadow-2xl p-10">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-black text-slate-900 italic uppercase">Detail <span class="text-blue-600">Pesanan.</span></h3>
                <button onclick="closeDetail()" class="text-slate-400 hover:text-red-500 transition-colors"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            
            <div class="space-y-6">
                <div class="bg-blue-50 p-6 rounded-3xl border border-blue-100">
                    <p class="text-[9px] font-black text-blue-400 uppercase tracking-widest mb-1">ID Reservasi</p>
                    <p id="detID" class="text-xl font-black text-blue-700 italic">#000</p>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Pelanggan</p>
                        <p id="detNama" class="font-black text-slate-800 uppercase italic leading-tight">---</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Status</p>
                        <p id="detStatus" class="font-black text-blue-600 uppercase italic">---</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Layanan</p>
                        <p id="detLayanan" class="font-black text-slate-700 italic">---</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Jumlah</p>
                        <p id="detJumlah" class="font-black text-slate-700 italic">--- Pasang</p>
                    </div>
                </div>

                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Alamat Penjemputan</p>
                    <p id="detAlamat" class="text-xs font-bold text-slate-600 leading-relaxed uppercase tracking-tighter">---</p>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-between items-center">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Metode Pembayaran</p>
                        <p id="detMetode" class="font-black text-slate-800 italic uppercase">---</p>
                    </div>
                    <button onclick="closeDetail()" class="bg-slate-900 text-white px-8 py-3 rounded-xl font-black uppercase text-[10px] tracking-widest hover:bg-blue-600 transition-all active:scale-95">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL BUKTI TRANSFER --}}
    <div id="imageModal" class="fixed inset-0 z-[110] hidden bg-slate-900/95 backdrop-blur-md flex items-center justify-center p-6 opacity-0 transition-opacity duration-300">
        <button onclick="closeImageModal()" class="absolute top-8 right-8 text-white hover:text-red-400 transition-colors bg-white/10 p-3 rounded-full">
            <i class="fa-solid fa-xmark text-2xl"></i>
        </button>
        <div class="w-full max-w-2xl max-h-full flex flex-col items-center">
            <img id="modalImage" src="" alt="Bukti Transfer" class="rounded-[2rem] shadow-2xl border-2 border-white/20 max-h-[85vh] object-contain">
        </div>
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
            setTimeout(() => {
                document.getElementById('detailModal').classList.add('invisible');
            }, 300);
            document.body.classList.remove('modal-active');
        }

        function openImageModal(imgSrc) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modalImg.src = imgSrc;
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.add('flex', 'opacity-100');
            }, 10);
            document.body.classList.add('modal-active');
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.remove('opacity-100');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
            document.body.classList.remove('modal-active');
        }
    </script>

</body>
</html>