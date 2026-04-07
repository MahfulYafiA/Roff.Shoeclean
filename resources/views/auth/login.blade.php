<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - ROFF.SHOECLEAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    {{-- CSS UNTUK BACKGROUND KEREN --}}
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
<body class="flex items-center justify-center min-h-screen p-4 py-8 md:p-6 selection:bg-blue-600 selection:text-white relative">

    {{-- Layer Background --}}
    <div class="bg-gradient-light"></div>
    <div class="bg-dots"></div>

    {{-- CONTAINER UTAMA --}}
    <div class="w-full max-w-5xl flex bg-white rounded-[2rem] md:rounded-[3rem] shadow-[0_20px_60px_-15px_rgba(37,99,235,0.15)] overflow-hidden border border-slate-100/50 relative z-10">
        
        {{-- KOLOM FORM --}}
        <div class="w-full lg:w-1/2 p-8 sm:p-10 md:p-12 lg:p-16 flex flex-col justify-center">

            <div class="mb-8 md:mb-10 text-center sm:text-left">
                <a href="{{ route('landing') }}" class="font-black text-2xl md:text-3xl tracking-tighter italic text-slate-900 mb-6 block">ROFF.<span class="text-blue-600">SHOECLEAN</span></a>
                <h2 class="text-2xl md:text-3xl font-black uppercase tracking-tighter mb-2">Selamat Datang!</h2>
                <p class="text-slate-500 font-medium text-xs md:text-sm">Silakan masuk untuk memantau status sepatu Anda.</p>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 text-[11px] font-bold shadow-sm text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4 md:space-y-5" id="loginForm">
                @csrf
                <div>
                    <label class="block text-[10px] md:text-[11px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required 
                        placeholder="Masukkan alamat email Anda"
                        class="w-full bg-slate-50 border border-slate-200 px-5 py-3.5 md:px-6 md:py-4 rounded-xl md:rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-400 placeholder:font-medium">
                </div>
                
                <div>
                    <label class="block text-[10px] md:text-[11px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Kata Sandi</label>
                    <input type="password" name="password" required 
                        placeholder="Masukkan kata sandi Anda"
                        class="w-full bg-slate-50 border border-slate-200 px-5 py-3.5 md:px-6 md:py-4 rounded-xl md:rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-400 placeholder:font-medium">
                </div>
                
                <div class="flex items-center justify-between mt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                        <span class="text-[11px] md:text-xs font-bold text-slate-500">Ingat Saya</span>
                    </label>
                    {{-- 🚨 UPDATE: Link Lupa Sandi Arah ke Route Password Request Bawaan Laravel 🚨 --}}
                    <a href="{{ route('password.request') }}" class="text-[11px] md:text-xs font-bold text-blue-600 hover:text-slate-900 transition-colors">Lupa Sandi?</a>
                </div>

                <button type="submit" id="submitBtn" 
                    class="w-full mt-2 bg-slate-900 text-white py-4 rounded-xl md:rounded-2xl font-black uppercase text-[10px] md:text-xs tracking-[0.2em] hover:bg-blue-600 hover:shadow-xl hover:shadow-blue-600/20 transition-all duration-300 active:scale-[0.98]">
                    Masuk Sekarang
                </button>
            </form>

            <div class="relative flex items-center justify-center mt-6 mb-6">
                <div class="flex-grow border-t border-slate-100"></div>
                <span class="flex-shrink mx-4 text-[9px] md:text-[10px] font-black uppercase tracking-widest text-slate-300 bg-white px-2">Atau</span>
                <div class="flex-grow border-t border-slate-100"></div>
            </div>

            <a href="{{ route('login.google') }}" class="w-full bg-white border border-slate-200 text-slate-700 py-3.5 md:py-4 rounded-xl md:rounded-2xl font-black uppercase text-[10px] md:text-xs tracking-widest hover:bg-slate-50 hover:border-blue-200 transition-all flex items-center justify-center gap-3 shadow-sm mb-6">
                <svg class="w-4 h-4 md:w-5 md:h-5" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Login dengan Gmail
            </a>

            {{-- LINK DAFTAR --}}
            <p class="text-center text-[10px] md:text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-2">
                Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 hover:text-slate-900 transition-colors font-black">Daftar</a>
            </p>

            {{-- TOMBOL KEMBALI DI BAWAH --}}
            <div class="mt-8 text-center">
                <a href="{{ url('/') }}" class="inline-block text-[10px] md:text-[11px] font-black text-slate-400 hover:text-slate-900 transition-colors uppercase tracking-[0.2em]">
                    Kembali
                </a>
            </div>

        </div>

        {{-- KOLOM GAMBAR (Hanya Desktop) --}}
        <div class="hidden lg:block lg:w-1/2 bg-blue-600 relative p-12 overflow-hidden flex flex-col justify-end">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent z-10"></div>
            <div class="absolute inset-0 w-full h-full object-cover mix-blend-overlay bg-slate-900 opacity-40"></div>
            
            <div class="relative z-20 text-white mb-10">
                <h3 class="text-4xl font-black tracking-tighter uppercase leading-[1.1] italic">Perawatan <br> Premium.</h3>
                <p class="text-blue-50 font-medium text-sm mt-4 leading-relaxed max-w-sm">Akses dashboard Anda untuk melakukan reservasi online dan melacak proses cuci sepatu secara real-time.</p>
            </div>
        </div>

    </div>

    <script>
        const form = document.getElementById('loginForm');
        const btn = document.getElementById('submitBtn');
        form.addEventListener('submit', function() {
            btn.innerHTML = "Memproses...";
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            btn.disabled = true;
        });
    </script>
</body>
</html>