<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan - ROFF.SHOECLEAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0f172a; overflow: hidden; }
        
        .glass-panel { 
            background: rgba(30, 41, 59, 0.4); 
            backdrop-filter: blur(16px); 
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05); 
        }

        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }

        @media print {
            @page { 
                size: A4 portrait; 
                margin: 15mm 15mm; 
            }

            .no-print { display: none !important; }

            body, main { 
                background: white !important; 
                overflow: visible !important; 
                display: block !important;
                height: auto !important;
                width: 100% !important;
                color: #000 !important;
            }

            .overflow-y-auto { overflow: visible !important; height: auto !important; padding: 0 !important; }

            .glass-panel { 
                background: white !important; 
                border: 1px solid #e2e8f0 !important; 
                box-shadow: none !important; 
                border-radius: 0.75rem !important;
                margin-bottom: 20px !important;
                padding: 20px !important;
            }

            table { 
                width: 100% !important; 
                table-layout: fixed !important; 
                border-collapse: collapse !important;
            }

            th { 
                font-size: 8.5px !important; 
                padding: 12px 8px !important; 
                background-color: #0f172a !important; 
                color: white !important; 
                border: 1px solid #0f172a !important;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            td { 
                font-size: 9px !important; 
                padding: 10px 8px !important; 
                border-bottom: 1px solid #f1f5f9 !important;
                word-wrap: break-word !important;
            }

            th:last-child, td:last-child {
                padding-right: 40px !important; 
                text-align: right !important;
                width: 100px !important;
            }

            .badge-fast { background-color: #2563eb !important; color: white !important; }
            .badge-deep { background-color: #f59e0b !important; color: white !important; }
            .badge-unyellow { background-color: #9333ea !important; color: white !important; }
            .badge-other { background-color: #64748b !important; color: white !important; }

            .print-text-dark { color: #000 !important; }
            .blur-\[150px\], .blur-2xl { display: none !important; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>
</head>

@php
    $isSuper = auth()->user()->id_role == 1 || auth()->user()->role === 'superadmin';
    $accent = $isSuper ? 'emerald' : 'blue';
@endphp

<body class="text-slate-200 antialiased flex h-screen overflow-hidden relative selection:bg-{{ $accent }}-500">

    <main class="flex-1 flex flex-col h-screen min-w-0 bg-[#0f172a] relative z-10">
        
        <header class="bg-[#0f172a]/80 backdrop-blur-xl border-b border-white/5 px-6 md:px-12 py-4 flex justify-between items-center shrink-0 z-40 no-print sticky top-0">
            <div class="flex items-center gap-3 md:gap-4">
                <a href="{{ $isSuper ? route('superadmin.dashboard') : route('admin.dashboard') }}" class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-slate-800/50 border border-slate-700 text-slate-400 hover:text-{{ $accent }}-400 hover:bg-slate-800 transition-all flex items-center justify-center shadow-sm group">
                    <i class="fa-solid fa-arrow-left text-sm group-hover:-translate-x-1"></i>
                </a>
                <h1 class="block font-black text-xl md:text-2xl uppercase tracking-tighter italic text-white leading-tight">
                    ROFF.<span class="text-{{ $accent }}-500">{{ $isSuper ? 'SUPER' : 'ADMIN' }}</span>
                </h1>
            </div>
            
            <div class="flex items-center gap-5">
                <div class="flex items-center bg-slate-800/40 border border-slate-700 p-1 pr-4 rounded-full shadow-inner">
                    <div class="w-8 h-8 rounded-full overflow-hidden bg-{{ $accent }}-600 flex items-center justify-center text-[10px] font-black text-white border border-slate-700 shadow-xl">
                        @if(auth()->user()->foto_profil)
                            <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" class="w-full h-full object-cover">
                        @else
                            {{ $isSuper ? 'SU' : strtoupper(substr(auth()->user()->nama, 0, 2)) }}
                        @endif
                    </div>
                    <div class="ml-3 hidden md:block">
                        <p class="text-[10px] font-black text-white uppercase tracking-widest leading-none">{{ explode(' ', auth()->user()->nama)[0] }}</p>
                        <p class="text-[7px] font-bold text-{{ $accent }}-400/80 uppercase mt-0.5 tracking-tighter">{{ $isSuper ? 'Owner Access' : 'Verified Access' }}</p>
                    </div>
                </div>
            </div>
        </header>

        <div class="p-6 md:p-12 flex-1 overflow-y-auto custom-scroll relative">
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-{{ $accent }}-600/10 blur-[150px] rounded-full pointer-events-none -translate-y-1/2 translate-x-1/4 no-print"></div>

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10 relative z-10">
                <div>
                    <h1 class="text-3xl md:text-5xl font-black text-white print-text-dark tracking-tighter leading-none mb-2">
                        Laporan <span class="italic text-transparent bg-clip-text bg-gradient-to-r from-{{ $accent }}-400 to-teal-200 print-text-dark">Omset</span>
                    </h1>
                    <p class="text-slate-400 print-text-gray font-medium text-sm italic">Monitoring Pendapatan ROFF.SHOECLEAN</p>
                </div>

                <div class="glass-panel px-10 py-8 rounded-[2rem] text-right border border-{{ $accent }}-500/20">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 group-hover:text-{{ $accent }}-400 transition-colors">Total Omset Keseluruhan</p>
                    <h3 class="text-3xl md:text-5xl font-black text-white print-text-dark italic">
                        <span class="text-xl md:text-3xl text-{{ $accent }}-500 mr-1 font-bold">Rp</span>{{ number_format($totalOmzet ?? 0, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
            
            <div class="glass-panel rounded-3xl p-6 mb-8 no-print border border-white/5">
                <form action="{{ url()->current() }}" method="GET" class="flex flex-col md:flex-row items-end gap-5">
                    <div class="flex-1 w-full"><label class="text-[10px] font-black text-slate-400 uppercase mb-2 block ml-1">Dari Tanggal</label>
                        <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai', date('Y-m-d')) }}" class="w-full bg-slate-800 border-slate-700 text-white rounded-xl px-4 py-3 [color-scheme:dark] outline-none">
                    </div>
                    <div class="flex-1 w-full"><label class="text-[10px] font-black text-slate-400 uppercase mb-2 block ml-1">Sampai Tanggal</label>
                        <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai', date('Y-m-d')) }}" class="w-full bg-slate-800 border-slate-700 text-white rounded-xl px-4 py-3 [color-scheme:dark] outline-none">
                    </div>
                    <button type="submit" class="bg-{{ $accent }}-600 hover:bg-{{ $accent }}-500 text-white px-8 py-3.5 rounded-xl font-black uppercase text-xs tracking-widest transition-all shadow-lg active:scale-95">Filter</button>
                </form>
            </div>

            <div class="glass-panel rounded-[2rem] overflow-hidden mb-10 border border-white/5">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-800/50 print-bg-light border-b border-slate-700 text-[9px] font-black uppercase tracking-widest text-slate-400">
                                <th class="px-6 py-5 w-[15%]">Tanggal</th>
                                <th class="px-6 py-5 w-[20%]">Pelanggan</th>
                                <th class="px-6 py-5 w-[15%]">Metode</th>
                                <th class="px-6 py-5 w-[35%]">Layanan & Qty</th>
                                <th class="px-6 py-5 w-[15%] text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50 text-sm">
                            @forelse($laporanOmzet ?? $laporan as $l)
                            <tr class="hover:bg-slate-800/20 transition-colors">
                                <td class="px-6 py-5">
                                    <p class="font-bold text-white print-text-dark uppercase text-[10px] tracking-widest">{{ $l->updated_at->format('d/m/y') }}</p>
                                    <p class="text-[8px] font-bold text-slate-500 mt-1 uppercase">{{ $l->updated_at->format('H:i') }} WIB</p>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="font-black text-white print-text-dark uppercase italic leading-tight text-sm">{{ $l->user->nama ?? 'Walk-in' }}</p>
                                    <p class="text-[8px] font-bold text-slate-500 mt-1 uppercase">ID #{{ $l->id_reservasi }}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-[9px] font-black text-slate-400 print-text-dark uppercase italic tracking-tighter">
                                        {{ $l->metode_bayar ?? 'Payment Gateway' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    @foreach($l->detail as $det)
                                        @php
                                            $nm = strtolower($det->layanan->nama_layanan ?? '');
                                            if(str_contains($nm, 'fast')) { $bCls = 'bg-blue-600 badge-fast'; }
                                            elseif(str_contains($nm, 'deep')) { $bCls = 'bg-amber-500 badge-deep'; }
                                            elseif(str_contains($nm, 'unyellow')) { $bCls = 'bg-purple-600 badge-unyellow'; }
                                            else { $bCls = 'bg-slate-700 badge-other'; }
                                        @endphp
                                        <div class="mb-2 last:mb-0 flex items-center">
                                            <span class="{{ $bCls }} text-white px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest border border-white/10">
                                                {{ $det->layanan->nama_layanan ?? 'Custom' }}
                                            </span>
                                            <span class="ml-1 text-[8px] font-bold text-slate-400 uppercase italic">
                                                {{ $det->jumlah }} Psg
                                            </span>
                                        </div>
                                    @endforeach
                                </td>
                                <td class="px-6 py-5 text-right font-black text-{{ $accent }}-400 print-text-dark italic text-base tracking-tighter">
                                    Rp {{ number_format($l->total_harga, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="p-24 text-center text-slate-500 font-black uppercase text-xs italic tracking-widest">Belum ada transaksi</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-center gap-6 no-print">
                <p class="text-[9px] font-black text-slate-500 uppercase italic tracking-widest">Official Report ROFF.SHOECLEAN System</p>
                
                <button onclick="window.print()" class="bg-slate-800 border border-slate-700 hover:bg-white hover:text-slate-900 text-white px-10 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest transition-all shadow-lg active:scale-95 flex items-center gap-3">
                    <i class="fa-solid fa-print"></i> Cetak PDF
                </button>
            </div>
        </div>
    </main>

</body>
</html>