<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Reservasi - ROFF.SHOECLEAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fafafa; }
        .luxury-shadow { box-shadow: 0 24px 48px -12px rgba(15, 23, 42, 0.05); }
        input[type="radio"]:checked + div { border-color: #2563eb; background-color: #eff6ff; }
        input[type="radio"]:checked + div .radio-dot { opacity: 1; transform: scale(1); }
        input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
    </style>
</head>
<body class="text-slate-900 antialiased">

    {{-- NAVBAR --}}
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="w-full px-4 md:px-6 lg:px-12 flex justify-between items-center py-3 md:py-4 gap-2">
            <a href="{{ url('/dashboard') }}" class="flex items-center gap-2 md:gap-4 hover:text-blue-600 transition-colors group shrink-0">
                <div class="bg-white border border-slate-200 rounded-xl w-10 h-10 md:w-12 md:h-12 flex items-center justify-center shadow-sm group-hover:border-blue-300 transition-all">
                    <svg class="w-4 h-4 md:w-5 md:h-5 transition-transform group-hover:-translate-x-1 text-slate-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </div>
                <span class="text-[9px] md:text-[11px] font-black uppercase tracking-[0.2em] mt-0.5 text-slate-400 group-hover:text-blue-600 hidden sm:block">Kembali</span>
            </a>
            <div class="text-right shrink-0">
                <p class="text-[8px] md:text-[10px] font-bold uppercase tracking-widest text-slate-400 leading-none">Pemesanan</p>
                <p class="font-black text-xs md:text-sm">Sesi Baru</p>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 md:px-6 py-8 md:py-12">
        <div class="mb-8 md:mb-10 text-center px-2">
            <h1 class="text-2xl md:text-4xl font-black text-slate-900 tracking-tight mb-2 uppercase italic">Form <span class="text-blue-600">Reservasi.</span></h1>
            <p class="text-slate-500 font-medium text-xs md:text-sm">Pilih jenis layanan dan atur pesanan sepatu Anda.</p>
        </div>

        {{-- 🚨 NOTIFIKASI ERROR VALIDASI 🚨 --}}
        @if ($errors->any())
            <div class="mb-8 md:mb-10 bg-red-50 border border-red-100 text-red-600 p-6 rounded-[2rem] luxury-shadow animate-pulse">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h4 class="font-black uppercase tracking-widest text-[10px]">Ada kendala pada input Anda:</h4>
                </div>
                <ul class="list-disc list-inside text-xs font-bold space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Start --}}
        <form id="formReservasi" action="{{ route('reservasi.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-[2rem] md:rounded-[3rem] p-6 md:p-10 lg:p-14 luxury-shadow border border-slate-100 space-y-10 md:space-y-12">
            @csrf

            {{-- STEP 1: PILIH LAYANAN --}}
            <div>
                <h3 class="font-black text-base md:text-lg uppercase tracking-widest mb-6 flex items-center gap-3">
                    <span class="bg-blue-600 text-white w-6 h-6 md:w-7 md:h-7 rounded-full flex items-center justify-center text-[10px] md:text-xs shadow-lg shadow-blue-500/30">1</span> 
                    Pilih Jenis Layanan
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                    @foreach($layanans as $l)
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="id_layanan" value="{{ $l->id_layanan }}" data-harga="{{ $l->harga }}" class="sr-only layanan-radio" required>
                        <div class="border-2 border-slate-100 rounded-[1.5rem] md:rounded-[2rem] p-5 md:p-7 hover:border-blue-300 transition-all h-full flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-3 gap-2">
                                    <h4 class="font-black text-lg md:text-xl text-slate-900 italic tracking-tight leading-tight">{{ $l->nama_layanan }}</h4>
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-200 flex items-center justify-center mt-1 group-hover:border-blue-300">
                                        <div class="radio-dot w-2.5 h-2.5 bg-blue-600 rounded-full opacity-0 transform scale-0 transition-all duration-300"></div>
                                    </div>
                                </div>
                                <p class="text-[10px] md:text-[11px] text-slate-500 font-medium mb-5 leading-relaxed line-clamp-3">{{ $l->deskripsi }}</p>
                            </div>
                            <p class="text-blue-600 font-black text-base md:text-lg">Rp {{ number_format($l->harga, 0, ',', '.') }} <span class="text-[9px] md:text-[10px] text-slate-400 font-bold uppercase tracking-widest not-italic">/ Ps</span></p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- STEP 2: METODE PENYERAHAN SEPATU (FITUR BARU) --}}
            <div>
                <h3 class="font-black text-base md:text-lg uppercase tracking-widest mb-6 flex items-center gap-3">
                    <span class="bg-slate-900 text-white w-6 h-6 md:w-7 md:h-7 rounded-full flex items-center justify-center text-[10px] md:text-xs shadow-lg shadow-slate-900/30">2</span> 
                    Metode Penyerahan
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-5">
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="metode_layanan" value="Drop-off" class="sr-only delivery-radio" checked required>
                        <div class="border-2 border-slate-100 rounded-[2rem] p-6 md:p-8 hover:border-blue-300 transition-all h-full">
                            <h4 class="font-black text-lg md:text-xl text-slate-900 italic tracking-tight mb-2">Antar ke Toko</h4>
                            <p class="text-[10px] text-slate-500 font-medium">Bawa langsung sepatu kotor Anda ke bengkel kami.</p>
                        </div>
                    </label>
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="metode_layanan" value="Pick-up" class="sr-only delivery-radio">
                        <div class="border-2 border-slate-100 rounded-[2rem] p-6 md:p-8 hover:border-blue-300 transition-all h-full">
                            <h4 class="font-black text-lg md:text-xl text-slate-900 italic tracking-tight mb-2">Dijemput Kurir</h4>
                            <p class="text-[10px] text-slate-500 font-medium">Tim kurir kami akan mengambil sepatu ke lokasi Anda.</p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- STEP 3: DETAIL & ALAMAT --}}
            <div>
                <h3 class="font-black text-base md:text-lg uppercase tracking-widest mb-6 flex items-center gap-3">
                    <span class="bg-slate-900 text-white w-6 h-6 md:w-7 md:h-7 rounded-full flex items-center justify-center text-[10px] md:text-xs shadow-lg shadow-slate-900/30">3</span> 
                    Detail Pesanan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                    <div>
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-2">Jumlah Sepatu (Pasang)</label>
                        <input type="number" id="jumlah_sepatu" name="jumlah_sepatu" min="1" value="1" required 
                               class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-lg rounded-2xl p-4 md:p-5 font-black focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition-all">
                    </div>
                    {{-- 🚨 Area Alamat: Akan Sembunyi/Muncul Berdasarkan Pilihan Kurir --}}
                    <div id="areaAlamat" class="hidden transition-all duration-300">
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-2 text-blue-600">Alamat Penjemputan</label>
                        <textarea name="alamat_jemput" id="alamat_jemput" rows="1" placeholder="Masukkan alamat lengkap penjemputan..." 
                                  class="w-full bg-blue-50/50 border border-blue-200 text-slate-900 text-sm rounded-2xl p-4 md:p-5 font-medium focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 outline-none resize-none transition-all"></textarea>
                    </div>
                </div>
            </div>

            {{-- STEP 4: PEMBAYARAN --}}
            <div>
                <h3 class="font-black text-base md:text-lg uppercase tracking-widest mb-6 flex items-center gap-3">
                    <span class="bg-slate-900 text-white w-6 h-6 md:w-7 md:h-7 rounded-full flex items-center justify-center text-[10px] md:text-xs shadow-lg shadow-slate-900/30">4</span> 
                    Metode Pembayaran
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-5">
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="metode_pembayaran" value="Bayar di Toko" class="sr-only payment-radio" required>
                        <div class="border-2 border-slate-100 rounded-[2rem] p-6 md:p-8 hover:border-blue-300 transition-all h-full">
                            <h4 class="font-black text-lg md:text-xl text-slate-900 italic tracking-tight mb-2">Bayar di Toko</h4>
                            <p class="text-[10px] text-slate-500 font-medium">Tunai/QRIS saat serah-terima sepatu di bengkel.</p>
                        </div>
                    </label>
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="metode_pembayaran" value="Transfer Bank" class="sr-only payment-radio">
                        <div class="border-2 border-slate-100 rounded-[2rem] p-6 md:p-8 hover:border-blue-300 transition-all h-full">
                            <h4 class="font-black text-lg md:text-xl text-slate-900 italic tracking-tight mb-2">Transfer / E-Wallet</h4>
                            <p class="text-[10px] text-slate-500 font-medium">Transfer via BRI atau DANA. Wajib unggah bukti.</p>
                        </div>
                    </label>
                </div>

                {{-- AREA UPLOAD TRANSFER (BRI & DANA) --}}
                <div id="areaTransfer" class="hidden mt-6 bg-blue-50/50 p-6 md:p-8 rounded-[2rem] border border-blue-100 transition-all">
                    <p class="text-[10px] font-black text-blue-800 uppercase tracking-widest mb-4 ml-2">Tujuan Transfer & Pembayaran</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        {{-- BANK BRI / BRIVA --}}
                        <div class="bg-white p-5 rounded-[1.5rem] border border-blue-200 shadow-sm relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-16 h-16 bg-blue-50 rounded-bl-full -z-10"></div>
                            <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-1 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                Bank BRI
                            </p>
                            <p class="text-lg md:text-xl font-black text-slate-800 tracking-wider mt-2 font-mono">1234 5678 9012</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">a.n Roff Shoeclean</p>
                        </div>

                        {{-- DANA / E-WALLET --}}
                        <div class="bg-white p-5 rounded-[1.5rem] border border-blue-200 shadow-sm relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-16 h-16 bg-blue-50 rounded-bl-full -z-10"></div>
                            <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-1 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                E-Wallet DANA
                            </p>
                            <p class="text-lg md:text-xl font-black text-slate-800 tracking-wider mt-2 font-mono">0812 3456 7890</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">a.n Roff Shoeclean</p>
                        </div>
                    </div>

                    <hr class="border-blue-100 mb-6 border-dashed">
                    
                    <label class="block text-[10px] font-black text-blue-800 uppercase tracking-widest mb-3 ml-2">Upload Bukti Transfer</label>
                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" accept="image/jpeg, image/png, image/jpg" 
                           class="block w-full text-xs text-slate-500 bg-white border border-blue-200 rounded-2xl file:bg-blue-600 file:text-white file:border-0 file:px-6 file:py-4 file:font-black file:uppercase file:text-[10px] file:tracking-widest cursor-pointer hover:file:bg-blue-700 transition-all shadow-sm">
                </div>

                {{-- AREA INFO BAYAR DI TOKO --}}
                <div id="areaBayarToko" class="hidden mt-6 bg-slate-50 p-6 md:p-8 rounded-[2rem] border border-slate-200 transition-all">
                    <div class="flex flex-col md:flex-row items-start gap-5">
                        <div class="w-12 h-12 rounded-full bg-white border border-slate-200 flex items-center justify-center shrink-0 shadow-sm text-slate-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-black text-slate-900 text-sm md:text-base uppercase tracking-widest mb-2">Panduan Pembayaran Kasir</h4>
                            <p class="text-[11px] md:text-xs text-slate-500 leading-relaxed font-medium">
                                Selesaikan reservasi ini, lalu selesaikan pembayaran secara Tunai (Cash) atau Scan QRIS saat serah terima sepatu di kasir kami.
                            </p>
                            <div class="mt-5 p-4 bg-white rounded-2xl border border-slate-100 shadow-sm inline-block">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Lokasi Bengkel Roff Shoeclean:
                                </p>
                                <p class="text-xs font-black text-slate-800 tracking-wide mt-1">Ds. Purworejo Rt.05 Rw.01, Kec. Geger, Kab. Madiun</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- TOTAL & SUBMIT --}}
            <div class="bg-slate-50 border border-slate-200 p-8 md:p-10 rounded-[2.5rem] flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-center md:text-left">
                    <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Total Tagihan</p>
                    <h2 class="text-4xl md:text-5xl font-black text-blue-600 italic tracking-tighter" id="totalTagihan">Rp 0</h2>
                </div>
                <button type="submit" id="btnSubmit" class="w-full md:w-auto px-16 py-6 bg-blue-600 text-white font-black uppercase text-xs tracking-[0.3em] rounded-2xl shadow-xl shadow-blue-500/20 active:scale-95 transition-all">
                    Konfirmasi Reservasi
                </button>
            </div>
        </form>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('formReservasi');
            const btnSubmit = document.getElementById('btnSubmit');
            const jumlahInput = document.getElementById('jumlah_sepatu');
            const totalText = document.getElementById('totalTagihan');
            
            // Area Upload Bukti & Kasir
            const areaTransfer = document.getElementById('areaTransfer');
            const areaBayarToko = document.getElementById('areaBayarToko');
            const fileInput = document.getElementById('bukti_pembayaran');
            
            // Area Alamat (Baru)
            const areaAlamat = document.getElementById('areaAlamat');
            const inputAlamat = document.getElementById('alamat_jemput');

            // Radios
            const layananRadios = document.querySelectorAll('input[name="id_layanan"]');
            const paymentRadios = document.querySelectorAll('input[name="metode_pembayaran"]');
            const deliveryRadios = document.querySelectorAll('input[name="metode_layanan"]'); // Tambahan Baru

            function hitungTotal() {
                let harga = 0;
                let jumlah = parseInt(jumlahInput.value) || 1;
                const selectedLayanan = document.querySelector('input[name="id_layanan"]:checked');
                
                if (selectedLayanan) {
                    harga = parseInt(selectedLayanan.getAttribute('data-harga'));
                }
                
                let total = harga * jumlah;
                totalText.innerText = new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR", minimumFractionDigits: 0 }).format(total);
            }

            // Fungsi untuk Memunculkan/Menyembunyikan Kolom Alamat
            function toggleAlamatArea() {
                const selectedDelivery = document.querySelector('input[name="metode_layanan"]:checked');
                
                if (selectedDelivery && selectedDelivery.value === 'Pick-up') {
                    areaAlamat.classList.remove('hidden');
                    inputAlamat.required = true;
                } else {
                    areaAlamat.classList.add('hidden');
                    inputAlamat.required = false;
                    inputAlamat.value = ''; // Kosongkan text box kalau pilih antar ke toko
                }
            }

            // Fungsi memunculkan form upload khusus transfer
            function toggleUploadArea() {
                const selectedPayment = document.querySelector('input[name="metode_pembayaran"]:checked');
                
                if (selectedPayment && selectedPayment.value === 'Transfer Bank') {
                    areaTransfer.classList.remove('hidden');
                    areaBayarToko.classList.add('hidden');
                    fileInput.required = true;
                } else if (selectedPayment && selectedPayment.value === 'Bayar di Toko') {
                    areaBayarToko.classList.remove('hidden');
                    areaTransfer.classList.add('hidden');
                    fileInput.required = false;
                    fileInput.value = ''; 
                } else {
                    areaTransfer.classList.add('hidden');
                    areaBayarToko.classList.add('hidden');
                    fileInput.required = false;
                    fileInput.value = ''; 
                }
            }

            form.addEventListener('submit', function() {
                btnSubmit.disabled = true;
                btnSubmit.innerText = 'MEMPROSES...';
                btnSubmit.classList.replace('bg-blue-600', 'bg-slate-400');
            });

            // Pantau perubahan setiap kali user klik
            layananRadios.forEach(radio => radio.addEventListener('change', hitungTotal));
            jumlahInput.addEventListener('input', hitungTotal);
            paymentRadios.forEach(radio => radio.addEventListener('change', toggleUploadArea));
            
            // Pantau klik metode penyerahan
            deliveryRadios.forEach(radio => radio.addEventListener('change', toggleAlamatArea));
            
            // Panggil sekali saat halaman dimuat untuk memastikan status default (Hide)
            toggleAlamatArea(); 
        });
    </script>
</body>
</html>