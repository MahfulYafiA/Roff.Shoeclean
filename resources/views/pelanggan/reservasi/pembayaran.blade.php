<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selesaikan Pembayaran - ROFF.SHOECLEAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- ✨ SDK MIDTRANS (Sandbox Mode) -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #0f172a; }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 1);
            box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.05);
        }
        
        .receipt-dashed {
            border-bottom: 2px dashed #cbd5e1;
        }

        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="antialiased flex flex-col min-h-screen relative overflow-x-hidden">

    {{-- Dekorasi Latar --}}
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-100 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/3 pointer-events-none z-0"></div>
    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-cyan-100 rounded-full blur-[100px] translate-y-1/3 -translate-x-1/3 pointer-events-none z-0"></div>

    <header class="w-full px-6 py-6 absolute top-0 left-0 z-20 flex justify-between items-center">
        <a href="{{ route('reservasi.riwayat') }}" class="w-10 h-10 rounded-full bg-white border border-slate-200 text-slate-400 hover:text-cyan-500 hover:bg-cyan-50 transition-all flex items-center justify-center shadow-sm active:scale-95">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <h1 class="font-black text-lg uppercase tracking-tighter italic text-slate-900">
            ROFF.<span class="text-blue-600">PAY</span>
        </h1>
    </header>

    <main class="flex-1 flex items-center justify-center p-6 mt-16 md:mt-0 relative z-10">
        <div class="w-full max-w-lg">
            
            <div class="glass-card rounded-[2.5rem] overflow-hidden">
                
                {{-- Bagian Atas: Header & Pesan --}}
                <div class="p-8 md:p-10 text-center bg-gradient-to-b from-blue-50/50 to-white">
                    @if(session('success_msg'))
                        <div class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-600 px-4 py-1.5 rounded-full mb-6 shadow-sm border border-emerald-200">
                            <i class="fa-solid fa-check-circle text-[10px]"></i>
                            <span class="text-[9px] font-black uppercase tracking-widest">{{ session('success_msg') }}</span>
                        </div>
                    @else
                        <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner border border-blue-100">
                            <i class="fa-solid fa-wallet text-2xl"></i>
                        </div>
                    @endif

                    <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tighter mb-2 uppercase italic">
                        Selesaikan <span class="text-blue-600">Pembayaran</span>
                    </h1>
                    <p class="text-slate-500 font-medium text-xs md:text-sm leading-relaxed">
                        Order ID <span class="font-bold text-slate-800">#ORD-{{ $reservasi->id_reservasi }}</span>
                    </p>
                </div>

                {{-- Bagian Tengah: Rincian Pesanan (Struk) --}}
                <div class="px-8 md:px-10 py-6 bg-slate-50/50 border-y border-slate-100">
                    <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-receipt"></i> Rincian Layanan
                    </p>
                    
                    <div class="space-y-4 max-h-[200px] overflow-y-auto custom-scroll pr-2 mb-4">
                        @foreach($reservasi->detail as $item)
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide">{{ $item->layanan->nama_layanan ?? 'Layanan Custom' }}</h4>
                                <p class="text-[10px] text-slate-500 font-medium mt-0.5">{{ $item->jumlah }} Pasang × Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right pl-4">
                                <p class="text-xs font-black text-slate-900">Rp {{ number_format($item->sub_total, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="receipt-dashed my-4"></div>

                    <div class="flex justify-between items-center mt-4">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">Total Tagihan</p>
                        <h2 class="text-2xl font-black text-blue-600 italic tracking-tighter">
                            Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}
                        </h2>
                    </div>
                </div>

                {{-- Bagian Bawah: Tombol Aksi --}}
                <div class="p-8 md:p-10 bg-white">
                    <button id="pay-button" class="w-full bg-blue-600 hover:bg-slate-900 text-white py-4 md:py-5 rounded-2xl font-black text-[11px] md:text-xs uppercase tracking-[0.2em] shadow-xl shadow-blue-500/20 transition-all active:scale-[0.98] flex items-center justify-center gap-3 mb-4">
                        <i class="fa-solid fa-shield-halved text-lg"></i> Bayar Sekarang
                    </button>
                    
                    <div class="text-center">
                        <a href="{{ route('reservasi.riwayat') }}" class="inline-block text-[10px] font-bold text-slate-400 hover:text-slate-600 uppercase tracking-widest transition-colors underline decoration-slate-300 underline-offset-4">
                            Bayar Nanti Saja
                        </a>
                    </div>
                </div>

            </div>

            {{-- Info Keamanan --}}
            <div class="mt-8 flex justify-center items-center gap-6 opacity-50">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-lock text-sm text-slate-700"></i>
                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-700">Secure Payment</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-sm text-slate-700"></i>
                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-700">Powered by Midtrans</span>
                </div>
            </div>

        </div>
    </main>

    <script type="text/javascript">
        const payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            // Animasi loading kecil pada tombol
            payButton.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-lg"></i> Membuka Sistem...';
            payButton.classList.add('opacity-80', 'cursor-not-allowed');

            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function (result) {
                    window.location.href = "{{ route('reservasi.riwayat') }}?status=success";
                },
                onPending: function (result) {
                    window.location.href = "{{ route('reservasi.riwayat') }}?status=pending";
                },
                onError: function (result) {
                    alert("Pembayaran Gagal! Silakan coba lagi.");
                    resetButton();
                },
                onClose: function () {
                    resetButton();
                }
            });
        });

        function resetButton() {
            payButton.innerHTML = '<i class="fa-solid fa-shield-halved text-lg"></i> Bayar Sekarang';
            payButton.classList.remove('opacity-80', 'cursor-not-allowed');
        }
    </script>

</body>
</html>