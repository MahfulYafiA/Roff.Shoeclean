<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran - ROFF.SHOECLEAN</title>
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
        .orb-1, .orb-2 {
            position: fixed; border-radius: 50%; filter: blur(140px); z-index: -2;
            animation: floatOrb 15s infinite alternate ease-in-out;
        }
        .orb-1 { width: 600px; height: 600px; background: rgba(37, 99, 235, 0.15); top: -100px; left: -100px; }
        .orb-2 { width: 700px; height: 700px; background: rgba(6, 182, 212, 0.15); bottom: -200px; right: -100px; animation-delay: -5s; }
        
        @keyframes floatOrb {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(100px, 80px) scale(1.2); }
        }

        .glass-nav { 
            background: rgba(255, 255, 255, 0.65); 
            backdrop-filter: blur(28px); 
            border-bottom: 1px solid rgba(255, 255, 255, 0.5); 
        }
        
        .glass-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.7));
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 1);
            box-shadow: 0 24px 48px -12px rgba(31, 38, 135, 0.08);
        }
    </style>
</head>
<body class="antialiased flex flex-col min-h-screen relative">

    <div class="noise"></div>
    <div class="bg-mesh"></div>
    <div class="orb-1"></div>
    <div class="orb-2"></div>

    <header class="glass-nav px-6 md:px-12 py-4 flex justify-between items-center shrink-0 z-40 sticky top-0">
        <div class="flex items-center gap-3 md:gap-4">
            <a href="{{ route('reservasi.riwayat') }}" class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all flex items-center justify-center shadow-sm group active:scale-95" title="Kembali ke Riwayat">
                <i class="fa-solid fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <h1 class="block font-black text-xl md:text-2xl uppercase tracking-tighter italic text-slate-900 leading-tight">
                ROFF.<span class="text-blue-600">CHECKOUT</span>
            </h1>
        </div>
        <div class="hidden sm:block text-right">
            <p class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-400">Secure Payment Gateway</p>
        </div>
    </header>

    <main class="flex-1 w-full relative z-10 p-4 md:p-10 lg:p-16 flex flex-col items-center justify-center">
        <div class="w-full max-w-[90vw] xl:max-w-[1200px]">

            <div class="mb-10 lg:mb-16 text-center">
                <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-100 px-4 py-1.5 rounded-full mb-4 lg:mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                    <p class="text-[8px] md:text-[9px] lg:text-[10px] font-black text-blue-600 uppercase tracking-[0.4em]">Payment Confirmation</p>
                </div>
                <h2 class="text-4xl md:text-5xl lg:text-7xl font-black text-slate-900 tracking-tighter uppercase italic leading-none">Selesaikan <span class="text-blue-600">Pesanan.</span></h2>
                <p class="text-slate-500 font-medium text-xs md:text-sm lg:text-base mt-4 lg:mt-6">Data reservasi Anda telah kami amankan. Silakan lanjut ke gerbang pembayaran.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-stretch">
                
                {{-- KIRI: INFORMASI TAGIHAN --}}
                <div class="lg:col-span-6 space-y-6 flex flex-col h-full">
                    <div class="glass-card rounded-[2.5rem] lg:rounded-[3rem] p-8 lg:p-12 border-blue-100 relative overflow-hidden flex-1 flex flex-col justify-center">
                        <div class="absolute top-0 right-0 w-32 h-32 lg:w-48 lg:h-48 bg-blue-50 rounded-bl-full -z-10"></div>
                        <p class="text-[10px] lg:text-xs font-black text-slate-400 uppercase tracking-widest mb-2 lg:mb-4">Total Tagihan Anda</p>
                        <h3 class="text-4xl md:text-5xl lg:text-6xl font-black text-blue-600 italic tracking-tighter">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</h3>
                        
                        <div class="mt-8 lg:mt-12 pt-8 lg:pt-10 border-t border-slate-200/60 space-y-4 lg:space-y-6">
                            <div class="flex justify-between items-center text-[11px] lg:text-[13px] font-bold uppercase tracking-widest">
                                <span class="text-slate-400">Order ID</span>
                                <span class="text-slate-900 bg-slate-100 px-3 py-1 rounded-md">#ORD-{{ $reservasi->id_reservasi }}</span>
                            </div>
                            <div class="flex justify-between items-center text-[11px] lg:text-[13px] font-bold uppercase tracking-widest">
                                <span class="text-slate-400">Total Muatan</span>
                                {{-- ✅ UPDATE: Menghitung total sepatu dari semua rincian detail --}}
                                <span class="text-slate-900 font-black italic">{{ $reservasi->detail->sum('jumlah') }} Pasang Sepatu</span>
                            </div>
                            <div class="flex justify-between items-start text-[11px] lg:text-[13px] font-bold uppercase tracking-widest">
                                <span class="text-slate-400 shrink-0">Daftar Jasa</span>
                                <div class="text-right flex flex-col gap-1">
                                    @foreach($reservasi->detail as $det)
                                        <span class="text-slate-700 text-[10px] lg:text-[12px]">{{ $det->layanan->nama_layanan }} ({{ $det->jumlah }}x)</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KANAN: TOMBOL MIDTRANS --}}
                <div class="lg:col-span-6 flex flex-col h-full">
                    <div class="glass-card rounded-[2.5rem] lg:rounded-[3rem] p-8 lg:p-14 h-full flex flex-col justify-center items-center text-center border-slate-200">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/e/e0/Midtrans.png" class="h-10 lg:h-14 mb-8 drop-shadow-sm" alt="Midtrans">
                        <h2 class="text-2xl lg:text-3xl font-black text-slate-900 mb-3 uppercase italic tracking-tight">Checkout Instan</h2>
                        <p class="text-[11px] lg:text-sm text-slate-500 mb-10 font-medium leading-relaxed max-w-sm">
                            Klik tombol di bawah untuk membuka jendela pembayaran aman Midtrans. Kami mendukung QRIS, GoPay, dan Transfer Bank.
                        </p>
                        
                        <button id="pay-button" class="w-full max-w-md bg-blue-600 text-white py-5 lg:py-6 rounded-[1.5rem] lg:rounded-[2rem] font-black uppercase text-xs lg:text-sm tracking-[0.2em] shadow-2xl shadow-blue-500/30 hover:bg-slate-900 hover:shadow-slate-900/20 hover:-translate-y-1 transition-all duration-300 active:scale-95 flex items-center justify-center gap-3">
                            Lanjut ke Pembayaran <i class="fa-solid fa-chevron-right text-sm"></i>
                        </button>
                    </div>
                </div>

            </div>

            <div class="text-center mt-12 lg:mt-16 mb-6">
                <p class="text-[9px] lg:text-[10px] font-black uppercase tracking-[0.5em] text-slate-300">© 2026 ROFF.SHOECLEAN SECURE SYSTEM</p>
            </div>
        </div>
    </main>

    {{-- SCRIPT MIDTRANS --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function () {
            const btn = document.getElementById('pay-button');
            const originalText = btn.innerHTML;
            
            btn.innerHTML = 'MENGHUBUNGKAN... <i class="fa-solid fa-circle-notch fa-spin ml-2"></i>';
            btn.disabled = true;

            snap.pay('{{ $snapToken }}', {
                onSuccess: function (result) { 
                    window.location.href = "{{ route('reservasi.riwayat') }}"; 
                },
                onPending: function (result) { 
                    window.location.href = "{{ route('reservasi.riwayat') }}";
                },
                onError: function (result) { 
                    alert("Terjadi kesalahan pada pembayaran."); 
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                },
                onClose: function () {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            });
        };
    </script>
</body>
</html>