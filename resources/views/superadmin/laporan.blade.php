<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Omzet - ROFF.SUPER</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0b1120; }
        .glass-card { background: rgba(30, 41, 59, 0.4); border: 1px solid rgba(51, 65, 85, 0.5); }
        .sidebar-active { background: #4f46e5; box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4); }
        
        /* Custom scrollbar */
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #0b1120; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }

        @media print {
            aside { display: none; }
            main { margin-left: 0 !important; width: 100%; }
            .no-print { display: none; }
            body { background-color: white; color: black; }
            .glass-card { border: 1px solid #ddd; background: white; }
            .text-white { color: black !important; }
        }
    </style>
</head>
<body class="text-slate-200 antialiased flex flex-col md:flex-row h-screen overflow-hidden">

    {{-- SIDEBAR SUPERADMIN (KHUSUS LAPORAN) --}}
    <aside class="hidden md:flex w-72 bg-[#0b1120] border-r border-slate-800/60 flex-col h-full shrink-0">
        <div class="p-8">
            <h1 class="font-black text-2xl uppercase tracking-tighter italic text-white">
                ROFF.<span class="text-indigo-500">SUPER</span>
            </h1>
        </div>

        <nav class="flex-1 px-4 space-y-2 mt-4">
            {{-- Menu Laporan Omzet (Status Aktif) --}}
            <a href="{{ route('superadmin.laporan') }}" class="flex items-center gap-4 sidebar-active text-white px-6 py-4 rounded-2xl font-black text-[11px] uppercase tracking-[0.15em] transition-all pointer-events-none">
                <i class="fa-solid fa-chart-line text-lg"></i>
                Laporan Omzet
            </a>
            
            <div class="pt-6 border-t border-slate-800/40 mt-4">
                {{-- Navigasi Kembali Ke Dashboard 4 Kotak --}}
                <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-4 text-slate-500 hover:text-white px-6 py-4 rounded-2xl font-bold text-[11px] uppercase tracking-[0.15em] transition-all group">
                    <i class="fa-solid fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
                    Dasbor Utama
                </a>
            </div>
        </nav>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 flex flex-col overflow-hidden bg-[#0b1120] relative min-w-0">
        {{-- Efek Cahaya --}}
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-600/10 blur-[120px] rounded-full pointer-events-none -translate-y-1/2 translate-x-1/4"></div>

        {{-- HEADER --}}
        <header class="px-6 md:px-10 py-8 flex flex-col sm:flex-row justify-between items-start sm:items-center shrink-0 relative z-10 gap-6">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-white tracking-tight italic leading-none">Revenue<span class="text-indigo-500">Report.</span></h2>
                <p class="text-[10px] text-slate-500 uppercase font-bold tracking-[0.2em] mt-3 italic">Monitoring pendapatan otomatis</p>
            </div>
            
            <div class="bg-indigo-600/10 border border-indigo-500/20 p-6 rounded-[2rem] min-w-[280px] glass-card">
                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em] mb-1">Total Omzet (Selesai)</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-white italic tracking-tight">Rp{{ number_format($totalOmzet, 0, ',', '.') }}</span>
                </div>
            </div>
        </header>

        {{-- AREA TABEL --}}
        <div class="flex-1 overflow-y-auto px-6 md:px-10 pb-12 relative z-10 custom-scroll">
            <div class="glass-card rounded-[2.5rem] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-900/60 border-b border-slate-800">
                                <th class="px-8 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Waktu Transaksi</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Pelanggan</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Layanan Dipilih</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/40">
                            @forelse($laporanOmzet as $l)
                            <tr class="hover:bg-white/[0.02] transition-all group">
                                <td class="px-8 py-6">
                                    <span class="text-xs font-bold text-slate-300">{{ $l->updated_at->format('d M Y') }}</span>
                                    <p class="text-[9px] text-slate-600 font-bold uppercase mt-1 tracking-tighter">{{ $l->updated_at->format('H:i') }} WIB</p>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-sm font-black text-white italic uppercase group-hover:text-indigo-400 transition-colors">{{ $l->user->nama }}</p>
                                    <p class="text-[9px] text-slate-500 font-bold tracking-widest mt-1 uppercase italic">ID: #{{ $l->id_reservasi }}</p>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="bg-slate-800 text-slate-300 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border border-slate-700">
                                        {{ $l->detail->layanan->nama_layanan ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <span class="text-base font-black text-white italic">Rp{{ number_format($l->total_harga, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-32 text-center">
                                    <div class="flex flex-col items-center opacity-30">
                                        <i class="fa-solid fa-chart-bar text-6xl mb-4 text-slate-700"></i>
                                        <p class="font-bold uppercase tracking-[0.3em] text-[10px]">Data Pendapatan Belum Tersedia</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ACTION FOOTER --}}
            <div class="mt-8 flex flex-col sm:flex-row justify-between items-center px-4 gap-4 no-print">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest italic">
                        Laporan otomatis sinkron dengan transaksi berstatus "Selesai".
                    </p>
                </div>
                <button onclick="window.print()" class="flex items-center gap-3 bg-white text-slate-900 px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-indigo-500 hover:text-white transition-all shadow-xl">
                    <i class="fa-solid fa-print text-sm"></i> Unduh Laporan Resmi
                </button>
            </div>
        </div>
    </main>

</body>
</html>