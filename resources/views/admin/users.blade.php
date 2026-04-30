<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pelanggan - ROFF.MANAGEMENT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0f172a; overflow: hidden; }
        
        /* Glassmorphism Panel */
        .glass-panel { 
            background: rgba(30, 41, 59, 0.4); 
            backdrop-filter: blur(16px); 
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05); 
        }

        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</head>

@php
    // LOGIKA BUNGLON (Otomatis menyesuaikan warna tema)
    $isSuper = auth()->user()->id_role == 1 || auth()->user()->role === 'superadmin';
    $accent = $isSuper ? 'emerald' : 'blue';
@endphp

<body class="text-slate-200 antialiased flex h-screen overflow-hidden relative selection:bg-{{ $accent }}-500 selection:text-white">

    {{-- KONTEN UTAMA --}}
    <main class="flex-1 flex flex-col min-w-0 bg-[#0f172a] relative z-10 h-screen">
        
        {{-- TOP NAVIGATION --}}
        <header class="bg-[#0f172a]/80 backdrop-blur-xl border-b border-white/5 px-6 md:px-12 py-4 flex justify-between items-center shrink-0 z-40 sticky top-0">
            
            {{-- LOGO & TOMBOL KEMBALI KIRI --}}
            <div class="flex items-center gap-3 md:gap-4">
                <a href="{{ $isSuper ? route('superadmin.dashboard') : route('admin.dashboard') }}" class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-slate-800/50 border border-slate-700 text-slate-400 hover:text-{{ $accent }}-400 hover:bg-slate-800 transition-all flex items-center justify-center shadow-sm group active:scale-95" title="Kembali ke Dasbor">
                    <i class="fa-solid fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
                </a>
                
                <h1 class="block font-black text-xl md:text-2xl uppercase tracking-tighter italic text-white leading-tight">
                    ROFF.<span class="text-{{ $accent }}-500">{{ $isSuper ? 'SUPER' : 'ADMIN' }}</span>
                </h1>
            </div>
            
            {{-- PROFIL KANAN ATAS --}}
            <div class="flex items-center gap-5">
                <div class="hidden md:flex items-center bg-slate-800/40 border border-slate-700/50 px-4 py-1.5 rounded-full shadow-inner">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Hari Ini: <span class="text-white">{{ now()->format('d M Y') }}</span></span>
                </div>
                <div class="flex items-center bg-slate-800/40 border border-slate-700 p-1 pr-4 rounded-full shadow-inner">
                    <div class="w-8 h-8 rounded-full overflow-hidden bg-{{ $accent }}-600 flex items-center justify-center text-[10px] font-black text-white border border-slate-700 shadow-xl shadow-{{ $accent }}-500/20">
                        @if(auth()->user()->foto_profil)
                            <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" class="w-full h-full object-cover">
                        @else
                            {{ $isSuper ? 'SU' : strtoupper(substr(auth()->user()->nama, 0, 2)) }}
                        @endif
                    </div>
                    <div class="ml-3 hidden md:block">
                        <p class="text-[10px] font-black text-white uppercase tracking-widest leading-none">{{ explode(' ', auth()->user()->nama)[0] }}</p>
                        <p class="text-[7px] font-bold text-{{ $accent }}-400/80 uppercase mt-0.5 tracking-tighter">{{ $isSuper ? 'Owner Access' : 'Staff Access' }}</p>
                    </div>
                </div>
            </div>
        </header>

        {{-- AREA SCROLLABLE --}}
        <div class="p-6 md:p-12 flex-1 overflow-y-auto custom-scroll relative">
            
            {{-- Background Glow Dinamis --}}
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-{{ $accent }}-600/10 blur-[150px] rounded-full pointer-events-none -translate-y-1/2 translate-x-1/4"></div>

            {{-- ALERT MESSAGES (Berwarna Hijau Netral untuk Success) --}}
            @if(session('success'))
                <div class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-400 px-6 py-4 rounded-2xl mb-8 text-xs font-bold shadow-lg flex items-center gap-3 backdrop-blur-sm relative z-10">
                    <i class="fa-solid fa-circle-check text-lg"></i> {{ session('success') }}
                </div>
            @endif

            {{-- HEADER HALAMAN & TOTAL PELANGGAN --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8 md:mb-10 relative z-10">
                <div>
                    <div class="inline-flex items-center gap-2 bg-{{ $accent }}-500/10 border border-{{ $accent }}-500/20 px-4 py-1.5 rounded-full mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-{{ $accent }}-500 animate-pulse shadow-[0_0_10px_currentColor]"></span>
                        <p class="text-[8px] md:text-[9px] font-black text-{{ $accent }}-400 uppercase tracking-[0.4em]">Customer Management</p>
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black text-white tracking-tighter leading-none mb-2">
                        Manajemen <span class="italic text-transparent bg-clip-text bg-gradient-to-r from-{{ $accent }}-400 to-teal-200">Pelanggan</span>
                    </h1>
                    <p class="text-slate-400 font-medium text-sm">Daftar pelanggan yang terdaftar di sistem ROFF.SHOECLEAN</p>
                </div>

                {{-- KARTU TOTAL PELANGGAN (DIPERCANTIK) --}}
                <div class="glass-panel w-full md:w-auto px-10 py-8 rounded-[2rem] text-right shadow-2xl relative overflow-hidden shrink-0 border border-{{ $accent }}-500/20 group">
                    <div class="absolute inset-0 bg-gradient-to-br from-{{ $accent }}-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute top-0 right-0 w-32 h-32 bg-{{ $accent }}-500/20 rounded-full -translate-y-1/2 translate-x-1/2 blur-2xl"></div>
                    <i class="fa-solid fa-users absolute -left-2 -bottom-4 text-8xl text-white/5 -rotate-12 group-hover:rotate-0 group-hover:scale-110 transition-transform duration-500"></i>
                    
                    <p class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-1 relative z-10 group-hover:text-{{ $accent }}-400 transition-colors">Total Pelanggan Aktif</p>
                    <h3 class="text-4xl md:text-5xl font-black text-white tracking-tighter relative z-10 italic drop-shadow-md">
                        {{ $totalPelanggan ?? $users->count() }} <span class="text-xl md:text-2xl text-{{ $accent }}-500 ml-1 font-bold">USER</span>
                    </h3>
                </div>
            </div>
            
            {{-- TABEL DATA --}}
            <div class="glass-panel rounded-[2.5rem] border border-white/5 shadow-2xl overflow-hidden mb-10 relative z-10">
                <div class="overflow-x-auto pb-4">
                    <table class="w-full text-left border-collapse min-w-[800px] md:min-w-0">
                        <thead>
                            <tr class="bg-slate-800/50 border-b border-slate-700 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                                <th class="px-8 py-6"><i class="fa-solid fa-user-tag mr-2 opacity-70"></i>Nama Pelanggan</th>
                                <th class="px-8 py-6"><i class="fa-solid fa-address-book mr-2 opacity-70"></i>Email & Kontak</th>
                                <th class="px-8 py-6 text-center"><i class="fa-solid fa-shield-halved mr-2 opacity-70"></i>Tipe Akun</th>
                                <th class="px-8 py-6 text-right"><i class="fa-solid fa-gears mr-2 opacity-70"></i>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50 text-sm relative">
                            @forelse($users as $u)
                            <tr class="hover:bg-slate-800/30 transition-all group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-400 shadow-inner group-hover:text-{{ $accent }}-400 group-hover:border-{{ $accent }}-500/30 transition-colors">
                                            {{ strtoupper(substr($u->nama, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-black text-white uppercase italic leading-tight group-hover:text-{{ $accent }}-400 transition-colors">{{ $u->nama }}</p>
                                            <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-1"><i class="fa-regular fa-calendar-check mr-1"></i> Daftar: {{ $u->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <a href="mailto:{{ $u->email }}" class="text-xs text-slate-300 font-medium hover:text-{{ $accent }}-400 transition-colors block mb-1">
                                        {{ $u->email }}
                                    </a>
                                    @if($u->no_telp)
                                        @php 
                                            // Ubah 08... menjadi 628... untuk link WA
                                            $waNumber = preg_replace('/^0/', '62', $u->no_telp); 
                                        @endphp
                                        <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="text-[10px] text-slate-400 hover:text-emerald-400 font-bold tracking-widest transition-colors inline-flex items-center gap-1.5">
                                            <i class="fa-brands fa-whatsapp text-emerald-500 text-xs"></i> {{ $u->no_telp }}
                                        </a>
                                    @else
                                        <p class="text-[10px] text-slate-600 font-bold tracking-widest italic">- Tanpa Nomor -</p>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="bg-slate-800 text-slate-300 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] border border-slate-700 shadow-inner">
                                        Pelanggan
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <form action="{{ route('admin.users.destroy', $u->id_user) }}" method="POST" onsubmit="return confirm('Hapus pelanggan ini? Seluruh data riwayat reservasi pelanggan ini juga akan terhapus.');" class="m-0 inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-11 h-11 rounded-full bg-red-500/10 text-red-500 border border-red-500/20 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center ml-auto active:scale-90 shadow-lg" title="Hapus Pelanggan">
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-24 text-center">
                                    <div class="flex flex-col items-center opacity-30">
                                        <i class="fa-solid fa-users-slash text-6xl mb-4 text-slate-400"></i>
                                        <p class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-400">Belum ada pelanggan terdaftar</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="mt-auto pt-6 pb-2 border-t border-white/5 flex justify-center items-center opacity-40 shrink-0 relative z-10">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] w-full text-center text-white">© 2026 ROFF.MASTER PANEL CONTROL</p>
            </div>

        </div>
    </main>

</body>
</html>