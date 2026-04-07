<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan - ROFF.ADMIN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .max-w-6xl { max-width: 100% !important; width: 100% !important; margin: 0 !important; padding: 0 !important; }
            .shadow-sm { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
        }
    </style>
</head>
<body class="bg-slate-50 antialiased min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    <nav class="w-full px-4 md:px-6 lg:px-12 py-4 md:py-6 no-print bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="flex justify-between items-center gap-2">
            <a href="{{ auth()->user()->id_role == 1 ? route('superadmin.users') : route('admin.dashboard') }}" 
               class="inline-flex items-center gap-2 md:gap-3 text-slate-400 hover:text-blue-600 transition-all group shrink-0">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-white border border-slate-200 rounded-lg md:rounded-xl flex items-center justify-center group-hover:border-blue-200 group-hover:bg-blue-50 transition-all shadow-sm">
                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </div>
                <span class="font-black text-[9px] md:text-[10px] uppercase tracking-[0.2em] hidden sm:block">Kembali ke Dasbor</span>
            </a>

            <div class="flex items-center gap-3 md:gap-4 shrink-0">
                <div class="text-right hidden sm:block">
                    <p class="text-[8px] md:text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">
                        {{ auth()->user()->id_role == 1 ? 'Master Superadmin' : 'Staff Admin' }}
                    </p>
                    <p class="text-[10px] md:text-xs font-bold text-slate-900">{{ auth()->user()->nama }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline m-0 p-0">
                    @csrf
                    <button type="submit" class="p-2 md:p-2.5 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-lg md:rounded-xl transition-all border border-red-100 group" title="Keluar">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4-4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- KONTEN UTAMA --}}
    <main class="w-full max-w-6xl mx-auto px-4 md:px-8 py-8 md:py-12 flex-1">
        
        {{-- HEADER LAPORAN --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-8 md:mb-12">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 uppercase tracking-tighter italic leading-tight">Laporan <span class="text-blue-600">Pendapatan.</span></h1>
                <p class="text-slate-500 font-medium text-xs md:text-sm mt-1">Rekapitulasi transaksi cucian sepatu yang telah selesai.</p>
            </div>
            
            {{-- KOTAK OMZET (Di HP jadi memanjang ke samping) --}}
            <div class="text-left md:text-right bg-white md:bg-transparent p-4 md:p-0 rounded-2xl md:rounded-none border md:border-none border-slate-200 shadow-sm md:shadow-none w-full md:w-auto">
                <p class="text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Total Omzet</p>
                <h2 class="text-2xl md:text-3xl font-black text-blue-600 tracking-tighter">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</h2>
            </div>
        </div>

        {{-- TABEL LAPORAN --}}
        <div class="bg-white rounded-[1.5rem] md:rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden mb-8 md:mb-10">
            
            {{-- Pembungkus overflow-x-auto untuk layar HP --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[600px] md:min-w-full">
                    <thead>
                        <tr class="bg-slate-50 text-[9px] md:text-[10px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100">
                            <th class="p-4 md:p-8 whitespace-nowrap">Tanggal</th>
                            <th class="p-4 md:p-8">Pelanggan</th>
                            <th class="p-4 md:p-8">Layanan</th>
                            <th class="p-4 md:p-8 text-right whitespace-nowrap">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-xs md:text-sm">
                        @forelse($laporan as $l)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-4 md:p-8 font-medium text-slate-600 whitespace-nowrap">{{ \Carbon\Carbon::parse($l->tanggal_reservasi)->format('d M Y') }}</td>
                            <td class="p-4 md:p-8 font-bold text-slate-900 leading-none">
                                {{ $l->user->nama ?? 'User Terhapus' }}
                                <span class="block text-[9px] md:text-[10px] text-slate-400 font-medium mt-1 uppercase tracking-widest">ID #{{ $l->id_reservasi }}</span>
                            </td>
                            <td class="p-4 md:p-8 italic font-semibold text-slate-500">
                                {{ $l->layanan->pluck('nama_layanan')->join(', ') ?: 'Layanan' }}
                                <span class="block md:inline not-italic font-normal text-slate-400 text-[10px] md:text-xs md:ml-1 mt-0.5 md:mt-0">({{ $l->jumlah_sepatu }} Pasang)</span>
                            </td>
                            <td class="p-4 md:p-8 text-right font-black text-slate-900 whitespace-nowrap">Rp {{ number_format($l->total_harga, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-10 md:p-20 text-center text-slate-400 italic font-medium text-xs md:text-sm">Belum ada transaksi yang diselesaikan bulan ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Akhir dari pembungkus overflow --}}

        </div>

        {{-- TOMBOL CETAK --}}
        <div class="flex justify-end no-print">
            <button onclick="window.print()" class="w-full md:w-auto justify-center bg-slate-900 text-white px-8 md:px-10 py-3 md:py-4 rounded-xl md:rounded-2xl font-black text-[10px] md:text-xs uppercase tracking-widest hover:bg-blue-600 hover:shadow-xl hover:shadow-blue-500/20 transition-all flex items-center gap-2 md:gap-3">
                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Cetak Laporan Resmi
            </button>
        </div>
    </main>

</body>
</html>