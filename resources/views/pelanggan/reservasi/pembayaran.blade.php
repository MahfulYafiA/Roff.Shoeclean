<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Bukti Pembayaran - ROFF.SHOECLEAN</title>
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
        
        /* --- BACKGROUND PREMIUM GLOBAL --- */
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

        /* --- GLASSMORPHISM --- */
        .glass-nav { 
            background: rgba(255, 255, 255, 0.65); 
            backdrop-filter: blur(28px); 
            -webkit-backdrop-filter: blur(28px); 
            border-bottom: 1px solid rgba(255, 255, 255, 0.5); 
        }
        
        .glass-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.7));
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 1);
            box-shadow: 0 32px 64px -16px rgba(31, 38, 135, 0.1);
        }

        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="antialiased selection:bg-cyan-500 selection:text-white flex flex-col min-h-screen relative">

    {{-- BACKGROUND ELEMENTS --}}
    <div class="noise"></div>
    <div class="bg-mesh"></div>
    <div class="orb-1"></div>
    <div class="orb-2"></div>
    <div class="orb-3"></div>

    {{-- TOP NAVIGATION --}}
    <header class="glass-nav px-6 md:px-12 py-4 flex justify-between items-center shrink-0 z-40 relative">
        <div class="flex items-center gap-3 md:gap-4">
            <a href="{{ route('reservasi.riwayat') }}" class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-white border border-slate-200 text-slate-400 hover:text-cyan-600 hover:bg-cyan-50 transition-all flex items-center justify-center shadow-sm group active:scale-95" title="Kembali ke Riwayat">
                <i class="fa-solid fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <h1 class="block font-black text-xl md:text-2xl uppercase tracking-tighter italic text-slate-900 leading-tight">
                ROFF.<span class="text-cyan-500">PAYMENT</span>
            </h1>
        </div>
        <div class="text-right shrink-0">
            <p class="text-[8px] md:text-[10px] font-bold uppercase tracking-widest text-slate-500 leading-none mb-1">Manual Transfer</p>
            <p class="font-black text-xs md:text-sm text-slate-900 uppercase tracking-widest">Selesaikan Pesanan</p>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 flex items-center justify-center px-4 md:px-6 py-10 md:py-12 relative z-10 w-full">
        <div class="max-w-4xl w-full">
            
            {{-- ERROR VALIDASI --}}
            @if ($errors->any())
                <div class="mb-8 bg-red-50 border border-red-200 text-red-600 p-6 rounded-[2rem] shadow-sm animate-pulse">
                    <div class="flex items-center gap-3 mb-2">
                        <i class="fa-solid fa-circle-exclamation text-lg"></i>
                        <h4 class="font-black uppercase tracking-widest text-[10px]">Gagal mengunggah bukti:</h4>
                    </div>
                    <ul class="list-disc list-inside text-xs font-bold space-y-1 ml-1 text-red-500">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- KIRI: INFORMASI TAGIHAN & REKENING --}}
                <div class="lg:col-span-5 space-y-6">
                    <div class="glass-card rounded-[2.5rem] p-8 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-cyan-50 rounded-bl-full -z-10"></div>
                        <div class="w-12 h-12 bg-gradient-to-tr from-cyan-500 to-blue-500 text-white rounded-[1rem] flex items-center justify-center text-xl mb-6 shadow-lg shadow-cyan-500/30">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                        </div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Total Tagihan</p>
                        <h3 class="text-3xl md:text-4xl font-black text-cyan-600 italic tracking-tighter">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</h3>
                        
                        <div class="mt-8 pt-6 border-t border-slate-200/60 space-y-3">
                            <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-widest">
                                <span class="text-slate-400">Order ID</span>
                                <span class="text-slate-900 bg-slate-100 px-2 py-1 rounded">#ORD-{{ $reservasi->id_reservasi }}</span>
                            </div>
                            <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-widest">
                                <span class="text-slate-400">Layanan</span>
                                <span class="text-slate-900">{{ $reservasi->layanan->nama_layanan ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-widest">
                                <span class="text-slate-400">Jumlah Sepatu</span>
                                <span class="text-slate-900">{{ $reservasi->jumlah_sepatu }} Pasang</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm flex items-center gap-5">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                                <i class="fa-solid fa-building-columns"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Transfer Bank BRI</p>
                                <p class="text-lg font-black text-slate-800 tracking-wider font-mono">1234 5678 9012</p>
                                <p class="text-[9px] font-bold text-slate-500 uppercase mt-1">a.n Roff Shoeclean</p>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm flex items-center gap-5">
                            <div class="w-12 h-12 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl shrink-0">
                                <i class="fa-solid fa-wallet"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Transfer E-Wallet DANA</p>
                                <p class="text-lg font-black text-slate-800 tracking-wider font-mono">0812 3456 7890</p>
                                <p class="text-[9px] font-bold text-slate-500 uppercase mt-1">a.n Roff Shoeclean</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KANAN: FORM UPLOAD BUKTI --}}
                <div class="lg:col-span-7 h-full">
                    <form id="uploadForm" action="{{ route('reservasi.pembayaran.update', $reservasi->id_reservasi) }}" method="POST" enctype="multipart/form-data" class="glass-card rounded-[2.5rem] md:rounded-[3rem] p-8 md:p-12 h-full flex flex-col">
                        @csrf 
                        @method('PATCH')
                        
                        <div class="mb-6 text-center md:text-left">
                            <h2 class="text-2xl md:text-3xl font-black tracking-tighter italic text-slate-900">Upload <span class="text-cyan-500">Bukti.</span></h2>
                            <p class="text-slate-500 mt-2 text-xs font-medium">Unggah bukti transfer agar admin dapat segera memproses pesanan Anda.</p>
                        </div>

                        <div class="flex-grow mb-8 relative group/upload">
                            <div id="preview-container" class="w-full h-full min-h-[250px] bg-white/60 border-2 border-dashed border-cyan-200 rounded-[2rem] flex flex-col items-center justify-center overflow-hidden transition-all group-hover/upload:border-cyan-500 group-hover/upload:bg-white relative shadow-inner">
                                {{-- Placeholder --}}
                                <div id="placeholder-content" class="flex flex-col items-center gap-3 transition-all px-4 text-center">
                                    <div class="w-16 h-16 bg-cyan-50 rounded-full flex items-center justify-center text-cyan-500 text-2xl group-hover/upload:text-white group-hover/upload:bg-cyan-500 group-hover/upload:scale-110 transition-all duration-500 shadow-sm border border-cyan-100">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                    </div>
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest group-hover/upload:text-cyan-600">Klik / Drag Foto Bukti Transfer</p>
                                    <p class="text-[9px] font-bold text-slate-400 mt-1">Format: JPG, PNG (Maks 2MB)</p>
                                </div>
                                {{-- Preview Image --}}
                                <img id="image-preview" src="" class="hidden absolute inset-0 w-full h-full object-contain p-4 z-10 bg-slate-50">
                                {{-- Change Button (Visible when image exists) --}}
                                <div id="change-btn" class="hidden absolute bottom-4 right-4 z-20 bg-slate-900/80 backdrop-blur-sm text-white px-4 py-2 rounded-full text-[9px] font-black uppercase tracking-widest shadow-lg cursor-pointer pointer-events-none group-hover/upload:bg-cyan-600 transition-colors">
                                    Ganti Foto
                                </div>
                                {{-- Input File Hidden --}}
                                <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" required accept="image/jpeg, image/png, image/jpg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-30" onchange="previewImage(event)">
                            </div>
                        </div>

                        <button type="submit" id="btnSubmit" class="w-full bg-cyan-600 text-white py-5 md:py-6 rounded-2xl md:rounded-[1.5rem] font-black uppercase text-[10px] md:text-xs tracking-[0.3em] shadow-xl shadow-cyan-500/30 hover:bg-slate-900 hover:shadow-slate-900/20 transition-all duration-300 active:scale-95 flex items-center justify-center gap-3">
                            Kirim Bukti Pembayaran <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </main>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('placeholder-content');
            const container = document.getElementById('preview-container');
            const changeBtn = document.getElementById('change-btn');

            reader.onload = function() {
                if (reader.readyState === 2) {
                    preview.src = reader.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    changeBtn.classList.remove('hidden');
                    container.classList.remove('border-dashed');
                    container.classList.add('border-solid', 'border-cyan-500', 'bg-white');
                }
            }
            if(event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }

        // Animasi Loading saat di-submit agar tidak di klik berulang kali
        document.getElementById('uploadForm').addEventListener('submit', function() {
            const btn = document.getElementById('btnSubmit');
            setTimeout(() => {
                btn.disabled = true;
                btn.innerHTML = 'MENGUNGGAH BUKTI... <i class="fa-solid fa-circle-notch fa-spin"></i>';
                btn.classList.replace('bg-cyan-600', 'bg-slate-500');
                btn.classList.replace('hover:bg-slate-900', 'hover:bg-slate-600');
                btn.classList.remove('shadow-cyan-500/30');
            }, 50);
        });
    </script>

</body>
</html>