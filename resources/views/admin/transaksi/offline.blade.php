<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Offline - ROFF.SHOECLEAN</title>
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
        
        /* Scrollbar */
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }

        /* ✨ Sembunyikan panah bawaan di input angka (Chrome, Safari, Edge, Opera) */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* ✨ Sembunyikan panah bawaan di input angka (Mozilla Firefox) */
        input[type="number"] {
            -moz-appearance: textfield;
            appearance: textfield;
        }
    </style>
</head>

@php
    $isSuper = auth()->user()->id_role == 1 || auth()->user()->role === 'superadmin';
    $accent = $isSuper ? 'emerald' : 'blue';
@endphp

<body class="text-slate-200 antialiased flex flex-col min-h-screen overflow-x-hidden relative custom-scroll selection:bg-{{ $accent }}-500 selection:text-white">

    {{-- CSS Khusus Focus Input berdasarkan Tema --}}
    <style>
        .dark-input:focus {
            border-color: {{ $isSuper ? '#10b981' : '#3b82f6' }};
            box-shadow: 0 0 0 4px {{ $isSuper ? 'rgba(16, 185, 129, 0.1)' : 'rgba(59, 130, 246, 0.1)' }};
            outline: none;
        }
    </style>

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

    {{-- MAIN CONTENT --}}
    <main class="flex-1 w-full px-6 py-8 md:p-12 relative z-10">
        
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-{{ $accent }}-600/5 blur-[150px] rounded-full pointer-events-none -translate-y-1/2 translate-x-1/3"></div>

        <div class="w-full max-w-[1400px] mx-auto relative z-10">
            
            {{-- HEADER TITLE --}}
            <div class="mb-8 md:mb-12">
                <div class="inline-flex items-center gap-2 bg-{{ $accent }}-500/10 border border-{{ $accent }}-500/20 px-4 py-1.5 rounded-full mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-{{ $accent }}-500 animate-pulse shadow-[0_0_10px_currentColor]"></span>
                    <p class="text-[8px] md:text-[9px] font-black text-{{ $accent }}-400 uppercase tracking-[0.4em]">Cashier System</p>
                </div>
                <h1 class="text-3xl md:text-5xl font-black text-white tracking-tighter mb-2 leading-none">
                    Kasir <span class="text-transparent bg-clip-text bg-gradient-to-r from-{{ $accent }}-400 to-teal-200 italic">Offline</span>
                </h1>
                <p class="text-slate-400 font-medium text-sm">Input pesanan pelanggan yang datang langsung ke toko secara real-time</p>
            </div>

            @if(session('success'))
                <div class="mb-8 bg-emerald-500/20 border border-emerald-500/50 text-emerald-400 px-6 py-4 rounded-2xl flex items-center gap-3 shadow-lg backdrop-blur-sm relative z-10">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    <span class="text-xs font-bold uppercase tracking-wide">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-8 bg-red-500/20 border border-red-500/50 text-red-400 px-6 py-4 rounded-2xl flex flex-col gap-2 shadow-lg backdrop-blur-sm relative z-10">
                    @foreach($errors->all() as $error)
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                            <span class="text-xs font-bold uppercase tracking-wide">{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- FORM FULL WIDTH --}}
            <form action="{{ route('admin.transaksi.store-offline') }}" method="POST" class="glass-panel rounded-[2.5rem] border border-white/5 p-6 md:p-10 shadow-2xl relative overflow-hidden group">
                
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-{{ $accent }}-600/10 rounded-full blur-[80px] translate-y-1/2 -translate-x-1/2 pointer-events-none transition-opacity duration-500 opacity-50 group-hover:opacity-100"></div>

                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 lg:gap-12 relative z-10">
                    
                    {{-- KOLOM 1: DATA PELANGGAN --}}
                    <div class="space-y-6">
                        <h3 class="flex items-center gap-4 text-sm font-black text-white uppercase tracking-widest border-b border-slate-700/50 pb-5">
                            <div class="w-10 h-10 rounded-xl bg-{{ $accent }}-500/20 border border-{{ $accent }}-500/30 text-{{ $accent }}-400 flex items-center justify-center shadow-inner"><i class="fa-solid fa-user text-base"></i></div>
                            Customer <span class="text-slate-500 text-[10px]">(Walk-in)</span>
                        </h3>
                        
                        <div>
                            <label class="block text-[10px] font-black text-{{ $accent }}-400 uppercase tracking-widest mb-2 ml-1">Nama Pelanggan <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_pelanggan" required placeholder="Contoh: Roff Pelanggan" class="dark-input w-full rounded-2xl px-5 py-4 text-sm font-bold placeholder:text-slate-600">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black text-{{ $accent }}-400 uppercase tracking-widest mb-2 ml-1">Nomor WhatsApp</label>
                            <input type="text" name="no_whatsapp" placeholder="Contoh: 08123456789" class="dark-input w-full rounded-2xl px-5 py-4 text-sm font-bold placeholder:text-slate-600">
                            <p class="text-[9px] font-bold text-slate-500 mt-2 ml-1 italic">*Opsional, jika pelanggan butuh notifikasi</p>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-{{ $accent }}-400 uppercase tracking-widest mb-2 ml-1">Pengambilan Nanti <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="metode_keluar" id="metode_keluar" required class="dark-input w-full rounded-2xl pl-5 pr-12 py-4 text-sm font-bold appearance-none cursor-pointer">
                                    <option value="Ambil Sendiri" class="bg-slate-800 text-white font-bold">🏬 Ambil Sendiri di Toko</option>
                                    <option value="Diantar Kurir" class="bg-slate-800 text-white font-bold">🛵 Diantar Kurir ke Rumah</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none"></i>
                            </div>
                        </div>

                        {{-- Wadah Alamat (Akan disembunyikan/ditampilkan via JS) --}}
                        <div id="wadah-alamat" class="hidden transition-all duration-300">
                            <label class="block text-[10px] font-black text-{{ $accent }}-400 uppercase tracking-widest mb-2 ml-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="alamat_lengkap" id="alamat_lengkap" rows="2" placeholder="Masukkan alamat lengkap..." class="dark-input w-full rounded-2xl px-5 py-3 text-sm font-bold placeholder:text-slate-600 custom-scroll"></textarea>
                        </div>
                    </div>

                    {{-- KOLOM 2: DETAIL CUCIAN --}}
                    <div class="space-y-6">
                        <h3 class="flex items-center gap-4 text-sm font-black text-white uppercase tracking-widest border-b border-slate-700/50 pb-5">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/20 border border-purple-500/30 text-purple-400 flex items-center justify-center shadow-inner"><i class="fa-solid fa-box-open text-base"></i></div>
                            Detail Cucian
                        </h3>
                        
                        <div class="space-y-3 pr-2 custom-scroll max-h-[300px] overflow-y-auto">
                            @foreach($layanans as $layanan)
                                <div class="dark-input rounded-2xl p-4 flex justify-between items-center group hover:border-purple-500/50">
                                    <div>
                                        <h4 class="font-black text-white text-sm uppercase tracking-wide">{{ $layanan->nama_layanan }}</h4>
                                        <p class="text-[10px] font-bold text-purple-400 mt-1">Rp {{ number_format($layanan->harga, 0, ',', '.') }} <span class="text-slate-500">/ psg</span></p>
                                    </div>
                                    
                                    <div class="flex items-center gap-3 bg-slate-900/80 rounded-xl p-1.5 border border-slate-700/50">
                                        <button type="button" onclick="decrement('qty-{{ $layanan->id_layanan }}')" class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:text-white hover:bg-slate-700 transition-colors">
                                            <i class="fa-solid fa-minus text-xs"></i>
                                        </button>
                                        
                                        <input type="number" name="layanan[{{ $layanan->id_layanan }}]" id="qty-{{ $layanan->id_layanan }}" data-harga="{{ $layanan->harga }}" value="0" min="0" readonly class="qty-input w-6 bg-transparent text-center text-sm font-black text-white outline-none cursor-default">
                                        
                                        <button type="button" onclick="increment('qty-{{ $layanan->id_layanan }}')" class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:text-white hover:bg-purple-500 transition-colors">
                                            <i class="fa-solid fa-plus text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-[9px] font-bold text-slate-500 italic text-center">*Biarkan angka 0 jika tidak memilih layanan tersebut</p>
                    </div>

                    {{-- KOLOM 3: PEMBAYARAN & TOTAL (REALTIME) --}}
                    <div class="space-y-6 flex flex-col h-full">
                        <h3 class="flex items-center gap-4 text-sm font-black text-white uppercase tracking-widest border-b border-slate-700/50 pb-5">
                            <div class="w-10 h-10 rounded-xl bg-orange-500/20 border border-orange-500/30 text-orange-400 flex items-center justify-center shadow-inner"><i class="fa-solid fa-cash-register text-base"></i></div>
                            Pembayaran Kasir
                        </h3>

                        {{-- ✨ KALKULATOR TOTAL REAL-TIME ✨ --}}
                        <div class="bg-slate-800/80 border border-slate-700/50 rounded-2xl p-6 shadow-inner flex flex-col items-center justify-center relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-500/10 blur-xl rounded-full"></div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Total Tagihan</p>
                            <h2 id="totalDisplay" class="text-4xl font-black text-emerald-400 tracking-tighter italic drop-shadow-md">Rp 0</h2>
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black text-orange-400 uppercase tracking-widest mb-2 ml-1">Metode Bayar <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="metode_bayar" required class="dark-input w-full rounded-2xl pl-5 pr-12 py-4 text-sm font-bold appearance-none cursor-pointer">
                                    <option value="Tunai" class="bg-slate-800 text-white font-bold">💵 Tunai (Cash)</option>
                                    <option value="Transfer BCA" class="bg-slate-800 text-white font-bold">📱 Transfer BCA</option>
                                    <option value="QRIS" class="bg-slate-800 text-white font-bold">📱 QRIS Toko</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none"></i>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black text-orange-400 uppercase tracking-widest mb-2 ml-1">Status Lunas? <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="status_lunas" required class="dark-input w-full rounded-2xl pl-5 pr-12 py-4 text-sm font-bold appearance-none cursor-pointer">
                                    <option value="Lunas" class="bg-slate-800 text-emerald-400 font-bold">✅ Sudah Lunas (Bayar di Depan)</option>
                                    <option value="Belum Lunas" class="bg-slate-800 text-red-400 font-bold">❌ Belum Lunas (Bayar Nanti)</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none"></i>
                            </div>
                        </div>

                        <div class="mt-auto pt-8">
                            <button type="submit" class="w-full bg-{{ $accent }}-600 hover:bg-{{ $accent }}-500 text-white py-4 md:py-5 rounded-2xl font-black text-[11px] md:text-xs uppercase tracking-[0.2em] shadow-xl shadow-{{ $accent }}-500/20 hover:shadow-{{ $accent }}-500/40 transition-all duration-300 active:scale-[0.98] flex items-center justify-center gap-3">
                                <i class="fa-solid fa-print text-lg"></i> Proses & Simpan Transaksi
                            </button>
                        </div>
                    </div>

                </div>
            </form>
            
        </div>
    </main>

    <footer class="w-full py-6 flex justify-center items-center opacity-40 shrink-0 mt-auto z-10 border-t border-white/5 bg-[#0f172a]/80">
        <p class="text-[9px] font-black uppercase tracking-[0.2em] text-white">© 2026 ROFF.SHOECLEAN SYSTEM</p>
    </footer>

    {{-- Script untuk Plus Minus Counter, Total Real-time, & Toggle Alamat --}}
    <script>
        // 1. Logika untuk Kalkulator Total Harga
        function calculateTotal() {
            let total = 0;
            const inputs = document.querySelectorAll('.qty-input');
            
            inputs.forEach(input => {
                const qty = parseInt(input.value) || 0;
                const harga = parseInt(input.getAttribute('data-harga')) || 0;
                total += (qty * harga);
            });

            const formattedTotal = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(total);

            document.getElementById('totalDisplay').innerText = formattedTotal;
        }

        function increment(inputId) {
            const input = document.getElementById(inputId);
            input.value = parseInt(input.value) + 1;
            calculateTotal(); 
        }

        function decrement(inputId) {
            const input = document.getElementById(inputId);
            if (parseInt(input.value) > 0) {
                input.value = parseInt(input.value) - 1;
                calculateTotal(); 
            }
        }

        // 2. Logika untuk Sembunyikan/Tampilkan Kolom Alamat
        document.addEventListener("DOMContentLoaded", function() {
            const metodeKeluar = document.getElementById('metode_keluar');
            const wadahAlamat = document.getElementById('wadah-alamat');
            const inputAlamat = document.getElementById('alamat_lengkap');

            function toggleAlamat() {
                if(metodeKeluar.value === 'Diantar Kurir') {
                    wadahAlamat.classList.remove('hidden');
                    inputAlamat.setAttribute('required', 'required');
                } else {
                    wadahAlamat.classList.add('hidden');
                    inputAlamat.removeAttribute('required');
                    inputAlamat.value = ''; // Kosongkan jika tidak jadi diantar
                }
            }

            // Jalankan saat pertama kali dimuat & setiap kali dropdown diubah
            toggleAlamat();
            metodeKeluar.addEventListener('change', toggleAlamat);
        });
    </script>
</body>
</html>