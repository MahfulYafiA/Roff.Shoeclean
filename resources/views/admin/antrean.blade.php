<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrean Pesanan - ROFF.MANAGEMENT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0f172a; overflow: hidden; }
        .glass-panel { 
            background: rgba(30, 41, 59, 0.4); 
            backdrop-filter: blur(16px); 
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05); 
        }
        
        /* Modifikasi Scrollbar agar cantik */
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #475569; }
        
        .modal-active { overflow: hidden; }
        .detail-modal-bg { transition: all 0.3s ease; }
        
        /* Animasi Modal Diperbaiki */
        .detail-modal-content { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); transform: translateY(20px) scale(0.95); opacity: 0; }
        #detailModal.active .detail-modal-bg { opacity: 1; visibility: visible; }
        #detailModal.active .detail-modal-content { transform: translateY(0) scale(1); opacity: 1; }
        
        /* Custom Select Options */
        select option { background-color: #1e293b; color: #f8fafc; font-weight: 800; padding: 12px; }
    </style>
</head>

@php
    // LOGIKA BUNGLON
    $isSuper = auth()->user()->id_role == 1 || auth()->user()->role === 'superadmin';
    $accent = $isSuper ? 'emerald' : 'blue';
@endphp

<body class="text-slate-200 antialiased flex h-screen overflow-hidden relative selection:bg-{{ $accent }}-500 selection:text-white">

    <main class="flex-1 flex flex-col min-w-0 bg-[#0f172a] relative z-10 h-screen">
        
        {{-- TOP NAVIGATION --}}
        <header class="bg-[#0f172a]/80 backdrop-blur-xl px-6 md:px-12 py-4 flex justify-between items-center sticky top-0 z-50 border-b border-white/5">
            <div class="flex items-center gap-3 md:gap-4">
                <a href="{{ $isSuper ? route('superadmin.dashboard') : route('admin.dashboard') }}" class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-slate-800/50 border border-slate-700 text-slate-400 hover:text-{{ $accent }}-400 hover:bg-slate-800 transition-all flex items-center justify-center shadow-sm group active:scale-95">
                    <i class="fa-solid fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <h1 class="block font-black text-xl md:text-2xl uppercase tracking-tighter italic text-white leading-tight">
                    ROFF.<span class="text-{{ $accent }}-500">{{ $isSuper ? 'SUPER' : 'ADMIN' }}</span>
                </h1>
            </div>
            
            <div class="flex items-center gap-5">
                <div class="hidden md:flex items-center bg-slate-800/40 border border-slate-700/50 px-4 py-1.5 rounded-full shadow-inner">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Hari Ini: <span class="text-white">{{ now()->format('d M Y') }}</span></span>
                </div>
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
            {{-- Background Glow --}}
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-{{ $accent }}-600/10 blur-[150px] rounded-full pointer-events-none -translate-y-1/2 translate-x-1/4"></div>

            {{-- HEADER TITLE --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-8 md:mb-10 relative z-10">
                <div>
                    <div class="inline-flex items-center gap-2 bg-{{ $accent }}-500/10 border border-{{ $accent }}-500/20 px-4 py-1.5 rounded-full mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-{{ $accent }}-500 animate-pulse shadow-[0_0_10px_currentColor]"></span>
                        <p class="text-[8px] md:text-[9px] font-black text-{{ $accent }}-400 uppercase tracking-[0.4em]">Queue Manager</p>
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black text-white tracking-tighter leading-none mb-2">
                        Antrean <span class="italic text-transparent bg-clip-text bg-gradient-to-r from-{{ $accent }}-400 to-teal-200">Pesanan</span>
                    </h1>
                    <p class="text-slate-400 font-medium text-sm">Monitor dan perbarui status pesanan pelanggan secara real-time</p>
                </div>
            </div>

            {{-- SUMMARY CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 md:gap-8 mb-10 relative z-10">
                {{-- Card 1: Total --}}
                <div class="glass-panel p-6 md:p-8 rounded-[2rem] flex flex-col justify-center transition-all hover:border-{{ $accent }}-500/50 group relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-{{ $accent }}-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] group-hover:text-{{ $accent }}-400 transition-colors">Total Antrean</p>
                        <i class="fa-solid fa-layer-group text-slate-600 group-hover:text-{{ $accent }}-500 transition-colors text-lg"></i>
                    </div>
                    <h3 class="text-4xl md:text-5xl font-black text-white italic tracking-tighter drop-shadow-md">{{ $semuaPesanan->count() }}</h3>
                </div>

                {{-- Card 2: Baru --}}
                <div class="glass-panel relative overflow-hidden p-6 md:p-8 rounded-[2rem] flex flex-col justify-center transition-all border-amber-500/30 hover:border-amber-500/60 group">
                    <div class="absolute inset-0 bg-amber-500/10 group-hover:bg-amber-500/20 transition-colors"></div>
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-500/30 blur-2xl rounded-full pointer-events-none"></div>
                    <div class="relative z-10">
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-[9px] md:text-[10px] font-black text-amber-400 uppercase tracking-[0.2em]">Baru Diajukan</p>
                            <i class="fa-solid fa-bell text-amber-500/50 group-hover:text-amber-400 animate-pulse text-lg"></i>
                        </div>
                        <h3 class="text-4xl md:text-5xl font-black text-amber-400 italic tracking-tighter drop-shadow-md">{{ $semuaPesanan->whereIn('status', ['Diajukan', 'Menunggu Konfirmasi'])->count() }}</h3>
                    </div>
                </div>

                {{-- Card 3: Selesai --}}
                <div class="glass-panel p-6 md:p-8 rounded-[2rem] flex flex-col justify-center transition-all hover:border-emerald-500/50 group relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] group-hover:text-emerald-400 transition-colors">Sudah Selesai</p>
                        <i class="fa-solid fa-check-double text-slate-600 group-hover:text-emerald-500 transition-colors text-lg"></i>
                    </div>
                    <h3 class="text-4xl md:text-5xl font-black text-emerald-500 italic tracking-tighter drop-shadow-md">{{ $semuaPesanan->where('status', 'Selesai')->count() }}</h3>
                </div>
            </div>

            {{-- TABLE PESANAN --}}
            <div class="glass-panel rounded-[2.5rem] border border-white/5 shadow-2xl overflow-hidden mb-10 relative z-10">
                <div class="overflow-x-auto pb-4">
                    <table class="w-full text-left border-collapse min-w-[900px]">
                        <thead>
                            <tr class="bg-slate-800/50 border-b border-slate-700 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                                <th class="px-6 md:px-8 py-5 md:py-6">ID Order</th>
                                <th class="px-6 md:px-8 py-5 md:py-6">Pelanggan</th>
                                <th class="px-6 md:px-8 py-5 md:py-6">Layanan</th>
                                <th class="px-6 md:px-8 py-5 md:py-6">Pembayaran</th>
                                <th class="px-6 md:px-8 py-5 md:py-6 text-center">Status</th>
                                <th class="px-6 md:px-8 py-5 md:py-6 text-right">Aksi Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50 text-sm">
                            @forelse($semuaPesanan as $p)
                            @php
                                $layananNama = $p->detail->first()->layanan->nama_layanan ?? 'N/A';
                                $metodeBayar = $p->pembayaran->metode_bayar ?? 'Cash';
                                $noTelp = $p->user->no_telp ?? '';
                                
                                $statusClass = match($p->status) {
                                    'Selesai' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.1)]',
                                    'Diajukan', 'Menunggu Konfirmasi' => 'bg-amber-500/10 text-amber-400 border-amber-500/30 shadow-[0_0_15px_rgba(245,158,11,0.1)]',
                                    'Diproses' => 'bg-blue-500/10 text-blue-400 border-blue-500/30 shadow-[0_0_15px_rgba(59,130,246,0.1)]',
                                    'Batalkan', 'Dibatalkan' => 'bg-red-500/10 text-red-400 border-red-500/30',
                                    default => 'bg-slate-800/50 text-slate-300 border-slate-600',
                                };

                                $statusTeks = $p->status == 'Menunggu Konfirmasi' ? 'DIAJUKAN' : $p->status;
                            @endphp
                            <tr class="group hover:bg-slate-800/30 transition-all cursor-pointer">
                                
                                {{-- ID ORDER --}}
                                <td onclick="showDetail('{{ $p->id_reservasi }}', '{{ addslashes($p->user?->nama ?? 'N/A') }}', '{{ addslashes($layananNama) }}', '{{ $p->detail->first()->jumlah ?? 1 }}', '{{ addslashes($p->alamat_jemput ?? 'Ambil Sendiri') }}', '{{ $statusTeks }}', '{{ $metodeBayar }}', '{{ $noTelp }}')" class="px-6 md:px-8 py-5 md:py-6">
                                    <span class="font-black text-{{ $accent }}-400 italic uppercase tracking-tighter text-base">#{{ $p->id_reservasi }}</span>
                                </td>

                                {{-- PELANGGAN --}}
                                <td onclick="showDetail('{{ $p->id_reservasi }}', '{{ addslashes($p->user?->nama ?? 'N/A') }}', '{{ addslashes($layananNama) }}', '{{ $p->detail->first()->jumlah ?? 1 }}', '{{ addslashes($p->alamat_jemput ?? 'Ambil Sendiri') }}', '{{ $statusTeks }}', '{{ $metodeBayar }}', '{{ $noTelp }}')" class="px-6 md:px-8 py-5 md:py-6">
                                    <p class="font-black text-white uppercase italic leading-tight group-hover:text-{{ $accent }}-400 transition-colors">{{ $p->user?->nama ?? 'N/A' }}</p>
                                    <p class="text-[9px] font-bold text-slate-500 mt-1 uppercase tracking-widest truncate max-w-[180px]"><i class="fa-solid fa-location-dot mr-1"></i> {{ $p->alamat_jemput ?? 'Ambil di Toko' }}</p>
                                </td>

                                {{-- LAYANAN --}}
                                <td onclick="showDetail('{{ $p->id_reservasi }}', '{{ addslashes($p->user?->nama ?? 'N/A') }}', '{{ addslashes($layananNama) }}', '{{ $p->detail->first()->jumlah ?? 1 }}', '{{ addslashes($p->alamat_jemput ?? 'Ambil Sendiri') }}', '{{ $statusTeks }}', '{{ $metodeBayar }}', '{{ $noTelp }}')" class="px-6 md:px-8 py-5 md:py-6">
                                    <span class="font-black text-slate-200 uppercase tracking-tighter">{{ $layananNama }}</span>
                                    <span class="block text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-1"><i class="fa-solid fa-shoe-prints mr-1"></i> {{ $p->detail->first()->jumlah ?? 1 }} PASANG</span>
                                </td>

                                {{-- PEMBAYARAN --}}
                                <td class="px-6 md:px-8 py-5 md:py-6">
                                    <div class="flex flex-col gap-2 items-start">
                                        <span class="text-[9px] font-black {{ $p->status_bayar == 'Lunas' ? 'text-emerald-400 bg-emerald-500/10 border-emerald-500/30' : 'text-slate-400 bg-slate-800 border-slate-700' }} uppercase tracking-widest border px-3 py-1.5 rounded-full">
                                            {{ $metodeBayar }} ({{ $p->status_bayar ?? 'Belum Lunas' }})
                                        </span>
                                        @if($p->pembayaran && $p->pembayaran->bukti_pembayaran)
                                            <button onclick="openImageModal('{{ asset('storage/' . $p->pembayaran->bukti_pembayaran) }}')" class="text-blue-400 hover:text-blue-300 transition-colors font-black text-[9px] uppercase tracking-widest flex items-center gap-1.5 px-2 py-1 bg-blue-500/10 rounded-lg border border-blue-500/20 active:scale-95">
                                                <i class="fa-solid fa-image text-xs"></i> Cek Bukti
                                            </button>
                                        @endif
                                    </div>
                                </td>

                                {{-- STATUS BADGE --}}
                                <td onclick="showDetail('{{ $p->id_reservasi }}', '{{ addslashes($p->user?->nama ?? 'N/A') }}', '{{ addslashes($layananNama) }}', '{{ $p->detail->first()->jumlah ?? 1 }}', '{{ addslashes($p->alamat_jemput ?? 'Ambil Sendiri') }}', '{{ $statusTeks }}', '{{ $metodeBayar }}', '{{ $noTelp }}')" class="px-6 md:px-8 py-5 md:py-6 text-center">
                                    <span class="px-4 py-2 rounded-full text-[9px] font-black uppercase tracking-[0.2em] border {{ $statusClass }}">
                                        {{ $statusTeks }}
                                    </span>
                                </td>

                                {{-- AKSI --}}
                                <td class="px-6 md:px-8 py-5 md:py-6 text-right">
                                    <div class="flex items-center justify-end gap-3" onclick="event.stopPropagation()">
                                        <form action="{{ route('admin.reservasi.update', $p->id_reservasi) }}" method="POST" class="relative">
                                            @csrf
                                            <select name="status" onchange="this.form.submit()" class="text-[10px] font-black uppercase bg-slate-800/80 border border-slate-600 text-white rounded-xl pl-4 pr-8 py-2.5 outline-none focus:border-{{ $accent }}-500 focus:ring-1 focus:ring-{{ $accent }}-500 cursor-pointer shadow-sm transition-all hover:bg-slate-700 appearance-none text-center">
                                                <option value="Diajukan" {{ in_array($p->status, ['Diajukan', 'Menunggu Konfirmasi']) ? 'selected' : '' }}>DIAJUKAN</option>
                                                <option value="Diproses" {{ $p->status == 'Diproses' ? 'selected' : '' }}>DIPROSES</option>
                                                <option value="Selesai" {{ $p->status == 'Selesai' ? 'selected' : '' }}>SELESAI</option>
                                                <option value="Batalkan" {{ $p->status == 'Batalkan' ? 'selected' : '' }}>BATALKAN</option>
                                            </select>
                                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                                        </form>
                                        <form action="{{ route('admin.reservasi.destroy', $p->id_reservasi) }}" method="POST" onsubmit="return confirm('Yakin hapus permanen data ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-10 h-10 rounded-xl bg-red-500/10 text-red-500 border border-red-500/20 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center active:scale-90 shadow-sm">
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

            <div class="mt-auto pt-6 pb-2 border-t border-white/5 flex justify-center items-center opacity-40 shrink-0 relative z-10">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] w-full text-center text-white">© 2026 ROFF.MASTER PANEL CONTROL</p>
            </div>
        </div>
    </main>

    {{-- MODAL DETAIL (DIPERBAIKI JADI LEBIH MEWAH) --}}
    <div id="detailModal" class="fixed inset-0 z-[100] invisible flex items-center justify-center p-4">
        <div class="detail-modal-bg absolute inset-0 bg-black/80 backdrop-blur-md opacity-0" onclick="closeDetail()"></div>
        
        <div class="detail-modal-content relative w-full max-w-lg bg-slate-900 border border-slate-700 rounded-[2.5rem] shadow-[0_0_100px_rgba(0,0,0,0.5)] p-8 max-h-[90vh] overflow-y-auto custom-scroll">
            
            {{-- Header Modal --}}
            <div class="flex justify-between items-center mb-8 border-b border-slate-800 pb-6">
                <div>
                    <h3 class="text-2xl font-black text-white italic uppercase tracking-tight leading-none">Detail <span class="text-{{ $accent }}-500">Order</span></h3>
                    <p id="detID" class="text-xs font-bold text-slate-500 mt-2 tracking-widest uppercase">#000</p>
                </div>
                <button onclick="closeDetail()" class="w-10 h-10 rounded-full bg-slate-800 text-slate-400 hover:bg-red-500 hover:text-white transition-all shadow-md flex items-center justify-center border border-slate-700 hover:border-transparent active:scale-90">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            
            <div class="space-y-4">
                
                {{-- Status Bar --}}
                <div class="flex justify-between items-center bg-{{ $accent }}-500/10 p-5 rounded-2xl border border-{{ $accent }}-500/20 shadow-inner">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-{{ $accent }}-500/20 flex items-center justify-center text-{{ $accent }}-400"><i class="fa-solid fa-info text-lg"></i></div>
                        <div>
                            <p class="text-[9px] font-black text-{{ $accent }}-500 uppercase tracking-widest mb-0.5">Status Saat Ini</p>
                            <p id="detStatus" class="font-black text-white uppercase italic text-lg leading-none drop-shadow-md">---</p>
                        </div>
                    </div>
                </div>

                {{-- Info Pelanggan --}}
                <div class="bg-slate-800/40 p-5 rounded-2xl border border-slate-700/50 flex flex-col gap-4">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center text-slate-400 shrink-0"><i class="fa-solid fa-user"></i></div>
                        <div class="flex-1">
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Identitas Pelanggan</p>
                            <p id="detNama" class="font-black text-white uppercase italic text-base leading-none mb-1.5">---</p>
                            <p id="detNoTelp" class="text-[10px] font-bold text-slate-400 tracking-widest font-mono">---</p>
                        </div>
                    </div>
                    <a id="btnWA" href="#" target="_blank" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-3.5 rounded-xl font-black uppercase text-[10px] tracking-widest transition-all shadow-lg shadow-emerald-900/50 active:scale-95 flex items-center justify-center gap-2 border border-emerald-500/50">
                        <i class="fa-brands fa-whatsapp text-base"></i> Chat WhatsApp Pelanggan
                    </a>
                </div>

                {{-- Grid Info Layanan & Jumlah --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-800/40 p-5 rounded-2xl border border-slate-700/50">
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2"><i class="fa-solid fa-jug-detergent mr-1"></i> Layanan</p>
                        <p id="detLayanan" class="font-black text-white uppercase tracking-tighter text-sm leading-tight">---</p>
                    </div>
                    <div class="bg-slate-800/40 p-5 rounded-2xl border border-slate-700/50">
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2"><i class="fa-solid fa-shoe-prints mr-1"></i> Quantity</p>
                        <p id="detJumlah" class="font-black text-white uppercase tracking-widest text-sm leading-tight">--- Pasang</p>
                    </div>
                </div>

                {{-- Info Alamat & Pembayaran --}}
                <div class="bg-slate-800/40 p-5 rounded-2xl border border-slate-700/50 space-y-4">
                    <div>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1"><i class="fa-solid fa-location-dot mr-1"></i> Titik Lokasi / Alamat</p>
                        <p id="detAlamat" class="text-xs font-bold text-slate-300 leading-relaxed uppercase">---</p>
                    </div>
                    <div class="h-px w-full bg-slate-700/50"></div>
                    <div>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1"><i class="fa-solid fa-wallet mr-1"></i> Metode Transaksi</p>
                        <p id="detMetode" class="font-black text-amber-400 italic uppercase text-sm">---</p>
                    </div>
                </div>

            </div>
            
            <div class="mt-8 pt-6 border-t border-slate-800">
                <button onclick="closeDetail()" class="w-full bg-slate-800 hover:bg-slate-700 border border-slate-700 text-white px-6 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest transition-all shadow-lg active:scale-95">Tutup Detail</button>
            </div>
        </div>
    </div>

    {{-- MODAL BUKTI TRANSFER --}}
    <div id="imageModal" class="fixed inset-0 z-[110] hidden bg-black/90 backdrop-blur-md flex items-center justify-center p-4 md:p-6 opacity-0 transition-opacity duration-300">
        <button onclick="closeImageModal()" class="absolute top-6 right-6 text-white bg-slate-800 hover:bg-red-500 transition-colors w-12 h-12 flex items-center justify-center rounded-full shadow-lg border border-slate-700">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <img id="modalImage" src="" alt="Bukti Transfer" class="rounded-[2rem] shadow-[0_0_50px_rgba(0,0,0,0.8)] border-4 border-slate-800 max-h-[85vh] w-auto object-contain">
    </div>

    <script>
        function showDetail(id, nama, layanan, jumlah, alamat, status, metode, noTelp) {
            document.getElementById('detID').innerText = 'ORD-' + id.padStart(4, '0');
            document.getElementById('detNama').innerText = nama;
            document.getElementById('detLayanan').innerText = layanan;
            document.getElementById('detJumlah').innerText = jumlah + ' PASANG';
            document.getElementById('detAlamat').innerText = alamat;
            document.getElementById('detStatus').innerText = status;
            document.getElementById('detMetode').innerText = metode;
            
            // Format Nomor Telp
            document.getElementById('detNoTelp').innerText = noTelp ? noTelp : 'TIDAK ADA NOMOR HP';

            // Link WA
            let waLink = "#";
            if(noTelp) {
                let formattedNumber = noTelp.replace(/^0/, '62');
                waLink = "https://wa.me/" + formattedNumber + "?text=Halo%20Kak%20" + encodeURIComponent(nama) + ",%20saya%20Admin%20dari%20ROFF.SHOECLEAN...%20";
            }
            document.getElementById('btnWA').href = waLink;

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