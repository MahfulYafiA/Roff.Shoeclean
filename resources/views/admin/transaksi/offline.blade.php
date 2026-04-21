<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Offline - ROFF.ADMIN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0f172a; } 
        
        /* Dark Glassmorphism Card */
        .glass-panel { 
            background: rgba(30, 41, 59, 0.4); 
            backdrop-filter: blur(16px); 
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05); 
        }

        /* Custom Input Dark */
        .dark-input {
            background-color: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(51, 65, 85, 0.8);
            color: #f8fafc;
            transition: all 0.3s ease;
        }
        .dark-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            outline: none;
        }
        
        /* Scrollbar */
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    </style>
</head>
<body class="text-slate-200 antialiased flex flex-col min-h-screen overflow-x-hidden relative custom-scroll">

    {{-- TOP NAVIGATION (Menyamakan dengan halaman Antrean) --}}
    <header class="bg-[#0f172a]/80 backdrop-blur-xl px-6 py-4 flex justify-between items-center sticky top-0 z-50 border-b border-white/5">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 rounded-full bg-slate-800/80 border border-slate-700 text-slate-400 hover:text-white hover:bg-slate-700 transition-all flex items-center justify-center shadow-sm">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <h1 class="font-black text-xl md:text-2xl uppercase tracking-tighter italic text-white leading-tight">
                ROFF.<span class="text-blue-500">ADMIN</span>
            </h1>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="hidden md:flex items-center bg-slate-800/50 border border-slate-700/50 px-4 py-1.5 rounded-full">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Hari Ini: <span class="text-white">{{ now()->format('d M Y') }}</span></span>
            </div>
            <div class="flex items-center bg-slate-800/50 border border-slate-700/50 p-1 pr-4 rounded-full">
                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-[10px] font-black text-white">
                    ST
                </div>
                <div class="ml-3">
                    <p class="text-[10px] font-black text-white uppercase tracking-widest leading-none">Staf</p>
                    <p class="text-[7px] font-bold text-blue-400 uppercase tracking-tighter">Staff Access</p>
                </div>
            </div>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 w-full px-6 py-8 md:p-10 relative z-10">
        
        {{-- Background Glow --}}
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-blue-600/5 blur-[150px] rounded-full pointer-events-none -translate-y-1/2 translate-x-1/3"></div>

        <div class="w-full max-w-[1400px] mx-auto">
            
            {{-- HEADER TITLE --}}
            <div class="mb-8 md:mb-12">
                <div class="inline-flex items-center gap-2 bg-blue-500/10 border border-blue-500/20 px-3 py-1 rounded-full mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse shadow-[0_0_10px_rgba(59,130,246,1)]"></span>
                    <p class="text-[9px] font-black text-blue-400 uppercase tracking-[0.3em]">Cashier System</p>
                </div>
                <h1 class="text-3xl md:text-5xl font-black text-white tracking-tighter mb-2 leading-none">
                    Kasir <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400 italic">Offline.</span>
                </h1>
                <p class="text-slate-400 font-medium text-sm">Input pesanan pelanggan yang datang langsung ke toko secara real-time.</p>
            </div>

            {{-- ALERT SUCCESS --}}
            @if(session('success'))
                <div class="mb-8 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-5 rounded-2xl flex items-center gap-3 shadow-lg">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                    <span class="text-sm font-bold uppercase tracking-wide">{{ session('success') }}</span>
                </div>
            @endif

            {{-- FULL WIDTH FORM --}}
            <form action="{{ route('admin.transaksi.store-offline') }}" method="POST" class="glass-panel rounded-[2rem] border border-slate-700/50 p-6 md:p-10">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 lg:gap-12">
                    
                    {{-- KOLOM 1: DATA PELANGGAN --}}
                    <div class="space-y-6">
                        <h3 class="flex items-center gap-3 text-sm font-black text-white uppercase tracking-widest border-b border-slate-700/50 pb-4">
                            <div class="w-8 h-8 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center"><i class="fa-solid fa-user"></i></div>
                            Customer (Walk-in)
                        </h3>
                        
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Pelanggan <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_pelanggan" required placeholder="Contoh: Budi Santoso" class="dark-input w-full rounded-xl px-5 py-3.5 text-sm font-semibold">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nomor WhatsApp</label>
                            <input type="text" name="no_telp" placeholder="Contoh: 08123456789 (Opsional)" class="dark-input w-full rounded-xl px-5 py-3.5 text-sm font-semibold">
                        </div>
                    </div>

                    {{-- KOLOM 2: DETAIL PESANAN --}}
                    <div class="space-y-6">
                        <h3 class="flex items-center gap-3 text-sm font-black text-white uppercase tracking-widest border-b border-slate-700/50 pb-4">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center"><i class="fa-solid fa-box-open"></i></div>
                            Detail Cucian
                        </h3>
                        
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Layanan Jasa <span class="text-red-500">*</span></label>
                            <select name="id_layanan" required class="dark-input w-full rounded-xl px-5 py-3.5 text-sm font-semibold appearance-none">
                                <option value="" class="bg-slate-800 text-slate-400">-- Pilih Jenis Layanan --</option>
                                @foreach($layanans as $layanan)
                                    <option value="{{ $layanan->id_layanan }}" class="bg-slate-800 text-white">{{ $layanan->nama_layanan }} - Rp {{ number_format($layanan->harga, 0, ',', '.') }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Jumlah Sepatu (Pasang) <span class="text-red-500">*</span></label>
                            <input type="number" name="jumlah" value="1" min="1" required class="dark-input w-full rounded-xl px-5 py-3.5 text-sm font-semibold">
                        </div>
                    </div>

                    {{-- KOLOM 3: PEMBAYARAN & SUBMIT --}}
                    <div class="space-y-6 flex flex-col h-full">
                        <h3 class="flex items-center gap-3 text-sm font-black text-white uppercase tracking-widest border-b border-slate-700/50 pb-4">
                            <div class="w-8 h-8 rounded-lg bg-orange-500/20 text-orange-400 flex items-center justify-center"><i class="fa-solid fa-cash-register"></i></div>
                            Pembayaran Kasir
                        </h3>
                        
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Metode Bayar <span class="text-red-500">*</span></label>
                            <select name="metode_bayar" required class="dark-input w-full rounded-xl px-5 py-3.5 text-sm font-semibold appearance-none">
                                <option value="Tunai" class="bg-slate-800 text-white">💵 Tunai (Cash)</option>
                                <option value="Transfer Manual" class="bg-slate-800 text-white">📱 Transfer / QRIS Toko</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Status Lunas? <span class="text-red-500">*</span></label>
                            <select name="status_bayar" required class="dark-input w-full rounded-xl px-5 py-3.5 text-sm font-semibold appearance-none">
                                <option value="Lunas" class="bg-slate-800 text-emerald-400 font-bold">✅ Sudah Lunas (Bayar di Muka)</option>
                                <option value="Belum Lunas" class="bg-slate-800 text-red-400 font-bold">❌ Belum Lunas (Bayar Nanti)</option>
                            </select>
                        </div>

                        {{-- Tombol Submit didorong ke paling bawah --}}
                        <div class="mt-auto pt-6">
                            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white py-4 rounded-xl font-black text-[11px] md:text-xs uppercase tracking-[0.2em] shadow-[0_10px_30px_-10px_rgba(59,130,246,0.5)] transition-all duration-300 active:scale-[0.98] flex items-center justify-center gap-3">
                                <i class="fa-solid fa-print"></i> Proses Transaksi & Simpan
                            </button>
                        </div>
                    </div>

                </div>
            </form>
            
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="w-full py-6 flex justify-center items-center opacity-40 shrink-0 mt-auto z-10 border-t border-white/5 bg-[#0f172a]/80">
        <p class="text-[9px] font-black uppercase tracking-[0.2em] text-white">© 2026 ROFF.ADMIN PANEL CONTROL</p>
    </footer>

</body>
</html>