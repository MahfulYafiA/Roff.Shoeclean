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
        .orb-2 { width: 700px; height: 700px; background: rgba(6, 182, 212, 0.12); bottom: -200px; right: -100px; animation-delay: -5s; }
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
        }

        input[type="radio"]:checked + div { 
            border-color: #2563eb; 
            background-color: rgba(37, 99, 235, 0.05); 
            box-shadow: 0 0 15px rgba(37, 99, 235, 0.15);
        }
        input[type="radio"]:checked + div .radio-dot { opacity: 1; transform: scale(1); }
        input[type="radio"]:checked + div h4 { color: #2563eb; }
        
        input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }

        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="antialiased selection:bg-blue-600 selection:text-white flex flex-col h-screen overflow-hidden relative">

    <div class="noise"></div>
    <div class="bg-mesh"></div>
    <div class="orb-1"></div>
    <div class="orb-2"></div>
    <div class="orb-3"></div>

    <header class="glass-nav px-6 md:px-12 py-4 flex justify-between items-center shrink-0 z-40 relative">
        <div class="flex items-center gap-3 md:gap-4">
            <a href="{{ url('/dashboard') }}" class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all flex items-center justify-center shadow-sm group active:scale-95">
                <i class="fa-solid fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <h1 class="block font-black text-xl md:text-2xl uppercase tracking-tighter italic text-slate-900 leading-tight">
                ROFF.<span class="text-blue-600">MEMBER</span>
            </h1>
        </div>
        <div class="text-right shrink-0">
            <p class="text-[8px] md:text-[10px] font-bold uppercase tracking-widest text-slate-400 leading-none mb-1">Pemesanan</p>
            <p class="font-black text-xs md:text-sm text-slate-900 uppercase tracking-widest">Sesi Baru</p>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto custom-scroll w-full relative z-10 p-4 md:p-6 lg:p-10">
        <div class="max-w-5xl mx-auto">
            
            <div class="mb-8 md:mb-10 text-center px-2">
                <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-100 px-4 py-1.5 rounded-full mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                    <p class="text-[8px] md:text-[9px] font-black text-blue-600 uppercase tracking-[0.4em]">Order & Booking</p>
                </div>
                <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tighter mb-2 uppercase italic leading-none">
                    Form <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Reservasi.</span>
                </h1>
                <p class="text-slate-500 font-medium text-xs md:text-sm mt-2">Pilih layanan dan atur kombinasi pengiriman sesuai keinginan Anda.</p>
            </div>

            @if ($errors->any())
                <div class="mb-8 md:mb-10 bg-red-50 border border-red-200 text-red-600 p-6 rounded-[2rem] shadow-sm animate-pulse">
                    <div class="flex items-center gap-3 mb-2">
                        <i class="fa-solid fa-circle-exclamation text-lg"></i>
                        <h4 class="font-black uppercase tracking-widest text-[10px]">Ada kendala pada input Anda:</h4>
                    </div>
                    <ul class="list-disc list-inside text-xs font-bold space-y-1 ml-1 text-red-500">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="formReservasi" action="{{ route('reservasi.store') }}" method="POST" class="glass-card rounded-[2rem] md:rounded-[3rem] p-6 md:p-10 lg:p-14 space-y-10 md:space-y-12">
                @csrf

                {{-- STEP 1: PILIH LAYANAN --}}
                <div>
                    <h3 class="font-black text-base md:text-lg uppercase tracking-widest mb-6 flex items-center gap-3 text-slate-900">
                        <span class="bg-blue-600 text-white w-7 h-7 md:w-8 md:h-8 rounded-full flex items-center justify-center text-[10px] md:text-xs shadow-lg shadow-blue-500/30">1</span> 
                        Pilih Jenis Layanan
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-5">
                        @foreach($layanans as $l)
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="id_layanan" value="{{ $l->id_layanan }}" data-harga="{{ $l->harga }}" class="sr-only layanan-radio" {{ old('id_layanan') == $l->id_layanan ? 'checked' : '' }} required>
                            <div class="border-2 border-slate-200 bg-white/60 rounded-[1.5rem] md:rounded-[2rem] p-5 md:p-6 hover:border-blue-400 hover:bg-white transition-all h-full flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start mb-3 gap-2">
                                        <h4 class="font-black text-lg md:text-xl text-slate-800 italic tracking-tight leading-tight transition-colors">{{ $l->nama_layanan }}</h4>
                                        <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center mt-1 group-hover:border-blue-400 bg-white shrink-0">
                                            <div class="radio-dot w-2.5 h-2.5 bg-blue-600 rounded-full opacity-0 transform scale-0 transition-all duration-300"></div>
                                        </div>
                                    </div>
                                    <p class="text-[10px] md:text-[11px] text-slate-500 font-medium mb-5 leading-relaxed line-clamp-3">{{ $l->deskripsi }}</p>
                                </div>
                                <p class="text-blue-600 font-black text-base md:text-lg tracking-tighter">Rp {{ number_format($l->harga, 0, ',', '.') }} <span class="text-[9px] md:text-[10px] text-slate-400 font-bold uppercase tracking-widest not-italic">/ Ps</span></p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- STEP 2A: PENYERAHAN SEPATU --}}
                <div>
                    <h3 class="font-black text-base md:text-lg uppercase tracking-widest mb-6 flex items-center gap-3 text-slate-900">
                        <span class="bg-blue-600 text-white w-7 h-7 md:w-8 md:h-8 rounded-full flex items-center justify-center text-[10px] md:text-xs shadow-lg shadow-blue-500/30">2A</span> 
                        Serahkan Sepatu Ke Toko
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-5">
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="metode_masuk" value="Antar Sendiri" class="sr-only delivery-in-radio" {{ old('metode_masuk', 'Antar Sendiri') == 'Antar Sendiri' ? 'checked' : '' }} required>
                            <div class="border-2 border-slate-200 bg-white/60 rounded-[1.5rem] md:rounded-[2rem] p-6 md:p-8 hover:border-blue-400 hover:bg-white transition-all h-full">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-black text-lg md:text-xl text-slate-800 italic tracking-tight transition-colors">Antar Sendiri</h4>
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center group-hover:border-blue-400 bg-white shrink-0">
                                        <div class="radio-dot w-2.5 h-2.5 bg-blue-600 rounded-full opacity-0 transform scale-0 transition-all duration-300"></div>
                                    </div>
                                </div>
                                <p class="text-[10px] md:text-[11px] text-slate-500 font-medium">Bawa langsung sepatu kotor Anda ke workshop kami.</p>
                            </div>
                        </label>
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="metode_masuk" value="Jemput Kurir" class="sr-only delivery-in-radio" {{ old('metode_masuk') == 'Jemput Kurir' ? 'checked' : '' }}>
                            <div class="border-2 border-slate-200 bg-white/60 rounded-[1.5rem] md:rounded-[2rem] p-6 md:p-8 hover:border-blue-400 hover:bg-white transition-all h-full">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-black text-lg md:text-xl text-slate-800 italic tracking-tight transition-colors">Dijemput Kurir</h4>
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center group-hover:border-blue-400 bg-white shrink-0">
                                        <div class="radio-dot w-2.5 h-2.5 bg-blue-600 rounded-full opacity-0 transform scale-0 transition-all duration-300"></div>
                                    </div>
                                </div>
                                <p class="text-[10px] md:text-[11px] text-slate-500 font-medium">Tim kurir kami akan mengambil sepatu ke lokasi Anda.</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- STEP 2B: PENGAMBILAN SEPATU --}}
                <div>
                    <h3 class="font-black text-base md:text-lg uppercase tracking-widest mb-6 flex items-center gap-3 text-slate-900">
                        <span class="bg-blue-600 text-white w-7 h-7 md:w-8 md:h-8 rounded-full flex items-center justify-center text-[10px] md:text-xs shadow-lg shadow-blue-500/30">2B</span> 
                        Pengambilan Setelah Selesai
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-5">
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="metode_keluar" value="Ambil Sendiri" class="sr-only delivery-out-radio" {{ old('metode_keluar', 'Ambil Sendiri') == 'Ambil Sendiri' ? 'checked' : '' }} required>
                            <div class="border-2 border-slate-200 bg-white/60 rounded-[1.5rem] md:rounded-[2rem] p-6 md:p-8 hover:border-blue-400 hover:bg-white transition-all h-full">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-black text-lg md:text-xl text-slate-800 italic tracking-tight transition-colors">Ambil Sendiri</h4>
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center group-hover:border-blue-400 bg-white shrink-0">
                                        <div class="radio-dot w-2.5 h-2.5 bg-blue-600 rounded-full opacity-0 transform scale-0 transition-all duration-300"></div>
                                    </div>
                                </div>
                                <p class="text-[10px] md:text-[11px] text-slate-500 font-medium">Ambil sepatu Anda di toko kami jika sudah bersih.</p>
                            </div>
                        </label>
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="metode_keluar" value="Antar Kurir" class="sr-only delivery-out-radio" {{ old('metode_keluar') == 'Antar Kurir' ? 'checked' : '' }}>
                            <div class="border-2 border-slate-200 bg-white/60 rounded-[1.5rem] md:rounded-[2rem] p-6 md:p-8 hover:border-blue-400 hover:bg-white transition-all h-full">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-black text-lg md:text-xl text-slate-800 italic tracking-tight transition-colors">Diantar Kurir</h4>
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center group-hover:border-blue-400 bg-white shrink-0">
                                        <div class="radio-dot w-2.5 h-2.5 bg-blue-600 rounded-full opacity-0 transform scale-0 transition-all duration-300"></div>
                                    </div>
                                </div>
                                <p class="text-[10px] md:text-[11px] text-slate-500 font-medium">Kurir kami akan mengantar kembali sepatu ke rumah Anda.</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- STEP 3: DETAIL --}}
                <div>
                    <h3 class="font-black text-base md:text-lg uppercase tracking-widest mb-6 flex items-center gap-3 text-slate-900">
                        <span class="bg-blue-600 text-white w-7 h-7 md:w-8 md:h-8 rounded-full flex items-center justify-center text-[10px] md:text-xs shadow-lg shadow-blue-500/30">3</span> 
                        Detail Lokasi & Pesanan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 items-start">
                        <div>
                            <label class="block text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 ml-2">Jumlah Sepatu (Pasang)</label>
                            <input type="number" id="jumlah_sepatu" name="jumlah_sepatu" min="1" value="{{ old('jumlah_sepatu', 1) }}" required 
                                   class="w-full bg-white border border-slate-200 text-slate-900 text-lg rounded-[1.5rem] p-4 md:p-5 font-black focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all shadow-sm">
                        </div>
                        
                        <div id="areaAlamat" class="hidden transition-all duration-500">
                            <label class="block text-[9px] font-black text-blue-600 uppercase tracking-[0.2em] mb-3 ml-2">Alamat Lengkap</label>
                            <textarea name="alamat_jemput" id="alamat_jemput" rows="2" placeholder="Masukkan alamat lengkap untuk keperluan kurir..." 
                                      class="w-full bg-blue-50 border border-blue-200 text-slate-900 text-sm rounded-[1.5rem] p-4 md:p-5 font-medium focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 outline-none resize-none transition-all shadow-sm">{{ old('alamat_jemput') }}</textarea>
                            <p class="text-[9px] text-slate-400 mt-2 ml-2 italic">*Diisi jika Anda memilih layanan antar/jemput kurir.</p>
                        </div>
                    </div>
                </div>

                {{-- STEP 4: PEMBAYARAN --}}
                <div>
                    <h3 class="font-black text-base md:text-lg uppercase tracking-widest mb-6 flex items-center gap-3 text-slate-900">
                        <span class="bg-blue-600 text-white w-7 h-7 md:w-8 md:h-8 rounded-full flex items-center justify-center text-[10px] md:text-xs shadow-lg shadow-blue-500/30">4</span> 
                        Metode Pembayaran
                    </h3>
                    
                    <div class="grid grid-cols-1">
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="metode_pembayaran" value="Payment Gateway" class="sr-only payment-radio" checked required>
                            <div class="border-2 border-slate-200 bg-white/60 rounded-[1.5rem] md:rounded-[2rem] p-6 md:p-8 hover:border-blue-400 hover:bg-white transition-all h-full">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-black text-lg md:text-xl text-slate-800 italic tracking-tight transition-colors">QRIS / Bank (Otomatis)</h4>
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center group-hover:border-blue-400 bg-white shrink-0">
                                        <div class="radio-dot w-2.5 h-2.5 bg-blue-600 rounded-full opacity-0 transform scale-0 transition-all duration-300"></div>
                                    </div>
                                </div>
                                <p class="text-[10px] md:text-[11px] text-slate-500 font-medium">Pembayaran instan via QRIS, E-Wallet, atau Virtual Account (Midtrans).</p>
                            </div>
                        </label>
                    </div>

                    <div class="mt-6 bg-cyan-50/80 p-6 md:p-8 rounded-[2rem] border border-cyan-100 text-center">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/e/e0/Midtrans.png" class="h-6 md:h-8 mx-auto mb-4" alt="Midtrans">
                        <h4 class="font-black text-slate-800 uppercase tracking-widest text-xs md:text-sm mb-2">Checkout Otomatis Berbasis Snap</h4>
                        <p class="text-[10px] md:text-[11px] text-slate-500 font-medium max-w-lg mx-auto">Status pembayaran akan otomatis diverifikasi sistem setelah Anda menyelesaikan transaksi di jendela Midtrans.</p>
                    </div>
                </div>

                {{-- TOTAL & SUBMIT --}}
                <div class="bg-blue-50/50 border border-blue-100 p-8 md:p-10 rounded-[2rem] flex flex-col md:flex-row justify-between items-center gap-6 mt-6 shadow-sm">
                    <div class="text-center md:text-left">
                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-500 mb-1">Total Tagihan</p>
                        <h2 class="text-4xl md:text-5xl font-black text-blue-600 italic tracking-tighter" id="totalTagihan">Rp 0</h2>
                    </div>
                    <button type="submit" id="btnSubmit" class="w-full md:w-auto px-12 lg:px-16 py-5 lg:py-6 bg-blue-600 text-white font-black uppercase text-[10px] md:text-xs tracking-[0.3em] rounded-[1.5rem] shadow-lg shadow-blue-500/30 active:scale-95 transition-all hover:bg-slate-900 hover:shadow-slate-900/30 flex items-center justify-center gap-3">
                        Lanjut Checkout <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('formReservasi');
            const btnSubmit = document.getElementById('btnSubmit');
            const jumlahInput = document.getElementById('jumlah_sepatu');
            const totalText = document.getElementById('totalTagihan');
            const areaAlamat = document.getElementById('areaAlamat');
            const inputAlamat = document.getElementById('alamat_jemput');

            const layananRadios = document.querySelectorAll('.layanan-radio');
            const inRadios = document.querySelectorAll('.delivery-in-radio');
            const outRadios = document.querySelectorAll('.delivery-out-radio');

            function hitungTotal() {
                let harga = 0;
                let jumlah = parseInt(jumlahInput.value) || 1;
                const selectedLayanan = document.querySelector('.layanan-radio:checked');
                
                if (selectedLayanan) {
                    harga = parseInt(selectedLayanan.getAttribute('data-harga'));
                }
                
                let total = harga * jumlah;
                totalText.innerText = new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR", minimumFractionDigits: 0 }).format(total);
            }

            function toggleAlamatArea() {
                const selectedIn = document.querySelector('.delivery-in-radio:checked');
                const selectedOut = document.querySelector('.delivery-out-radio:checked');
                
                // Area alamat muncul jika pilih Jemput Kurir (masuk) ATAU Antar Kurir (keluar)
                const isNeedKurir = (selectedIn && selectedIn.value === 'Jemput Kurir') || 
                                    (selectedOut && selectedOut.value === 'Antar Kurir');

                if (isNeedKurir) {
                    areaAlamat.classList.remove('hidden');
                    inputAlamat.required = true;
                } else {
                    areaAlamat.classList.add('hidden');
                    inputAlamat.required = false;
                }
            }

            form.addEventListener('submit', function() {
                setTimeout(() => {
                    btnSubmit.disabled = true;
                    btnSubmit.innerHTML = 'MEMPROSES... <i class="fa-solid fa-circle-notch fa-spin ml-2"></i>';
                    btnSubmit.classList.replace('bg-blue-600', 'bg-slate-400');
                    btnSubmit.classList.remove('shadow-blue-500/30');
                }, 50);
            });

            layananRadios.forEach(radio => radio.addEventListener('change', hitungTotal));
            jumlahInput.addEventListener('input', hitungTotal);
            inRadios.forEach(radio => radio.addEventListener('change', toggleAlamatArea));
            outRadios.forEach(radio => radio.addEventListener('change', toggleAlamatArea));
            
            hitungTotal();
            toggleAlamatArea();
        });
    </script>
</body>
</html>