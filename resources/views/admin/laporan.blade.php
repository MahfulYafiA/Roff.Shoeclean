<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan - ROFF.MANAGEMENT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0f172a; overflow: hidden; }
        
        /* Glassmorphism Panel */
        .glass-panel { 
            background: rgba(30, 41, 59, 0.4); 
            backdrop-filter: blur(16px); 
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05); 
        }

        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* Tampilan khusus saat mencetak (Print) */
        @media print {
            .no-print { display: none !important; }
            main { margin: 0 !important; padding: 0 !important; width: 100% !important; max-width: 100% !important; background: white !important;}
            body { background: white !important; overflow: auto !important; }
            .glass-panel { background: white !important; border: 1px solid #e2e8f0 !important; box-shadow: none !important; backdrop-filter: none !important; }
            .print-text-dark { color: #0f172a !important; }
            .print-text-gray { color: #64748b !important; }
            .print-border-light { border-color: #e2e8f0 !important; }
            th { color: #64748b !important; background-color: #f8fafc !important; }
            td { color: #0f172a !important; border-bottom: 1px solid #f1f5f9 !important; }
            .print-bg-light { background: #f8fafc !important; }
            .print-hide-icon { display: none !important; } /* Sembunyikan ikon dekoratif saat print */
        }
    </style>
</head>

@php
    // LOGIKA BUNGLON (Otomatis menyesuaikan warna tema)
    $isSuper = auth()->user()->id_role == 1 || auth()->user()->role === 'superadmin';
    $accent = $isSuper ? 'emerald' : 'blue';
@endphp

<body class="text-slate-200 antialiased flex h-screen overflow-hidden relative selection:bg-{{ $accent }}-500 selection:text-white">

    {{-- KONTEN UTAMA --}}
    <main class="flex-1 flex flex-col h-screen min-w-0 bg-[#0f172a] relative z-10">
        
        {{-- TOP NAVIGATION --}}
        <header class="bg-[#0f172a]/80 backdrop-blur-xl border-b border-white/5 px-6 md:px-12 py-4 flex justify-between items-center shrink-0 z-40 no-print sticky top-0">
            
            {{-- LOGO & TOMBOL KEMBALI KIRI --}}
            <div class="flex items-center gap-3 md:gap-4">
                <a href="{{ $isSuper ? route('superadmin.dashboard') : route('admin.dashboard') }}" class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-slate-800/50 border border-slate-700 text-slate-400 hover:text-{{ $accent }}-400 hover:bg-slate-800 transition-all flex items-center justify-center shadow-sm group active:scale-95" title="Kembali ke Dasbor">
                    <i class="fa-solid fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
                </a>
                
                <h1 class="block font-black text-xl md:text-2xl uppercase tracking-tighter italic text-white leading-tight">
                    ROFF.<span class="text-{{ $accent }}-500">{{ $isSuper ? 'SUPER' : 'ADMIN' }}</span>
                </h1>
            </div>
            
            {{-- PROFIL KANAN ATAS --}}
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

        {{-- AREA SCROLLABLE --}}
        <div class="p-6 md:p-12 flex-1 overflow-y-auto custom-scroll relative">
            
            {{-- Background Glow Dinamis --}}
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-{{ $accent }}-600/10 blur-[150px] rounded-full pointer-events-none -translate-y-1/2 translate-x-1/4 no-print"></div>

            {{-- HEADER HALAMAN & TOTAL OMSET --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8 md:mb-10 relative z-10">
                <div>
                    <div class="inline-flex items-center gap-2 bg-{{ $accent }}-500/10 border-{{ $accent }}-500/20 border px-4 py-1.5 rounded-full mb-4 no-print">
                        <span class="w-1.5 h-1.5 rounded-full bg-{{ $accent }}-500 animate-pulse shadow-[0_0_10px_currentColor]"></span>
                        <p class="text-[8px] md:text-[9px] font-black text-{{ $accent }}-400 uppercase tracking-[0.4em]">Real-time Data</p>
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black text-white print-text-dark tracking-tighter leading-none mb-2">
                        Laporan <span class="italic text-transparent bg-clip-text bg-gradient-to-r from-{{ $accent }}-400 to-teal-200 print-text-dark">Omset</span>
                    </h1>
                    <p class="text-slate-400 print-text-gray font-medium text-sm">Monitoring pendapatan resmi sistem ROFF.SHOECLEAN</p>
                </div>

                {{-- KARTU TOTAL OMSET (DIPERCANTIK) --}}
                <div class="glass-panel w-full md:w-auto px-10 py-8 rounded-[2rem] text-right shadow-2xl relative overflow-hidden shrink-0 border border-{{ $accent }}-500/20 print-border-light print-bg-light group">
                    <div class="absolute inset-0 bg-gradient-to-br from-{{ $accent }}-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 no-print"></div>
                    <div class="absolute top-0 right-0 w-32 h-32 bg-{{ $accent }}-500/20 rounded-full -translate-y-1/2 translate-x-1/2 blur-2xl no-print"></div>
                    <i class="fa-solid fa-wallet absolute -left-4 -bottom-4 text-8xl text-white/5 no-print -rotate-12 group-hover:rotate-0 group-hover:scale-110 transition-transform duration-500"></i>
                    
                    <p class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-1 relative z-10 group-hover:text-{{ $accent }}-400 transition-colors">Total Omset Keseluruhan</p>
                    <h3 class="text-3xl md:text-5xl font-black text-white print-text-dark tracking-tighter relative z-10 italic drop-shadow-md">
                        <span class="text-xl md:text-3xl text-{{ $accent }}-500 mr-1 font-bold">Rp</span>{{ number_format($totalOmzet ?? 0, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
            
            {{-- FILTER TANGGAL --}}
            <div class="glass-panel rounded-[2rem] p-6 md:p-8 mb-8 relative z-10 no-print border border-white/5 shadow-xl group">
                <form action="{{ url()->current() }}" method="GET" class="flex flex-col md:flex-row items-end gap-5">
                    <div class="w-full md:w-auto flex-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 group-hover:text-{{ $accent }}-400 transition-colors"><i class="fa-regular fa-calendar-minus mr-1"></i> Dari Tanggal</label>
                        <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai', $tgl_mulai ?? '') }}" class="w-full bg-slate-800/50 border border-slate-700 text-white rounded-xl px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-{{ $accent }}-500 focus:border-{{ $accent }}-500 outline-none transition-all [color-scheme:dark] shadow-inner">
                    </div>
                    <div class="w-full md:w-auto flex-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 group-hover:text-{{ $accent }}-400 transition-colors"><i class="fa-regular fa-calendar-plus mr-1"></i> Sampai Tanggal</label>
                        <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai', $tgl_selesai ?? '') }}" class="w-full bg-slate-800/50 border border-slate-700 text-white rounded-xl px-5 py-4 text-sm font-bold focus:ring-2 focus:ring-{{ $accent }}-500 focus:border-{{ $accent }}-500 outline-none transition-all [color-scheme:dark] shadow-inner">
                    </div>
                    <div class="w-full md:w-auto flex gap-3 h-full">
                        <button type="submit" class="w-full md:w-auto bg-{{ $accent }}-600 hover:bg-{{ $accent }}-500 text-white px-8 py-4 rounded-xl font-black text-[10px] md:text-xs uppercase tracking-widest transition-all shadow-lg shadow-{{ $accent }}-500/20 active:scale-95 flex items-center justify-center">
                            <i class="fa-solid fa-filter mr-2"></i> Terapkan
                        </button>
                        
                        {{-- Tombol Reset hanya muncul jika filter sedang aktif --}}
                        @if(request('tgl_mulai') || request('tgl_selesai'))
                        <a href="{{ url()->current() }}" class="w-full md:w-auto bg-slate-700 hover:bg-red-500 border border-slate-600 hover:border-red-500 text-white px-6 py-4 rounded-xl font-black text-[10px] md:text-xs uppercase tracking-widest transition-all flex items-center justify-center shadow-lg active:scale-95" title="Hapus Filter">
                            <i class="fa-solid fa-rotate-right"></i>
                        </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- TABEL DATA --}}
            <div class="glass-panel rounded-[2.5rem] border border-white/5 shadow-2xl overflow-hidden mb-8 md:mb-10 relative z-10 print-border-light">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px] md:min-w-0">
                        <thead>
                            <tr class="bg-slate-800/50 print-bg-light border-b border-slate-700 print-border-light text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                                <th class="px-8 py-6"><i class="fa-regular fa-clock mr-2 print-hide-icon"></i>Tanggal Selesai</th>
                                <th class="px-8 py-6"><i class="fa-solid fa-user mr-2 print-hide-icon"></i>Pelanggan</th>
                                <th class="px-8 py-6"><i class="fa-solid fa-tags mr-2 print-hide-icon"></i>Detail Layanan</th>
                                <th class="px-8 py-6 text-right whitespace-nowrap"><i class="fa-solid fa-wallet mr-2 print-hide-icon"></i>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50 print-border-light text-sm">
                            @forelse($laporanOmzet ?? $laporan as $l)
                            <tr class="hover:bg-slate-800/30 transition-all group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-400 print-hide-icon shadow-inner">
                                            <i class="fa-solid fa-calendar-check"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-white print-text-dark uppercase text-[11px] tracking-widest">{{ $l->updated_at->format('d M Y') }}</p>
                                            <p class="text-[9px] font-bold text-slate-500 mt-1 uppercase tracking-widest">{{ $l->updated_at->format('H:i') }} WIB</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="font-black text-white print-text-dark uppercase italic leading-tight text-base group-hover:text-{{ $accent }}-400 transition-colors">{{ $l->user->nama ?? 'Walk-in Customer' }}</p>
                                    <p class="text-[9px] font-bold text-slate-500 mt-1 uppercase tracking-widest">ID #{{ str_pad($l->id_reservasi, 4, '0', STR_PAD_LEFT) }}</p>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="bg-slate-800 text-slate-300 print-text-dark px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-[0.2em] border border-slate-700 print-border-light shadow-inner">
                                        {{ $l->detail->first()->layanan->nama_layanan ?? $l->layanan->pluck('nama_layanan')->first() ?? 'Layanan' }}
                                    </span>
                                    <span class="block text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-widest">
                                        <i class="fa-solid fa-shoe-prints mr-1 print-hide-icon"></i> {{ $l->detail->first()->jumlah ?? 1 }} PASANG
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right font-black text-{{ $accent }}-400 print-text-dark italic text-lg tracking-tighter">
                                    Rp {{ number_format($l->total_harga, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-24 text-center">
                                    <div class="flex flex-col items-center opacity-30">
                                        <i class="fa-solid fa-receipt text-6xl mb-4 text-slate-400"></i>
                                        <p class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-400">Data belum tersedia / Filter tidak ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- FOOTER & TOMBOL CETAK --}}
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 no-print relative z-10">
                <div class="flex items-center gap-4 opacity-50">
                    <span class="w-2 h-2 rounded-full bg-{{ $accent }}-500 animate-pulse shadow-[0_0_10px_currentColor]"></span>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] italic">
                        Laporan resmi yang diverifikasi sistem
                    </p>
                </div>
                
                <button onclick="window.print()" class="w-full md:w-auto bg-slate-800 border border-slate-700 hover:bg-white hover:text-slate-900 text-white px-8 py-4 rounded-[1.5rem] font-black uppercase text-[10px] tracking-[0.2em] transition-all flex items-center justify-center gap-3 shadow-lg active:scale-95 group">
                    <i class="fa-solid fa-print group-hover:-translate-y-1 transition-transform"></i> Unduh Laporan PDF
                </button>
            </div>

        </div>
    </main>

</body>
</html>