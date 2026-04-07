<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor Pelanggan - ROFF.SHOECLEAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- TAMBAHAN: Library SweetAlert2 untuk Notifikasi Mewah --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fafafa; }
        .blue-gradient { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); }
        .luxury-shadow { box-shadow: 0 24px 48px -12px rgba(15, 23, 42, 0.05); }
        .hover-lift { transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        .hover-lift:hover { transform: translateY(-6px); box-shadow: 0 32px 64px -16px rgba(15, 23, 42, 0.1); }
        
        /* Custom SweetAlert Style agar senada dengan UI ROFF */
        .swal2-popup { border-radius: 2.5rem !important; padding: 2rem !important; }
        .swal2-styled.swal2-confirm { background-color: #2563eb !important; border-radius: 1rem !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; padding: 1rem 2rem !important; }
    </style>
</head>
<body class="text-slate-900 antialiased">

    {{-- NAVBAR --}}
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-full mx-auto px-4 md:px-6 lg:px-10 py-4 md:py-5 flex justify-between items-center gap-2">
            
            {{-- KIRI: TOMBOL KEMBALI KE BERANDA --}}
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-2 text-xs md:text-sm font-bold text-slate-500 hover:text-blue-600 uppercase tracking-widest transition-colors">
                    <i class="fa-solid fa-arrow-left"></i> Beranda Utama
                </a>
            </div>
            
            {{-- KANAN: PROFIL & LOGOUT (DESAIN KAPSUL SEPERTI LANDING PAGE) --}}
            <div class="flex items-center shrink-0">
                <div class="flex items-center bg-white border border-slate-200 p-1 pr-4 rounded-full shadow-sm hover:shadow-md transition-all">
                    
                    {{-- Profil & Nama --}}
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 md:w-8 md:h-8 rounded-full overflow-hidden bg-blue-600 text-white flex items-center justify-center text-[9px] md:text-[10px] font-black border border-white shadow-sm shrink-0">
                            @if(auth()->user()->foto_profil)
                                <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr(auth()->user()->nama, 0, 2)) }}
                            @endif
                        </div>
                        <span class="text-[9px] md:text-[10px] font-black text-slate-800 uppercase tracking-widest truncate max-w-[80px] md:max-w-[100px]">
                            {{ explode(' ', auth()->user()->nama)[0] }}
                        </span>
                    </div>

                    {{-- Garis Pemisah Tipis --}}
                    <div class="w-px h-4 bg-slate-300 mx-3 md:mx-4"></div>

                    {{-- Tombol Keluar Ringkas --}}
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0 flex items-center">
                        @csrf
                        <button type="submit" class="flex items-center gap-1.5 text-[9px] md:text-[10px] font-bold uppercase tracking-widest text-red-500 hover:text-red-700 transition-colors group">
                            Keluar <i class="fa-solid fa-power-off text-[9px] group-hover:scale-110 transition-transform"></i>
                        </button>
                    </form>
                    
                </div>
            </div>

        </div>
    </nav>

    {{-- KONTEN UTAMA --}}
    <main class="max-w-full mx-auto px-4 md:px-6 lg:px-10 py-8 md:py-12">
        
        {{-- NOTIFIKASI BANNER (Opsional, karena sudah ada SweetAlert) --}}
        @if(session('success'))
        <div class="mb-8 bg-emerald-50 border border-emerald-100 rounded-[2rem] p-4 md:p-6 flex items-center gap-4 animate-pulse">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-emerald-500 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <p class="text-emerald-800 font-bold text-xs md:text-sm">{{ session('success') }}</p>
        </div>
        @endif

        {{-- HERO BANNER --}}
        <div class="bg-slate-900 rounded-[2rem] md:rounded-[3rem] p-8 md:p-12 lg:p-20 mb-10 relative overflow-hidden luxury-shadow text-center md:text-left">
            <div class="absolute top-0 right-0 w-64 h-64 md:w-96 md:h-96 bg-blue-600/20 rounded-full blur-[80px] -translate-y-1/2 translate-x-1/3"></div>
            <div class="relative z-10">
                <h1 class="text-3xl md:text-5xl lg:text-6xl font-black text-white tracking-tight mb-4 md:mb-6 leading-tight">
                    Selamat Datang, <br class="md:hidden"><span class="text-blue-500 italic">{{ auth()->user()->nama }}</span>!
                </h1>
                <p class="text-slate-400 font-medium text-sm md:text-lg max-w-2xl leading-relaxed mx-auto md:mx-0">
                    Ini adalah pusat kendali Anda untuk perawatan sepatu premium. Silakan pilih layanan yang Anda butuhkan di bawah ini.
                </p>
            </div>
        </div>

        {{-- GRID 3 KOTAK MENU --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 mt-8">
            
            {{-- KOTAK 1: KELOLA PROFIL --}}
            <a href="{{ route('profil.index') }}" class="group block bg-white rounded-[2.5rem] p-8 md:p-10 border border-slate-100 shadow-xl shadow-slate-200/20 hover:-translate-y-2 hover:bg-blue-600 hover:shadow-2xl hover:shadow-blue-500/30 transition-all duration-500 cursor-pointer">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-white/20 group-hover:text-white transition-colors duration-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <h3 class="font-black text-2xl text-slate-900 mb-3 group-hover:text-white transition-colors duration-500">KELOLA PROFIL</h3>
                <p class="text-slate-500 font-medium text-sm leading-relaxed group-hover:text-blue-100 transition-colors duration-500">Update data diri, foto profil, dan alamat penjemputan utama Anda.</p>
            </a>

            {{-- KOTAK 2: BUAT RESERVASI --}}
            <a href="{{ route('reservasi.create') }}" class="group block bg-white rounded-[2.5rem] p-8 md:p-10 border border-slate-100 shadow-xl shadow-slate-200/20 hover:-translate-y-2 hover:bg-blue-600 hover:shadow-2xl hover:shadow-blue-500/30 transition-all duration-500 cursor-pointer">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-white/20 group-hover:text-white transition-colors duration-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <h3 class="font-black text-2xl text-slate-900 mb-3 group-hover:text-white transition-colors duration-500">BUAT RESERVASI</h3>
                <p class="text-slate-500 font-medium text-sm leading-relaxed group-hover:text-blue-100 transition-colors duration-500">Mulai pesanan baru untuk perawatan sepatu kesayangan Anda hari ini.</p>
            </a>

            {{-- KOTAK 3: SEMUA RIWAYAT --}}
            <a href="{{ route('reservasi.riwayat') }}" class="group block bg-white rounded-[2.5rem] p-8 md:p-10 border border-slate-100 shadow-xl shadow-slate-200/20 hover:-translate-y-2 hover:bg-blue-600 hover:shadow-2xl hover:shadow-blue-500/30 transition-all duration-500 cursor-pointer">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-white/20 group-hover:text-white transition-colors duration-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <h3 class="font-black text-2xl text-slate-900 mb-3 group-hover:text-white transition-colors duration-500">SEMUA RIWAYAT</h3>
                <p class="text-slate-500 font-medium text-sm leading-relaxed group-hover:text-blue-100 transition-colors duration-500">Pantau status pengerjaan, cek total tagihan, dan riwayat cucian lama.</p>
            </a>

        </div>
    </main>

    {{-- SCRIPT NOTIFIKASI MEWAH (SweetAlert2) --}}
    @if(session('success'))
    <script>
        // Logika cerdas untuk menentukan Judul Notifikasi
        let pesan = "{{ session('success') }}";
        let judul = "BERHASIL!"; // Default Judul
        
        // Cek kata kunci dalam pesan (ubah ke huruf kecil semua agar tidak case sensitive)
        let textLower = pesan.toLowerCase();
        
        if(textLower.includes('reservasi') || textLower.includes('hore')) {
            judul = "RESERVASI BERHASIL!";
        } else if(textLower.includes('datang') || textLower.includes('daftar') || textLower.includes('registrasi')) {
            judul = "PENDAFTARAN BERHASIL!";
        } else if(textLower.includes('profil') || textLower.includes('update') || textLower.includes('disimpan')) {
            judul = "BERHASIL DISIMPAN!";
        }

        Swal.fire({
            title: judul,
            text: pesan,
            icon: 'success',
            confirmButtonText: 'PAHAM, TERIMA KASIH',
            buttonsStyling: true,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });
    </script>
    @endif

</body>
</html>