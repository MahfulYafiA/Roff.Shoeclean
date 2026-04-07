<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Sandi Baru - ROFF.SHOECLEAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; } </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4 py-8 md:p-6 selection:bg-blue-600 selection:text-white">
    <div class="w-full max-w-xl bg-white rounded-[2rem] shadow-2xl p-8 md:p-12 border border-slate-100 relative overflow-hidden">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-black uppercase tracking-tighter mb-2 text-slate-900">Buat Sandi Baru</h2>
            <p class="text-slate-500 font-medium text-xs md:text-sm">Silakan buat kata sandi baru untuk akun Anda.</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 text-xs font-bold text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Alamat Email</label>
                <input type="email" name="email" value="{{ $email ?? old('email') }}" readonly class="w-full bg-slate-100 border border-slate-200 px-5 py-4 rounded-xl text-sm font-bold text-slate-500 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Kata Sandi Baru</label>
                <input type="password" name="password" required placeholder="Minimal 8 karakter" class="w-full bg-slate-50 border border-slate-200 px-5 py-4 rounded-xl text-sm font-bold text-slate-700 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:font-medium">
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Ulangi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" required placeholder="Ulangi kata sandi baru" class="w-full bg-slate-50 border border-slate-200 px-5 py-4 rounded-xl text-sm font-bold text-slate-700 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:font-medium">
            </div>
            
            <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-xl font-black uppercase text-[10px] md:text-xs tracking-[0.2em] hover:bg-blue-600 shadow-lg transition-all duration-300">
                Simpan Kata Sandi Baru
            </button>
        </form>
    </div>
</body>
</html>