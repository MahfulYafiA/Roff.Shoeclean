<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Reservasi - ROFF.SHOECLEAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #2563eb; --surface: #f8fafc; --text: #0f172a; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--surface); color: var(--text); overflow-x: hidden; }
        
        .glass-nav { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(0,0,0,0.05); }
        .glass-card { background: white; border: 1px solid rgba(0,0,0,0.05); box-shadow: 0 4px 20px -5px rgba(0,0,0,0.05); }

        /* ✅ HAPUS TOTAL IKON PANAH INPUT NUMBER */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }

        .bg-mesh { position: fixed; inset: 0; z-index: -3; background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 32px 32px; opacity: 0.3; }
        
        /* Custom Checkbox Behavior */
        input[type="radio"]:checked + div { border-color: #2563eb; background-color: rgba(37, 99, 235, 0.03); }
        input[type="radio"]:checked + div h4 { color: #2563eb; }

        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="antialiased flex flex-col min-h-screen relative">

    <div class="bg-mesh"></div>

    {{-- HEADER --}}
    <header class="glass-nav sticky top-0 px-4 md:px-10 py-4 flex justify-between items-center z-50">
        <div class="flex items-center gap-3">
            <a href="{{ url('/dashboard') }}" class="w-10 h-10 rounded-full bg-white border border-slate-200 text-slate-400 hover:text-blue-600 flex items-center justify-center transition-all shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="font-black text-xl md:text-2xl uppercase tracking-tighter italic text-slate-900">
                ROFF.<span class="text-blue-600">MEMBER</span>
            </h1>
        </div>
        <div class="hidden md:block text-right">
            <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Online Booking</p>
            <p class="font-bold text-xs text-slate-400 uppercase">Sesi Baru</p>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 w-full max-w-screen-2xl mx-auto p-4 md:p-10">
        
        <div class="mb-12 text-center">
            <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-100 px-4 py-1.5 rounded-full mb-4">
                <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                <p class="text-[9px] font-black text-blue-600 uppercase tracking-[0.3em]">Reservation System</p>
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tighter uppercase italic leading-none">
                Form <span class="text-blue-600">Reservasi.</span>
            </h1>
        </div>

        {{-- ✅ RADAR ERROR: Akan muncul jika validasi Controller gagal --}}
        @if ($errors->any())
            <div class="mb-8 bg-rose-50 border-2 border-rose-200 text-rose-600 p-6 rounded-[2rem] shadow-lg animate-bounce">
                <div class="flex items-center gap-3 mb-2">
                    <i class="fa-solid fa-circle-exclamation text-xl"></i>
                    <h4 class="font-black uppercase tracking-widest text-xs">Pemesanan Terhenti:</h4>
                </div>
                <ul class="list-disc list-inside text-xs font-bold space-y-1 ml-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="formReservasi" action="{{ route('reservasi.store') }}" method="POST" class="space-y-10">
            @csrf

            {{-- STEP 1: LAYANAN --}}
            <section class="glass-card rounded-[2.5rem] p-6 md:p-10">
                <h3 class="font-black text-lg md:text-xl uppercase tracking-widest mb-8 flex items-center gap-4 text-slate-900">
                    <span class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-xs">1</span> 
                    Pilih Jenis Layanan
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($layanans as $l)
                    <div class="bg-slate-50/50 border-2 border-slate-100 rounded-3xl p-6 flex items-center justify-between group transition-all hover:border-blue-200">
                        <div>
                            <h4 class="font-black text-slate-800 italic uppercase text-base tracking-tight mb-1">{{ $l->nama_layanan }}</h4>
                            <p class="text-blue-600 font-black text-sm">Rp {{ number_format($l->harga, 0, ',', '.') }} <span class="text-[10px] text-slate-400 font-bold tracking-widest uppercase">/ ps</span></p>
                        </div>
                        
                        <div class="flex items-center gap-3 bg-white border border-slate-200 p-1.5 rounded-2xl shadow-sm">
                            <button type="button" onclick="changeQty('{{ $l->id_layanan }}', -1)" class="w-9 h-9 rounded-xl bg-slate-50 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all">
                                <i class="fa-solid fa-minus text-[10px]"></i>
                            </button>
                            <input type="number" name="layanan[{{ $l->id_layanan }}][jumlah]" id="qty-{{ $l->id_layanan }}" value="0" min="0" data-harga="{{ $l->harga }}" class="w-10 bg-transparent text-center font-black text-slate-900 text-lg outline-none layanan-qty">
                            <button type="button" onclick="changeQty('{{ $l->id_layanan }}', 1)" class="w-9 h-9 rounded-xl bg-slate-50 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                                <i class="fa-solid fa-plus text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>

            {{-- STEP 2: PENYERAHAN & PENGAMBILAN --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                {{-- 2A --}}
                <section class="glass-card rounded-[2.5rem] p-6 md:p-10 flex flex-col">
                    <h3 class="font-black text-lg uppercase tracking-widest mb-8 flex items-center gap-3 text-slate-900">
                        <span class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-xs">2A</span> 
                        Metode Penyerahan
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 flex-1 items-stretch">
                        <label class="cursor-pointer group">
                            <input type="radio" name="metode_masuk" value="Antar Sendiri" class="sr-only delivery-in-radio" checked required>
                            <div class="border-2 border-slate-100 bg-slate-50/30 rounded-2xl p-6 transition-all h-full flex flex-col justify-center">
                                <h4 class="font-black text-xs text-slate-800 uppercase italic mb-1 tracking-tight">Antar Sendiri</h4>
                                <p class="text-[10px] text-slate-400 font-medium leading-relaxed">Antar sepatu langsung ke toko.</p>
                            </div>
                        </label>
                        <label class="cursor-pointer group">
                            <input type="radio" name="metode_masuk" value="Jemput Kurir" class="sr-only delivery-in-radio">
                            <div class="border-2 border-slate-100 bg-slate-50/30 rounded-2xl p-6 transition-all h-full flex flex-col justify-center">
                                <h4 class="font-black text-xs text-slate-800 uppercase italic mb-1 tracking-tight">Dijemput Kurir</h4>
                                <p class="text-[10px] text-slate-400 font-medium leading-relaxed">Kurir kami ambil ke lokasi Anda.</p>
                            </div>
                        </label>
                    </div>
                </section>

                {{-- 2B --}}
                <section class="glass-card rounded-[2.5rem] p-6 md:p-10 flex flex-col">
                    <h3 class="font-black text-lg uppercase tracking-widest mb-8 flex items-center gap-3 text-slate-900">
                        <span class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-xs">2B</span> 
                        Metode Pengambilan
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 flex-1 items-stretch">
                        <label class="cursor-pointer group">
                            <input type="radio" name="metode_keluar" value="Ambil Sendiri" class="sr-only delivery-out-radio" checked required>
                            <div class="border-2 border-slate-100 bg-slate-50/30 rounded-2xl p-6 transition-all h-full flex flex-col justify-center">
                                <h4 class="font-black text-xs text-slate-800 uppercase italic mb-1 tracking-tight">Ambil Sendiri</h4>
                                <p class="text-[10px] text-slate-400 font-medium leading-relaxed">Ambil di toko jika pengerjaan selesai.</p>
                            </div>
                        </label>
                        <label class="cursor-pointer group">
                            <input type="radio" name="metode_keluar" value="Antar Kurir" class="sr-only delivery-out-radio">
                            <div class="border-2 border-slate-100 bg-slate-50/30 rounded-2xl p-6 transition-all h-full flex flex-col justify-center">
                                <h4 class="font-black text-xs text-slate-800 uppercase italic mb-1 tracking-tight">Diantar Kurir</h4>
                                <p class="text-[10px] text-slate-400 font-medium leading-relaxed">Kami antar kembali ke rumah Anda.</p>
                            </div>
                        </label>
                    </div>
                </section>
            </div>

            {{-- STEP 3: ALAMAT --}}
            <section class="glass-card rounded-[2.5rem] p-6 md:p-10">
                <h3 class="font-black text-lg md:text-xl uppercase tracking-widest mb-8 flex items-center gap-4 text-slate-900">
                    <span class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-xs">3</span> 
                    Alamat & Kapasitas
                </h3>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
                    <div class="bg-slate-900 text-white p-8 rounded-[2rem] flex flex-col justify-center relative overflow-hidden">
                        <p class="text-[10px] font-black text-blue-400 uppercase tracking-[0.3em] mb-2">Total Pesanan</p>
                        <p class="text-6xl font-black italic"><span id="totalSepatuDisplay">0</span></p>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-2">Pasang Sepatu</p>
                    </div>
                    
                    <div id="areaAlamat" class="lg:col-span-2 hidden transition-all duration-500">
                        <textarea name="alamat_jemput" id="alamat_jemput" placeholder="Tulis Alamat Lengkap Penjemputan/Pengantaran (Nama Jalan, No. Rumah, Patokan)..." 
                                  class="w-full h-full bg-slate-50 border-2 border-slate-100 text-slate-900 text-base rounded-[2rem] p-8 font-medium focus:border-blue-500 focus:bg-white outline-none transition-all shadow-inner min-h-[160px]">{{ old('alamat_jemput') }}</textarea>
                    </div>
                </div>
            </section>

            {{-- FOOTER CHECKOUT --}}
            <div class="bg-white border-2 border-blue-600/10 p-6 md:p-10 rounded-[3rem] flex flex-col md:flex-row justify-between items-center gap-8 shadow-2xl relative overflow-hidden mt-10">
                
                {{-- Field Metode Pembayaran Tersembunyi tapi Tetap Dikirim --}}
                <input type="hidden" name="metode_pembayaran" value="Payment Gateway">

                <div class="text-center md:text-left z-10">
                    <p class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400 mb-1">Total Tagihan</p>
                    <h2 class="text-5xl md:text-7xl font-black text-blue-600 italic tracking-tighter" id="totalTagihan">Rp 0</h2>
                </div>
                <button type="submit" id="btnSubmit" class="w-full md:w-auto px-12 md:px-20 py-6 md:py-8 bg-blue-600 text-white font-black uppercase text-xs md:text-sm tracking-[0.3em] rounded-[2rem] shadow-xl shadow-blue-500/30 active:scale-95 transition-all hover:bg-slate-900 flex items-center justify-center gap-4 group z-10 disabled:opacity-30 disabled:grayscale">
                    Lanjut Checkout <i class="fa-solid fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                </button>
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-full -z-0"></div>
            </div>
        </form>

        <footer class="py-12 text-center opacity-20">
            <p class="text-[9px] font-black uppercase tracking-[0.5em]">© 2026 ROFF.SHOECLEAN MASTER CONTROL</p>
        </footer>
    </main>

    <script>
        function changeQty(id, delta) {
            const input = document.getElementById('qty-' + id);
            let val = parseInt(input.value) + delta;
            if (val < 0) val = 0;
            input.value = val;
            hitungTotalSemua();
        }

        function hitungTotalSemua() {
            let totalHarga = 0;
            let totalSepatu = 0;
            const inputs = document.querySelectorAll('.layanan-qty');
            
            inputs.forEach(input => {
                const qty = parseInt(input.value) || 0;
                const harga = parseInt(input.getAttribute('data-harga'));
                totalHarga += qty * harga;
                totalSepatu += qty;
            });
            
            document.getElementById('totalTagihan').innerText = new Intl.NumberFormat("id-ID", { 
                style: "currency", currency: "IDR", minimumFractionDigits: 0 
            }).format(totalHarga);

            document.getElementById('totalSepatuDisplay').innerText = totalSepatu;
            
            const btn = document.getElementById('btnSubmit');
            btn.disabled = (totalSepatu === 0);
        }

        function toggleAlamatArea() {
            const selectedIn = document.querySelector('.delivery-in-radio:checked')?.value;
            const selectedOut = document.querySelector('.delivery-out-radio:checked')?.value;
            const isNeedKurir = (selectedIn === 'Jemput Kurir' || selectedOut === 'Antar Kurir');
            const areaAlamat = document.getElementById('areaAlamat');
            const inputAlamat = document.getElementById('alamat_jemput');

            if (isNeedKurir) {
                areaAlamat.classList.remove('hidden');
                inputAlamat.required = true;
            } else {
                areaAlamat.classList.add('hidden');
                inputAlamat.required = false;
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.delivery-in-radio, .delivery-out-radio').forEach(radio => {
                radio.addEventListener('change', toggleAlamatArea);
            });
            
            document.querySelectorAll('.layanan-qty').forEach(input => {
                input.addEventListener('input', hitungTotalSemua);
            });

            document.getElementById('formReservasi').addEventListener('submit', function() {
                const btn = document.getElementById('btnSubmit');
                btn.innerHTML = 'MEMPROSES... <i class="fa-solid fa-circle-notch fa-spin ml-2"></i>';
                btn.disabled = true;
            });

            hitungTotalSemua();
            toggleAlamatArea();
        });
    </script>
</body>
</html>