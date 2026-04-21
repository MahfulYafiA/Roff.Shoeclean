<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - ROFF.SHOECLEAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { 
            --primary: #2563eb; 
            --surface: #f8fafc; 
            --text: #0f172a; 
        }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--surface); 
            color: var(--text); 
            overflow-x: hidden; 
        }
        
        .noise { 
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; 
            pointer-events: none; z-index: 9999; opacity: 0.035; 
            background: url('data:image/svg+xml;utf8,%3Csvg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"%3E%3Cfilter id="noiseFilter"%3E%3CfeTurbulence type="fractalNoise" baseFrequency="0.65" numOctaves="3" stitchTiles="stitch"/%3E%3C/filter%3E%3Crect width="100%25" height="100%25" filter="url(%23noiseFilter)"/%3E%3C/svg%3E'); 
        }
        .bg-mesh {
            position: fixed; inset: 0; z-index: -3;
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 32px 32px; 
            opacity: 0.5;
        }
        .orb-1, .orb-2, .orb-3 {
            position: fixed; border-radius: 50%; filter: blur(140px); z-index: -2;
            animation: floatOrb 15s infinite alternate ease-in-out;
        }
        .orb-1 { width: 600px; height: 600px; background: rgba(37, 99, 235, 0.15); top: -100px; left: -100px; }
        .orb-2 { width: 700px; height: 700px; background: rgba(6, 182, 212, 0.15); bottom: -200px; right: -100px; animation-delay: -5s; }
        .orb-3 { width: 500px; height: 500px; background: rgba(139, 92, 246, 0.1); top: 40%; left: 30%; animation-delay: -10s; }
        
        @keyframes floatOrb {
            0% { transform: translate(0, 0) scale(1) rotate(0deg); }
            100% { transform: translate(100px, 80px) scale(1.2) rotate(10deg); }
        }

        .glass-nav { 
            background: rgba(255, 255, 255, 0.65); 
            backdrop-filter: blur(28px); 
            border-bottom: 1px solid rgba(255, 255, 255, 0.5); 
        }
        
        .glass-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.5));
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 16px 48px -12px rgba(31, 38, 135, 0.08);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .glass-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 32px 64px -16px rgba(37, 99, 235, 0.15);
        }

        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="antialiased selection:bg-cyan-500 selection:text-white flex flex-col h-screen overflow-hidden relative">

    <div class="noise"></div>
    <div class="bg-mesh"></div>
    <div class="orb-1"></div>
    <div class="orb-2"></div>
    <div class="orb-3"></div>

    <header class="glass-nav px-6 md:px-12 py-4 flex justify-between items-center shrink-0 z-40 relative">
        <div class="flex items-center gap-3 md:gap-4">
            <a href="{{ url('/dashboard') }}" class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-white border border-slate-200 text-slate-400 hover:text-cyan-500 hover:bg-cyan-50 transition-all flex items-center justify-center shadow-sm group active:scale-95">
                <i class="fa-solid fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <h1 class="block font-black text-xl md:text-2xl uppercase tracking-tighter italic text-slate-900 leading-tight">
                ROFF.<span class="text-cyan-500">MEMBER</span>
            </h1>
        </div>
        <div class="text-right shrink-0">
            <p class="text-[8px] md:text-[10px] font-bold uppercase tracking-widest text-slate-500 leading-none mb-1">Riwayat Pesanan</p>
            <p class="font-black text-xs md:text-sm text-slate-900 uppercase tracking-widest">Semua Data</p>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto custom-scroll w-full relative z-10 p-4 md:p-6 lg:p-10">
        <div class="max-w-5xl mx-auto">
            
            <div class="mb-8 md:mb-10 text-center px-2">
                <div class="inline-flex items-center gap-2 bg-cyan-50 border border-cyan-100 px-4 py-1.5 rounded-full mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 animate-pulse shadow-[0_0_10px_rgba(6,182,212,0.5)]"></span>
                    <p class="text-[8px] md:text-[9px] font-black text-cyan-600 uppercase tracking-[0.4em]">Tracking & Billing</p>
                </div>
                <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tighter mb-2 uppercase italic leading-none">
                    Riwayat <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-500">Cucian.</span>
                </h1>
                <p class="text-slate-500 font-medium text-xs md:text-sm mt-2">Pantau setiap tahap pengerjaan sepatu kesayangan Anda.</p>
            </div>

            @if(session('success'))
                <div class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-600 p-5 rounded-[1.5rem] flex items-center justify-center gap-3 shadow-lg shadow-emerald-500/10 animate-pulse">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    <span class="text-xs font-black uppercase tracking-widest">{{ session('success') }}</span>
                </div>
            @endif

            <div class="space-y-5 md:space-y-6">
                @forelse($riwayat as $r)
                <div class="glass-card rounded-[2rem] md:rounded-[2.5rem] p-6 md:p-8 group flex flex-col">
                    
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 md:gap-8">
                        {{-- Info Pesanan --}}
                        <div class="flex items-center gap-4 md:gap-6 w-full lg:w-1/3">
                            <div class="w-14 h-14 md:w-16 md:h-16 bg-white rounded-[1.2rem] flex items-center justify-center border border-slate-200 shadow-sm shrink-0 group-hover:border-cyan-300 transition-colors">
                                <i class="fa-solid fa-box-open text-cyan-500 text-xl md:text-2xl group-hover:scale-110 transition-transform"></i>
                            </div>
                            <div>
                                <p class="text-[9px] md:text-[10px] font-black uppercase tracking-widest text-slate-400 mb-0.5 md:mb-1">Order ID #ORD-{{ $r->id_reservasi }}</p>
                                <h3 class="text-lg md:text-xl font-black italic text-slate-900 leading-tight group-hover:text-cyan-600 transition-colors">
                                    {{ $r->layanan->first()?->nama_layanan ?? 'Layanan' }}
                                </h3>
                                <p class="text-xs md:text-sm font-bold text-slate-600 mt-1">Total: <span class="text-cyan-600 font-black">Rp {{ number_format($r->total_harga, 0, ',', '.') }}</span></p>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="flex-1 w-full max-w-md px-2 md:px-4">
                            <div class="relative py-4">
                                <div class="absolute top-1/2 left-0 w-full h-1.5 bg-slate-200/60 -translate-y-1/2 rounded-full"></div>
                                @php
                                    $progress = '15%';
                                    if(in_array($r->status, ['Diproses', 'Dicuci'])) $progress = '50%';
                                    if(in_array($r->status, ['Selesai', 'Siap Diambil', 'Menunggu Kurir', 'Sedang Diantar'])) $progress = '100%';
                                @endphp
                                <div class="absolute top-1/2 left-0 h-1.5 bg-gradient-to-r from-cyan-400 to-blue-500 -translate-y-1/2 rounded-full transition-all duration-1000 ease-out shadow-[0_0_10px_rgba(6,182,212,0.5)]" 
                                     style="width: {{ $progress }}"></div>

                                <div class="relative flex justify-between">
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="w-4 h-4 md:w-5 md:h-5 rounded-full border-4 border-white shadow-sm {{ $r->status != '' ? 'bg-cyan-500' : 'bg-slate-300' }} transition-colors z-10"></div>
                                        <span class="text-[8px] md:text-[9px] font-black uppercase tracking-widest {{ $r->status != '' ? 'text-cyan-600' : 'text-slate-400' }}">Booking</span>
                                    </div>
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="w-4 h-4 md:w-5 md:h-5 rounded-full border-4 border-white shadow-sm {{ in_array($r->status, ['Diproses', 'Dicuci', 'Selesai', 'Siap Diambil', 'Menunggu Kurir', 'Sedang Diantar']) ? 'bg-blue-500' : 'bg-slate-300' }} transition-colors z-10"></div>
                                        <span class="text-[8px] md:text-[9px] font-black uppercase tracking-widest {{ in_array($r->status, ['Diproses', 'Dicuci', 'Selesai', 'Siap Diambil', 'Menunggu Kurir', 'Sedang Diantar']) ? 'text-blue-600' : 'text-slate-400' }}">Process</span>
                                    </div>
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="w-4 h-4 md:w-5 md:h-5 rounded-full border-4 border-white shadow-sm {{ in_array($r->status, ['Selesai', 'Siap Diambil', 'Menunggu Kurir', 'Sedang Diantar']) ? 'bg-purple-500' : 'bg-slate-300' }} transition-colors z-10"></div>
                                        <span class="text-[8px] md:text-[9px] font-black uppercase tracking-widest {{ in_array($r->status, ['Selesai', 'Siap Diambil', 'Menunggu Kurir', 'Sedang Diantar']) ? 'text-purple-600' : 'text-slate-400' }}">Ready</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Status Badge / Bayar Sekarang --}}
                        <div class="shrink-0 w-full lg:w-auto flex flex-col items-center lg:items-end justify-center">
                            @if($r->status_bayar == 'Belum Lunas')
                                <a href="{{ route('reservasi.pembayaran', $r->id_reservasi) }}" class="bg-cyan-500 text-white px-8 py-4 rounded-[1rem] font-black text-xs uppercase tracking-widest hover:bg-slate-900 transition-all shadow-lg shadow-cyan-500/30 w-full text-center hover:scale-105 active:scale-95">Bayar Sekarang</a>
                            @else
                                <div class="px-8 py-4 rounded-xl bg-white border border-slate-200 text-slate-800 font-black text-[10px] md:text-xs uppercase tracking-[0.2em] text-center italic w-full shadow-sm flex items-center justify-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-cyan-500 animate-ping"></span> {{ $r->status }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- ✅ INFO LOGISTIK (2A & 2B) --}}
                    <div class="mt-8 pt-6 border-t border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50/50 p-6 rounded-[2rem]">
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 mb-2">2A. Penyerahan Ke Toko</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-blue-500 shadow-sm border border-slate-100">
                                    <i class="fa-solid {{ $r->metode_masuk == 'Jemput Kurir' ? 'fa-motorcycle' : 'fa-store' }}"></i>
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 text-sm italic uppercase tracking-tight">{{ $r->metode_masuk }}</p>
                                    <p class="text-[10px] text-slate-500 font-medium">Status: {{ $r->status == 'Menunggu Konfirmasi' ? 'Menunggu' : 'Diterima' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="md:border-l md:border-slate-200 md:pl-8">
                            <p class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 mb-2">2B. Pengambilan Selesai</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-purple-500 shadow-sm border border-slate-100">
                                    <i class="fa-solid {{ $r->metode_keluar == 'Antar Kurir' ? 'fa-truck-fast' : 'fa-hand-holding-box' }}"></i>
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 text-sm italic uppercase tracking-tight">{{ $r->metode_keluar }}</p>
                                    <p class="text-[10px] text-slate-500 font-medium">Status: {{ $r->status == 'Selesai' ? 'Siap' : 'Menunggu Selesai' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Pembayaran --}}
                    @if($r->pembayaran)
                    <div class="mt-6 pt-5 border-t border-dashed border-slate-200 w-full">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fa-solid fa-file-invoice-dollar text-cyan-500 text-xs"></i>
                            <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-500">Detail Pembayaran</h4>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Metode</p>
                                <p class="text-[10px] font-black text-slate-800">{{ $r->pembayaran->metode_bayar }}</p>
                            </div>
                            <div>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Status</p>
                                <span class="px-2.5 py-1 rounded-md text-[9px] font-black uppercase {{ $r->status_bayar == 'Lunas' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-500' }}">
                                    {{ $r->status_bayar }}
                                </span>
                            </div>
                            <div>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tanggal</p>
                                <p class="text-[10px] font-black text-slate-800">{{ $r->pembayaran->tanggal ? \Carbon\Carbon::parse($r->pembayaran->tanggal)->format('d M Y') : '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total</p>
                                <p class="text-[10px] font-black text-cyan-600">Rp {{ number_format($r->total_harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
                @empty
                <div class="text-center py-20 glass-card rounded-[3rem] px-6">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                        <i class="fa-solid fa-box-open text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 uppercase tracking-tighter mb-1">Belum Ada Riwayat</h3>
                    <p class="text-slate-500 font-medium text-sm mb-6">Anda belum pernah melakukan pemesanan cucian sepatu.</p>
                    <a href="{{ route('reservasi.create') }}" class="bg-cyan-500 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-900 transition-all shadow-lg shadow-cyan-500/30 inline-block">Buat Pesanan Baru</a>
                </div>
                @endforelse
            </div>

            <div class="mt-auto pt-10 pb-4 flex justify-center items-center shrink-0 z-10">
                <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 text-center">© 2026 ROFF.MEMBER LOUNGE</p>
            </div>
        </div>
    </main>

</body>
</html>