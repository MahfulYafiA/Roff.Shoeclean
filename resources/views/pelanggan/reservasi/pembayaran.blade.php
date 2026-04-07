<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran - ROFF</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fafafa; }
        .luxury-card { box-shadow: 0 40px 80px -20px rgba(15, 23, 42, 0.08); }
    </style>
</head>
<body class="text-slate-900 antialiased selection:bg-blue-600 selection:text-white">

    <main class="min-h-screen flex items-center justify-center px-4 md:px-6 py-10 md:py-12">
        <div class="max-w-xl w-full">
            
            {{-- HEADER SECTION --}}
            <div class="text-center mb-8 md:mb-10 px-2">
                <div class="inline-flex items-center justify-center w-14 h-14 md:w-16 md:h-16 bg-blue-600 text-white rounded-2xl md:rounded-[1.5rem] mb-4 md:mb-6 shadow-xl shadow-blue-500/30">
                    <svg class="w-7 h-7 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <h1 class="text-2xl md:text-3xl font-black tracking-tight italic">Selesaikan <span class="text-blue-600">Pembayaran.</span></h1>
                <p class="text-slate-500 mt-2 text-xs md:text-sm font-medium">Tinggal satu langkah lagi untuk merawat sepatu Anda.</p>
            </div>

            {{-- MAIN CARD --}}
            <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] p-6 md:p-10 border border-slate-200 luxury-card relative overflow-hidden">
                {{-- Top Blue Bar --}}
                <div class="absolute top-0 left-0 w-full h-2 bg-blue-600"></div>
                
                <div class="space-y-6">
                    {{-- Order ID Row --}}
                    <div class="flex justify-between items-center border-b border-slate-50 pb-4">
                        <span class="text-[9px] md:text-[10px] font-black uppercase tracking-widest text-slate-400">Order ID</span>
                        <span class="text-sm md:text-base font-black text-slate-900">#ORD-{{ $reservasi->id_reservasi }}</span>
                    </div>

                    {{-- Service Details --}}
                    <div class="flex justify-between items-start gap-4">
                        <div>
                            <p class="text-[9px] md:text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Layanan</p>
                            <h3 class="text-base md:text-lg font-bold italic leading-tight">{{ $reservasi->layanan->nama_layanan }}</h3>
                            <p class="text-xs md:text-sm text-slate-500 font-medium mt-1">{{ $reservasi->jumlah_sepatu }} Pasang Sepatu</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-[9px] md:text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Harga Satuan</p>
                            <p class="text-sm md:text-base font-bold text-slate-900">Rp {{ number_format($reservasi->layanan->harga, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- Total Tagihan Box --}}
                    <div class="bg-slate-50 rounded-xl md:rounded-2xl p-5 md:p-6 flex justify-between items-center mt-8 border border-slate-100">
                        <span class="font-black uppercase text-[10px] md:text-xs tracking-widest text-slate-400">Total Tagihan</span>
                        <span class="text-xl md:text-2xl font-black text-blue-600 tracking-tighter">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-8 md:mt-10">
                    <button id="pay-button" class="w-full bg-[#0f172a] text-white py-4 md:py-5 rounded-xl md:rounded-2xl font-black uppercase text-[10px] md:text-xs tracking-[0.2em] hover:bg-blue-600 transition-all shadow-xl hover:shadow-blue-500/20 flex items-center justify-center gap-3 active:scale-[0.98]">
                        Bayar Sekarang
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </button>
                    <div class="flex items-center justify-center gap-2 mt-6 opacity-40">
                        <span class="text-[8px] md:text-[9px] font-black uppercase tracking-widest text-slate-400">Secure Payment via</span>
                        <img src="https://upload.wikimedia.org/wikipedia/commons/e/e0/Midtrans.png" alt="Midtrans" class="h-2.5 md:h-3 grayscale">
                    </div>
                </div>
            </div>

            {{-- Footer Link --}}
            <div class="text-center mt-8">
                <a href="{{ route('reservasi.riwayat') }}" class="text-[10px] md:text-xs font-black uppercase tracking-[0.2em] text-slate-400 hover:text-red-600 transition-colors">Batalkan Pembayaran</a>
            </div>
        </div>
    </main>

    {{-- SCRIPTS --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function () {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function (result) { 
                    window.location.href = "{{ route('reservasi.riwayat') }}"; 
                },
                onPending: function (result) { 
                    alert("Pembayaran tertunda, silakan cek instruksi."); 
                },
                onError: function (result) { 
                    alert("Pembayaran gagal!"); 
                },
                onClose: function () {
                    alert('Anda menutup jendela pembayaran sebelum selesai.');
                }
            });
        };
    </script>
</body>
</html>