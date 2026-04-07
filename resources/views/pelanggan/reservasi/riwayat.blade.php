<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - ROFF</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fafafa; } 
        .hover-shadow-luxury { transition: all 0.4s ease; }
        .hover-shadow-luxury:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -15px rgba(0,0,0,0.05); }
        /* Animasi Modal */
        .modal-enter { opacity: 0; transform: scale(0.95); }
        .modal-enter-active { opacity: 1; transform: scale(1); transition: all 0.3s ease-out; }
    </style>
</head>
<body class="text-slate-900 antialiased min-h-screen">
    
    {{-- NAV ATAS --}}
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 w-full px-4 md:px-6 lg:px-12 py-4 md:py-5 flex justify-between items-center">
        <a href="{{ url('/dashboard') }}" class="flex items-center gap-3 md:gap-4 group transition-all shrink-0">
            <div class="bg-white border border-slate-200 rounded-xl md:rounded-2xl w-10 h-10 md:w-12 md:h-12 flex items-center justify-center shadow-sm group-hover:border-blue-400 group-hover:bg-blue-50 transition-all">
                <svg class="w-4 h-4 md:w-5 md:h-5 text-slate-400 group-hover:text-blue-600 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                </svg>
            </div>
            <span class="text-[10px] md:text-[11px] font-black uppercase tracking-[0.15em] md:tracking-[0.2em] text-slate-400 group-hover:text-blue-600 mt-0.5 hidden sm:block">Kembali Ke Dashboard</span>
        </a>
        <span class="text-[8px] md:text-[9px] font-black uppercase tracking-[0.2em] text-slate-300 whitespace-nowrap">Riwayat Pesanan</span>
    </nav>

    {{-- KONTEN UTAMA --}}
    <main class="max-w-5xl mx-auto px-4 md:px-6 py-8 md:py-12">
        <div class="mb-8 md:mb-12 text-center">
            <h1 class="text-3xl md:text-4xl font-black tracking-tight italic text-slate-900 uppercase">Riwayat <span class="text-blue-600">Cucian.</span></h1>
            <p class="text-slate-400 text-xs md:text-sm font-medium mt-2">Pantau setiap tahap pengerjaan sepatu kesayangan Anda.</p>
        </div>

        {{-- ALERT SUCCESS --}}
        @if(session('success'))
            <div class="mb-8 max-w-2xl mx-auto bg-emerald-50 border border-emerald-200 text-emerald-600 p-4 rounded-2xl flex items-center justify-center gap-3 shadow-lg shadow-emerald-500/10">
                <i class="fa-solid fa-circle-check"></i>
                <span class="text-xs font-black uppercase tracking-widest">{{ session('success') }}</span>
            </div>
        @endif

        <div class="space-y-4 md:space-y-6">
            @forelse($riwayat as $r)
            <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] border border-slate-200 p-6 md:p-8 hover-shadow-luxury transition-all">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 md:gap-8">
                    
                    {{-- Info Pesanan --}}
                    <div class="flex items-center gap-4 md:gap-6 w-full lg:w-1/3">
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-blue-50 rounded-xl md:rounded-2xl flex items-center justify-center border border-blue-100 shrink-0">
                            <i class="fa-solid fa-box-open text-blue-600 text-xl md:text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-[9px] md:text-[10px] font-black uppercase tracking-widest text-slate-400 mb-0.5 md:mb-1">Order ID #ORD-{{ $r->id_reservasi }}</p>
                            <h3 class="text-lg md:text-xl font-black italic text-slate-900 leading-tight">
                                {{ $r->detail->layanan->nama_layanan ?? 'Layanan' }}
                            </h3>
                            <p class="text-xs md:text-sm font-bold text-blue-600">Total: Rp {{ number_format($r->total_harga, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="flex-1 w-full max-w-md px-2 md:px-4">
                        <div class="relative py-4">
                            <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-100 -translate-y-1/2 rounded-full"></div>
                            @php
                                $progress = '15%';
                                if(in_array($r->status, ['Diproses', 'Dicuci'])) $progress = '50%';
                                if(in_array($r->status, ['Selesai', 'Siap Diambil', 'Menunggu Kurir'])) $progress = '100%';
                            @endphp
                            <div class="absolute top-1/2 left-0 h-1 bg-blue-600 -translate-y-1/2 rounded-full transition-all duration-1000 ease-out" 
                                 style="width: {{ $progress }}"></div>

                            <div class="relative flex justify-between">
                                <div class="flex flex-col items-center gap-2">
                                    <div class="w-3.5 h-3.5 md:w-4 md:h-4 rounded-full border-[3px] md:border-4 border-white shadow-sm {{ $r->status != '' ? 'bg-blue-600' : 'bg-slate-300' }}"></div>
                                    <span class="text-[8px] md:text-[9px] font-black uppercase tracking-widest {{ $r->status != '' ? 'text-blue-600' : 'text-slate-400' }}">Booking</span>
                                </div>
                                <div class="flex flex-col items-center gap-2">
                                    <div class="w-3.5 h-3.5 md:w-4 md:h-4 rounded-full border-[3px] md:border-4 border-white shadow-sm {{ in_array($r->status, ['Diproses', 'Dicuci', 'Selesai', 'Siap Diambil', 'Menunggu Kurir']) ? 'bg-blue-600' : 'bg-slate-300' }}"></div>
                                    <span class="text-[8px] md:text-[9px] font-black uppercase tracking-widest {{ in_array($r->status, ['Diproses', 'Dicuci', 'Selesai', 'Siap Diambil', 'Menunggu Kurir']) ? 'text-blue-600' : 'text-slate-400' }}">Process</span>
                                </div>
                                <div class="flex flex-col items-center gap-2">
                                    <div class="w-3.5 h-3.5 md:w-4 md:h-4 rounded-full border-[3px] md:border-4 border-white shadow-sm {{ in_array($r->status, ['Selesai', 'Siap Diambil', 'Menunggu Kurir']) ? 'bg-blue-600' : 'bg-slate-300' }}"></div>
                                    <span class="text-[8px] md:text-[9px] font-black uppercase tracking-widest {{ in_array($r->status, ['Selesai', 'Siap Diambil', 'Menunggu Kurir']) ? 'text-blue-600' : 'text-slate-400' }}">Ready</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="shrink-0 w-full lg:w-auto flex flex-col items-center lg:items-end justify-center">
                        
                        @if($r->status == 'Menunggu Pembayaran')
                            <a href="{{ route('reservasi.pembayaran', $r->id_reservasi) }}" class="bg-blue-600 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-900 transition-all shadow-lg shadow-blue-500/20 w-full text-center">Bayar Sekarang</a>
                        
                        @elseif($r->status == 'Selesai' && empty($r->metode_pengembalian))
                            <div class="flex flex-col items-center lg:items-end gap-3 w-full">
                                <span class="text-[9px] font-black text-red-500 uppercase tracking-widest animate-pulse bg-red-50 px-4 py-1.5 rounded-full border border-red-100 flex items-center gap-2">
                                    <i class="fa-solid fa-bell"></i> Pilih Pengambilan
                                </span>
                                <div class="flex w-full lg:w-auto gap-2">
                                    {{-- Form Ambil Sendiri --}}
                                    <form action="{{ route('reservasi.pilih-pengembalian', $r->id_reservasi) }}" method="POST" class="flex-1 lg:flex-none">
                                        @csrf
                                        <input type="hidden" name="metode" value="Ambil di Toko">
                                        <button type="submit" class="w-full bg-white border-2 border-blue-200 text-blue-600 hover:bg-blue-50 px-4 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm">
                                            Ambil Sendiri
                                        </button>
                                    </form>
                                    {{-- Button Modal Antar Kurir --}}
                                    <button type="button" onclick="openCourierModal('{{ $r->id_reservasi }}')" 
                                        class="flex-1 lg:flex-none bg-slate-900 border-2 border-slate-900 text-white hover:bg-slate-800 px-4 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm">
                                        Antar Kurir
                                    </button>
                                </div>
                            </div>

                        @elseif(!empty($r->metode_pengembalian))
                            <div class="text-center lg:text-right w-full">
                                <div class="px-8 py-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 font-black text-xs uppercase tracking-widest italic w-full">
                                    {{ $r->status }}
                                </div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mt-2">
                                    Via: <span class="text-blue-600">{{ $r->metode_pengembalian }}</span>
                                </p>
                            </div>

                        @else
                            <div class="px-8 py-4 rounded-2xl bg-slate-50 border border-slate-100 text-slate-500 font-black text-xs uppercase tracking-widest text-center italic w-full">
                                {{ $r->status }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-20 bg-white rounded-[3rem] border border-dashed border-slate-200 px-6">
                <p class="text-slate-400 font-medium italic text-sm">Belum ada riwayat pemesanan.</p>
                <a href="{{ route('reservasi.create') }}" class="text-blue-600 font-black text-xs uppercase tracking-widest mt-6 inline-block hover:underline">Mulai Cuci Sekarang →</a>
            </div>
            @endforelse
        </div>
    </main>

    {{-- MODAL ANTAR KURIR --}}
    <div id="courierModal" class="fixed inset-0 z-[100] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl border border-white/20">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-black text-xl text-slate-900 uppercase italic">Detail <span class="text-blue-600">Kurir.</span></h3>
                <button onclick="closeCourierModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="courierForm" method="POST">
                @csrf
                <input type="hidden" name="metode" value="Diantar ke Alamat">
                
                <div class="space-y-5">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block ml-1">Nomor WhatsApp Aktif</label>
                        <input type="text" name="wa_pengantaran" required placeholder="Contoh: 0812345xxx" value="{{ auth()->user()->no_telp }}"
                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block ml-1">Alamat Pengantaran Sepatu</label>
                        <textarea name="alamat_pengantaran" required placeholder="Tulis alamat lengkap (No rumah, jalan, patokan)"
                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all h-32 resize-none">{{ auth()->user()->alamat }}</textarea>
                    </div>
                </div>

                <button type="submit" class="w-full mt-8 bg-slate-900 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:bg-blue-600 hover:shadow-blue-500/30 transition-all duration-300">
                    Konfirmasi Pengantaran
                </button>
            </form>
        </div>
    </div>

    <script>
        function openCourierModal(id) {
            const modal = document.getElementById('courierModal');
            const form = document.getElementById('courierForm');
            
            // Set action URL secara dinamis berdasarkan ID Reservasi
            form.action = "{{ url('/reservasi/pilih-pengembalian') }}/" + id;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeCourierModal() {
            const modal = document.getElementById('courierModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Close modal saat klik area luar
        window.onclick = function(event) {
            const modal = document.getElementById('courierModal');
            if (event.target == modal) {
                closeCourierModal();
            }
        }
    </script>

</body>
</html>