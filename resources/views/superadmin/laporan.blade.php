<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan - ROFF.ADMIN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
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

        @media print {
            .no-print { display: none !important; }
            main { margin: 0 !important; padding: 0 !important; width: 100% !important; max-width: 100% !important; background: white !important;}
            body { background: white !important; overflow: auto !important; }
            .glass-panel { background: white !important; border: 1px solid #e2e8f0 !important; box-shadow: none !important; backdrop-filter: none !important; }
            .print-text-dark { color: #0f172a !important; }
            .print-border-light { border-color: #e2e8f0 !important; }
            th { color: #64748b !important; }
            td { color: #0f172a !important; border-bottom: 1px solid #f1f5f9 !important; }
            .print-bg-light { background: #f8fafc !important; }
        }
    </style>
</head>
<body class="text-slate-200 antialiased flex h-screen overflow-hidden relative">

    @php
        // ✅ LOGIKA CERDAS: Cek id_role (1 = Superadmin, 2 = Admin)
        $isSuper = auth()->user()->id_role == 1;
        $accentColor = $isSuper ? 'emerald' : 'blue';
        $accentHex = $isSuper ? 'text-emerald-500' : 'text-blue-500';
    @endphp

    {{-- KONTEN UTAMA --}}
    <main class="flex-1 flex flex-col h-screen min-w-0 bg-[#0f172a] relative z-10">
        
        {{-- TOP NAVIGATION --}}
        <header class="bg-[#0f172a]/40 backdrop-blur-xl border-b border-white/5 px-6 md:px-12 py-4 flex justify-between items-center shrink-0 z-40 no-print">
            
            <div class="flex items-center gap-3 md:gap-4">
                {{-- Tombol Kembali otomatis ke Dashboard masing-masing --}}
                <a href="{{ $isSuper ? route('superadmin.dashboard') : route('admin.dashboard') }}" class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-slate-800/50 border border-slate-700 text-slate-400 hover:text-{{ $accentColor }}-400 hover:bg-slate-800 transition-all flex items-center justify-center shadow-sm group active:scale-95" title="Kembali ke Dasbor">
                    <i class="fa-solid fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
                </a>
                
                <h1 class="block font-black text-xl md:text-2xl uppercase tracking-tighter italic text-white leading-tight">
                    ROFF.<span class="{{ $accentHex }}">{{ $isSuper ? 'SUPER' : 'ADMIN' }}</span>
                </h1>
            </div>
            
            <div class="flex items-center gap-5">
                <div class="flex items-center bg-slate-800/40 border border-slate-700 p-1 pr-4 rounded-full shadow-inner">
                    <div class="w-8 h-8 rounded-full overflow-hidden bg-{{ $accentColor }}-600 flex items-center justify-center text-[10px] font-black text-white border border-slate-700 shadow-xl">
                        {{ $isSuper ? 'SU' : strtoupper(substr(auth()->user()->nama, 0, 2)) }}
                    </div>
                    <div class="ml-3 hidden md:block">
                        <p class="text-[10px] font-black text-white uppercase tracking-widest leading-none">{{ explode(' ', auth()->user()->nama)[0] }}</p>
                        <p class="text-[7px] font-bold text-{{ $accentColor }}-500/60 uppercase mt-0.5 tracking-tighter">{{ $isSuper ? 'Owner Access' : 'Verified Access' }}</p>
                    </div>
                </div>
            </div>
        </header>

        <div class="p-6 md:p-12 flex-1 overflow-y-auto custom-scroll relative">
            
            {{-- Background Glow Dinamis --}}
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-{{ $accentColor }}-600/10 blur-[120px] rounded-full pointer-events-none -translate-y-1/2 translate-x-1/4 no-print"></div>

            {{-- HEADER HALAMAN & TOTAL OMSET --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8 md:mb-12 relative z-10">
                <div>
                    <div class="inline-flex items-center gap-2 bg-{{ $accentColor }}-500/10 border border-{{ $accentColor }}-500/20 px-4 py-1.5 rounded-full mb-4 no-print">
                        <span class="w-1.5 h-1.5 rounded-full bg-{{ $accentColor }}-500 animate-pulse"></span>
                        <p class="text-[8px] md:text-[9px] font-black text-{{ $accentColor }}-400 uppercase tracking-[0.4em]">Financial Report</p>
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black text-white print-text-dark tracking-tighter leading-none mb-2">
                        Laporan <span class="italic text-transparent bg-clip-text bg-gradient-to-r from-{{ $accentColor }}-400 to-teal-200 print-text-dark">Omset.</span>
                    </h1>
                    <p class="text-slate-400 print-text-dark font-medium text-sm">Monitoring resmi pendapatan sistem ROFF.SHOECLEAN</p>
                </div>

                <div class="glass-panel w-full md:w-auto px-8 py-6 rounded-[2rem] text-right shadow-2xl relative overflow-hidden shrink-0 border border-white/10 print-border-light print-bg-light">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-{{ $accentColor }}-500/20 rounded-full -translate-y-1/2 translate-x-1/2 blur-md no-print"></div>
                    <p class="text-[9px] md:text-[10px] font-black text-{{ $accentColor }}-400 uppercase tracking-[0.3em] mb-1 relative z-10">Total Omset Periode</p>
                    <h3 class="text-2xl md:text-4xl font-black text-white print-text-dark tracking-tighter relative z-10 italic">
                        Rp {{ number_format($totalOmzet ?? 0, 0, ',', '.') }}
                    </h3>
                </div>
            </div>

            {{-- FILTER TANGGAL (REVISI DOSEN) --}}
            <div class="glass-panel rounded-3xl p-5 md:p-6 mb-8 relative z-10 no-print border border-white/5 shadow-lg">
                <form action="{{ url()->current() }}" method="GET" class="flex flex-col md:flex-row items-end gap-4">
                    <div class="w-full md:w-auto flex-1">
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Dari Tanggal</label>
                        <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai', $tgl_mulai ?? '') }}" class="w-full bg-slate-800/50 border border-slate-700 text-white rounded-xl px-5 py-3.5 text-sm focus:ring-2 focus:ring-{{ $accentColor }}-500 outline-none transition-all [color-scheme:dark]">
                    </div>
                    <div class="w-full md:w-auto flex-1">
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Sampai Tanggal</label>
                        <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai', $tgl_selesai ?? '') }}" class="w-full bg-slate-800/50 border border-slate-700 text-white rounded-xl px-5 py-3.5 text-sm focus:ring-2 focus:ring-{{ $accentColor }}-500 outline-none transition-all [color-scheme:dark]">
                    </div>
                    <button type="submit" class="w-full md:w-auto bg-{{ $accentColor }}-600 hover:bg-{{ $accentColor }}-500 text-white px-8 py-4 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">
                        <i class="fa-solid fa-filter mr-2"></i> Terapkan
                    </button>
                    @if(request('tgl_mulai'))
                        <a href="{{ url()->current() }}" class="w-full md:w-auto bg-slate-700 text-white px-6 py-4 rounded-xl font-black text-[10px] uppercase tracking-widest text-center">Reset</a>
                    @endif
                </form>
            </div>
            
            {{-- TABEL DATA --}}
            <div class="glass-panel rounded-[2.5rem] border border-white/5 shadow-2xl overflow-hidden mb-8 md:mb-10 relative z-10 print-border-light">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[700px]">
                        <thead>
                            <tr class="bg-slate-800/50 print-bg-light border-b border-slate-700 print-border-light text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                                <th class="px-8 py-6">Tanggal Selesai</th>
                                <th class="px-8 py-6">Pelanggan</th>
                                <th class="px-8 py-6">Detail Layanan</th>
                                <th class="px-8 py-6 text-right whitespace-nowrap">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50 print-border-light text-sm">
                            @forelse($laporan as $l)
                            <tr class="hover:bg-slate-800/30 transition-all group">
                                <td class="px-8 py-6 font-bold text-slate-300 uppercase text-[11px]">
                                    {{ $l->tanggal_reservasi }}
                                </td>
                                <td class="px-8 py-6">
                                    <p class="font-black text-white print-text-dark uppercase italic leading-tight">{{ $l->user->nama ?? 'Walk-in' }}</p>
                                    <p class="text-[9px] font-bold text-slate-500 mt-1 uppercase tracking-widest">ID #{{ $l->id_reservasi }}</p>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="bg-slate-800 text-slate-300 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-[0.2em] border border-slate-700">
                                        {{ $l->detail->first()->layanan->nama_layanan ?? 'N/A' }}
                                    </span>
                                    <span class="block text-[9px] font-bold text-slate-500 mt-2 uppercase tracking-widest">{{ $l->detail->first()->jumlah ?? 1 }} PASANG</span>
                                </td>
                                <td class="px-8 py-6 text-right font-black text-{{ $accentColor }}-400 italic text-base tracking-tighter">
                                    Rp {{ number_format($l->total_harga, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-24 text-center">
                                    <p class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-400 opacity-30">Data belum tersedia</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TOMBOL CETAK --}}
            <div class="flex justify-end no-print relative z-10">
                <button onclick="window.print()" class="bg-slate-800 border border-slate-700 hover:bg-white hover:text-slate-900 text-white px-8 py-4 rounded-[1.5rem] font-black uppercase text-[10px] tracking-[0.2em] transition-all flex items-center gap-3">
                    <i class="fa-solid fa-file-export"></i> Unduh Laporan Resmi
                </button>
            </div>

        </div>
    </main>
</body>
</html>