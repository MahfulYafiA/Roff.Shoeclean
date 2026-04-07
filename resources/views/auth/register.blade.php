<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - ROFF.SHOECLEAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- CSS BACKGROUND KONSISTEN DENGAN LOGIN --}}
    <style> 
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #ffffff; 
        } 
        
        .bg-gradient-light {
            position: fixed; inset: 0; z-index: -3;
            background: 
                radial-gradient(at 10% 20%, hsla(215, 98%, 95%, 1) 0px, transparent 50%),
                radial-gradient(at 80% 0%, hsla(230, 96%, 96%, 1) 0px, transparent 50%),
                radial-gradient(at 0% 80%, hsla(240, 80%, 97%, 1) 0px, transparent 50%),
                radial-gradient(at 90% 90%, hsla(220, 90%, 96%, 1) 0px, transparent 50%);
        }

        .bg-dots {
            position: fixed; inset: 0; z-index: -2;
            background-image: radial-gradient(#94a3b8 1px, transparent 1px);
            background-size: 24px 24px; 
            opacity: 0.3; 
        }
    </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen p-4 py-12 md:p-6 selection:bg-blue-600 selection:text-white relative">

    {{-- Layer Background --}}
    <div class="bg-gradient-light"></div>
    <div class="bg-dots"></div>

    {{-- LOGO ATAS --}}
    <div class="mb-8 text-center relative z-10">
        <a href="{{ route('landing') }}" class="font-black text-2xl md:text-3xl tracking-tighter italic text-slate-900 block">ROFF.<span class="text-blue-600">SHOECLEAN</span></a>
    </div>

    {{-- CONTAINER FORM --}}
    <div class="w-full max-w-lg bg-white rounded-[2rem] md:rounded-[2.5rem] shadow-[0_20px_60px_-15px_rgba(37,99,235,0.15)] border border-slate-100/50 p-8 sm:p-10 relative z-10">
        
        <div class="mb-8 text-center sm:text-left">
            <h2 class="text-2xl md:text-3xl font-black tracking-tighter mb-2 text-slate-900">Buat Akun</h2>
            <p class="text-slate-500 font-medium text-xs md:text-sm">Daftarkan diri Anda untuk mulai membuat reservasi.</p>
        </div>

        {{-- 🚨 UPDATE NOTIFIKASI ERROR: Menampilkan semua pesan error dengan rapi --}}
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-xl mb-6 text-[11px] font-bold shadow-sm text-left">
                <p class="uppercase tracking-widest text-[9px] mb-2 text-red-500"><i class="fa-solid fa-circle-exclamation mr-1"></i> Terdapat Kesalahan:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-4 md:space-y-5" id="registerForm">
            @csrf
            
            {{-- Nama Lengkap --}}
            <div>
                <label class="block text-[9px] md:text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Masukkan nama lengkap Anda"
                    class="w-full bg-slate-50 border border-slate-200 px-5 py-3.5 md:px-6 md:py-4 rounded-xl md:rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-400 placeholder:font-medium">
            </div>

            {{-- No. WhatsApp --}}
            <div>
                <label class="block text-[9px] md:text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">No. WhatsApp</label>
                <input type="text" name="no_telp" value="{{ old('no_telp') }}" required placeholder="Masukkan nomor WhatsApp"
                    class="w-full bg-slate-50 border border-slate-200 px-5 py-3.5 md:px-6 md:py-4 rounded-xl md:rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-400 placeholder:font-medium">
            </div>

            {{-- Alamat Email --}}
            <div>
                <label class="block text-[9px] md:text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="Masukkan alamat email Anda"
                    class="w-full bg-slate-50 border border-slate-200 px-5 py-3.5 md:px-6 md:py-4 rounded-xl md:rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-400 placeholder:font-medium">
            </div>
            
            {{-- Grid Password --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                <div>
                    <label class="block text-[9px] md:text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Kata Sandi</label>
                    <input type="password" name="password" required placeholder="Min. 8 karakter"
                        class="w-full bg-slate-50 border border-slate-200 px-5 py-3.5 md:px-6 md:py-4 rounded-xl md:rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-400 placeholder:font-medium">
                </div>
                <div>
                    <label class="block text-[9px] md:text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Ulangi Sandi</label>
                    <input type="password" name="password_confirmation" required placeholder="Ketik ulang"
                        class="w-full bg-slate-50 border border-slate-200 px-5 py-3.5 md:px-6 md:py-4 rounded-xl md:rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-400 placeholder:font-medium">
                </div>
            </div>

            <button type="submit" id="submitBtn" 
                class="w-full mt-4 md:mt-6 bg-blue-600 text-white py-4 rounded-xl md:rounded-2xl font-black uppercase text-[10px] md:text-xs tracking-[0.2em] hover:bg-slate-900 hover:shadow-xl hover:shadow-slate-900/20 transition-all duration-300 active:scale-[0.98]">
                Daftar Sekarang
            </button>
        </form>

        {{-- LINK LOGIN --}}
        <p class="text-center text-[10px] md:text-[11px] font-bold text-slate-400 mt-6 md:mt-8">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-slate-900 hover:text-blue-600 transition-colors font-black">Masuk di sini</a>
        </p>

        {{-- TOMBOL KEMBALI DI BAWAH (Sudah dipangkas) --}}
        <div class="mt-6 md:mt-8 text-center">
            <a href="{{ url('/') }}" class="inline-block text-[10px] md:text-[11px] font-black text-slate-400 hover:text-slate-900 transition-colors uppercase tracking-[0.2em]">
                Kembali
            </a>
        </div>

    </div>

    <script>
        const form = document.getElementById('registerForm');
        const btn = document.getElementById('submitBtn');
        form.addEventListener('submit', function() {
            btn.innerHTML = "Memproses...";
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            // Menghapus disable agar Laravel bisa memproses form jika ada error yang dicegat browser
            // btn.disabled = true; 
        });
    </script>
</body>
</html>