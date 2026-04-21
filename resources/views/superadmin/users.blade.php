<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - ROFF.ADMIN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0f172a; overflow: hidden; }
        
        .glass-panel { 
            background: rgba(30, 41, 59, 0.4); 
            backdrop-filter: blur(12px); 
            border: 1px solid rgba(255, 255, 255, 0.05); 
        }

        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        
        /* Efek Akun Nonaktif */
        .user-inactive { opacity: 0.5; filter: grayscale(0.8); }
    </style>
</head>
<body class="text-slate-200 antialiased flex h-screen overflow-hidden relative">

    @php
        $isSuper = auth()->user()->id_role == 1;
        $accent = $isSuper ? 'emerald' : 'blue';
    @endphp

    {{-- KONTEN UTAMA --}}
    <main class="flex-1 flex flex-col min-w-0 bg-[#0f172a] relative z-10 h-screen">
        
        {{-- TOP NAVIGATION --}}
        <header class="bg-[#0f172a]/40 backdrop-blur-xl border-b border-white/5 px-6 md:px-12 py-4 flex justify-between items-center shrink-0 z-40">
            <div class="flex items-center gap-3 md:gap-4">
                <a href="{{ $isSuper ? route('superadmin.dashboard') : route('admin.dashboard') }}" class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-slate-800/50 border border-slate-700 text-slate-400 hover:text-{{ $accent }}-400 hover:bg-slate-800 transition-all flex items-center justify-center shadow-sm group active:scale-95">
                    <i class="fa-solid fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <h1 class="block font-black text-xl md:text-2xl uppercase tracking-tighter italic text-white leading-tight">
                    ROFF.<span class="text-{{ $accent }}-500">{{ $isSuper ? 'SUPER' : 'ADMIN' }}</span>
                </h1>
            </div>
            
            <div class="flex items-center gap-5">
                <div class="flex items-center bg-slate-800/40 border border-slate-700 p-1 pr-4 rounded-full shadow-inner">
                    <div class="w-8 h-8 rounded-full overflow-hidden bg-{{ $accent }}-500 flex items-center justify-center text-[10px] font-black text-white border border-slate-700 shadow-xl">
                        {{ $isSuper ? 'SU' : strtoupper(substr(auth()->user()->nama, 0, 2)) }}
                    </div>
                    <div class="ml-3 hidden md:block">
                        <p class="text-[10px] font-black text-white uppercase tracking-widest leading-none">{{ explode(' ', auth()->user()->nama)[0] }}</p>
                        <p class="text-[7px] font-bold text-{{ $accent }}-500/60 uppercase mt-0.5 tracking-tighter">{{ $isSuper ? 'Owner Access' : 'Verified Access' }}</p>
                    </div>
                </div>
            </div>
        </header>

        {{-- AREA SCROLLABLE --}}
        <div class="p-6 md:p-12 flex-1 overflow-y-auto custom-scroll relative">
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-{{ $accent }}-600/10 blur-[120px] rounded-full pointer-events-none -translate-y-1/2 translate-x-1/4"></div>

            @if(session('success'))
                <div class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-400 px-6 py-4 rounded-2xl mb-8 text-xs font-bold shadow-lg flex items-center gap-3 backdrop-blur-sm relative z-10">
                    <i class="fa-solid fa-circle-check text-lg"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-500/20 border border-rose-500/50 text-rose-400 px-6 py-4 rounded-2xl mb-8 text-xs font-bold shadow-lg flex items-center gap-3 backdrop-blur-sm relative z-10">
                    <i class="fa-solid fa-triangle-exclamation text-lg"></i> {{ session('error') }}
                </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-8 md:mb-10 relative z-10">
                <div>
                    <div class="inline-flex items-center gap-2 bg-{{ $accent }}-500/10 border border-{{ $accent }}-500/20 px-4 py-1.5 rounded-full mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-{{ $accent }}-500 animate-pulse shadow-[0_0_10px_currentColor]"></span>
                        <p class="text-[8px] md:text-[9px] font-black text-{{ $accent }}-400 uppercase tracking-[0.4em]">Database Master</p>
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black text-white tracking-tighter leading-none mb-2">
                        Manajemen <span class="italic text-transparent bg-clip-text bg-gradient-to-r from-{{ $accent }}-400 to-teal-200">User.</span>
                    </h1>
                    <p class="text-slate-400 font-medium text-sm">Kontrol penuh aksesibilitas staf dan keamanan data ROFF.</p>
                </div>

                <button onclick="openModal()" class="w-full md:w-auto bg-{{ $accent }}-500 text-white px-8 py-4 rounded-[1.5rem] font-black uppercase text-[10px] tracking-[0.2em] transition-all flex items-center justify-center gap-3 shadow-lg hover:-translate-y-1 active:scale-95 group">
                    <i class="fa-solid fa-user-plus group-hover:scale-110 transition-transform"></i> Tambah Admin Baru
                </button>
            </div>

            {{-- STATISTIK --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-6 mb-8 md:mb-10 relative z-10">
                <div class="glass-panel p-6 rounded-[2rem] flex justify-between items-center transition-all hover:bg-slate-800/80">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] mb-2">Total Akun</p>
                        <span class="text-3xl font-black text-white italic">{{ $users->count() }}</span>
                    </div>
                    <div class="w-10 h-10 bg-slate-800 text-slate-400 rounded-full flex items-center justify-center text-lg shadow-inner"><i class="fa-solid fa-users"></i></div>
                </div>
                
                <div class="glass-panel p-6 rounded-[2rem] flex justify-between items-center border-emerald-500/20 bg-emerald-500/5">
                    <div>
                        <p class="text-[9px] font-black text-emerald-400 uppercase tracking-[0.3em] mb-2">Admin/Staf</p>
                        <span class="text-3xl font-black text-emerald-400 italic">{{ $users->where('id_role', 2)->count() }}</span>
                    </div>
                    <div class="w-10 h-10 bg-emerald-500/20 text-emerald-400 rounded-full flex items-center justify-center text-lg"><i class="fa-solid fa-user-tie"></i></div>
                </div>

                <div class="glass-panel p-6 rounded-[2rem] flex justify-between items-center border-rose-500/20 bg-rose-500/5">
                    <div>
                        <p class="text-[9px] font-black text-rose-400 uppercase tracking-[0.3em] mb-2">Nonaktif</p>
                        <span class="text-3xl font-black text-rose-400 italic">{{ $users->where('is_active', false)->count() }}</span>
                    </div>
                    <div class="w-10 h-10 bg-rose-500/20 text-rose-400 rounded-full flex items-center justify-center text-lg"><i class="fa-solid fa-user-slash"></i></div>
                </div>
                
                <div class="glass-panel p-6 rounded-[2rem] flex justify-between items-center transition-all hover:bg-slate-800/80">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] mb-2">Pelanggan</p>
                        <span class="text-3xl font-black text-white italic">{{ $users->where('id_role', 3)->count() }}</span>
                    </div>
                    <div class="w-10 h-10 bg-slate-800 text-blue-400 rounded-full flex items-center justify-center text-lg shadow-inner"><i class="fa-solid fa-bag-shopping"></i></div>
                </div>
            </div>
            
            {{-- TABEL DATA --}}
            <div class="glass-panel rounded-[2.5rem] border border-white/5 shadow-2xl overflow-hidden mb-10 relative z-10">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px] md:min-w-0">
                        <thead>
                            <tr class="bg-slate-800/50 border-b border-slate-700 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                                <th class="px-8 py-6">Nama Pengguna</th>
                                <th class="px-8 py-6">Email & Kontak</th>
                                <th class="px-8 py-6 text-center">Status Akses</th>
                                <th class="px-8 py-6 text-right">Aksi Manajemen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50 text-sm">
                            @forelse($users as $u)
                            <tr class="hover:bg-slate-800/30 transition-all group {{ !$u->is_active ? 'user-inactive' : '' }}">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        @if(!$u->is_active)
                                            <i class="fa-solid fa-ban text-rose-500 text-xs animate-pulse" title="Akun Dinonaktifkan"></i>
                                        @endif
                                        <div>
                                            <p class="font-black text-white uppercase italic leading-tight group-hover:text-{{ $accent }}-400 transition-colors">{{ $u->nama }}</p>
                                            <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-1">Daftar: {{ $u->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-xs text-slate-300 font-medium">{{ $u->email }}</p>
                                    <p class="text-[10px] text-slate-500 font-bold tracking-widest mt-1">
                                        <i class="fa-brands fa-whatsapp text-emerald-500 mr-1"></i> {{ $u->no_telp ?? '-' }}
                                    </p>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    @if($u->id_role == 1)
                                        <span class="bg-emerald-500/20 text-emerald-400 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] border border-emerald-500/30">Superadmin</span>
                                    @elseif($u->id_role == 2)
                                        <span class="bg-blue-500/20 text-blue-400 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] border border-blue-500/30">Admin / Staff</span>
                                    @else
                                        <span class="bg-slate-700 text-slate-300 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] border border-slate-600">Pelanggan</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center justify-end gap-3">
                                        {{-- ✅ FITUR BARU: TOGGLE STATUS (DITAMPILKAN UNTUK SELURUH ROLE KECUALI DIRI SENDIRI) --}}
                                        @if($u->id_user != auth()->user()->id_user && $u->id_role != 1)
                                            <form action="{{ route('superadmin.users.toggle', $u->id_user) }}" method="POST" class="m-0">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="transition-all active:scale-90" title="{{ $u->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                                    @if($u->is_active)
                                                        <span class="px-3 py-1.5 rounded-xl bg-emerald-500/10 text-emerald-500 text-[9px] font-black uppercase border border-emerald-500/20 hover:bg-emerald-500 hover:text-white transition-all flex items-center gap-2">
                                                            <i class="fa-solid fa-toggle-on text-xs"></i> Aktif
                                                        </span>
                                                    @else
                                                        <span class="px-3 py-1.5 rounded-xl bg-rose-500/10 text-rose-500 text-[9px] font-black uppercase border border-rose-500/20 hover:bg-rose-500 hover:text-white transition-all flex items-center gap-2">
                                                            <i class="fa-solid fa-toggle-off text-xs"></i> Nonaktif
                                                        </span>
                                                    @endif
                                                </button>
                                            </form>
                                        @endif

                                        {{-- PROTEKSI HAPUS --}}
                                        @if($u->id_user == auth()->user()->id_user || $u->id_role == 1)
                                            <span class="text-[9px] font-black text-slate-600 uppercase tracking-[0.2em] italic pr-2">Protected</span>
                                        @else
                                            <form action="{{ route('superadmin.users.destroy', $u->id_user) }}" method="POST" onsubmit="return confirm('Peringatan! Menghapus akun akan menghapus seluruh data transaksi terkait. Lanjutkan?');" class="m-0">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-9 h-9 rounded-xl bg-rose-500/10 text-rose-500 border border-rose-500/20 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center active:scale-90" title="Hapus Permanen">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="p-24 text-center opacity-30">Tidak ada data terdeteksi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-auto pt-6 pb-2 border-t border-white/5 flex justify-center items-center opacity-30 shrink-0 relative z-10">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] w-full text-center text-white">© 2026 ROFF.MASTER PANEL CONTROL</p>
            </div>
        </div>
    </main>

    {{-- MODAL TAMBAH ADMIN --}}
    <div id="modalAdmin" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/85 backdrop-blur-md transition-opacity" onclick="closeModal()"></div>
        <div class="relative w-full max-w-md bg-slate-900 border border-slate-700 rounded-[2.5rem] shadow-2xl p-8 md:p-10 max-h-[90vh] overflow-y-auto custom-scroll">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-black uppercase tracking-tight text-white">Tambah <span class="text-emerald-500 italic">Admin.</span></h3>
                <button onclick="closeModal()" class="w-10 h-10 rounded-full bg-slate-800 text-slate-400 hover:bg-rose-500 hover:text-white transition-colors flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <form action="{{ route('superadmin.users.store') }}" method="POST" class="space-y-5 m-0">
                @csrf
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">Nama Lengkap</label>
                    <input type="text" name="nama" required placeholder="Contoh: Staf Kasir Baru" class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-emerald-500 focus:bg-slate-800 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">No WhatsApp</label>
                    <input type="text" name="no_telp" required placeholder="08xxxx" class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-emerald-500 outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">Email Staf</label>
                    <input type="email" name="email" required placeholder="staf@roff.com" class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-emerald-500 outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">Akses Sandi</label>
                    <input type="password" name="password" required placeholder="Minimal 8 Karakter" class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-emerald-500 outline-none">
                </div>
                
                <div class="pt-6 mt-6 border-t border-slate-800">
                    <button type="submit" class="w-full bg-emerald-500 text-white py-4 rounded-[1.2rem] font-black uppercase text-[11px] tracking-widest hover:bg-emerald-400 transition-all shadow-lg shadow-emerald-500/20 active:scale-95 flex items-center justify-center gap-3">
                        <i class="fa-solid fa-save"></i> Daftarkan Akun Staf
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalAdmin');
        function openModal() { modal.classList.replace('hidden', 'flex'); }
        function closeModal() { modal.classList.replace('flex', 'hidden'); }
    </script>
</body>
</html>