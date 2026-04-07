<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Sandi - ROFF.SHOECLEAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; } </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4 py-8 md:p-6 selection:bg-blue-600 selection:text-white">
    <div class="w-full max-w-xl bg-white rounded-[2rem] shadow-2xl p-8 md:p-12 border border-slate-100 relative overflow-hidden">
        <div class="text-center mb-8">
            <a href="{{ route('landing') }}" class="font-black text-2xl tracking-tighter italic text-slate-900 mb-4 block">ROFF.<span class="text-blue-600">SHOECLEAN</span></a>
            <h2 class="text-2xl font-black uppercase tracking-tighter mb-2">Lupa Kata Sandi?</h2>
            <p class="text-slate-500 font-medium text-xs md:text-sm">Masukkan email Anda, kami akan mengirimkan tautan untuk mengatur ulang kata sandi.</p>
        </div>

        @if(session('status'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl mb-6 text-xs font-bold text-center">
                {{ session('status') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 text-xs font-bold text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Alamat Email Terdaftar</label>
                <input type="email" name="email" required placeholder="Masukkan alamat email Anda" class="w-full bg-slate-50 border border-slate-200 px-5 py-4 rounded-xl text-sm font-bold text-slate-700 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:font-medium">
            </div>
            
            <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-xl font-black uppercase text-[10px] md:text-xs tracking-[0.2em] hover:bg-blue-700 shadow-lg hover:shadow-blue-600/30 transition-all duration-300">
                Kirim Tautan Reset
            </button>
        </form>
        
        <div class="mt-8 text-center">
            <a href="{{ route('login') }}" class="text-[10px] md:text-[11px] font-black text-slate-400 hover:text-slate-900 uppercase tracking-widest transition-colors">Kembali ke Login</a>
        </div>
    </div>
</body>
</html>