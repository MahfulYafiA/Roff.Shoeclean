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
        
        /* Glassmorphism Panel */
        .glass-panel { 
            background: rgba(30, 41, 59, 0.4); 
            backdrop-filter: blur(12px); 
            border: 1px solid rgba(255, 255, 255, 0.05); 
        }

        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    </style>
</head>
<body class="text-slate-200 antialiased flex h-screen overflow-hidden relative">

    {{-- KONTEN UTAMA (TANPA SIDEBAR SAMA SEKALI) --}}
    <main class="flex-1 flex flex-col min-w-0 bg-[#0f172a] relative z-10 h-screen">
        
        {{-- TOP NAVIGATION --}}
        <header class="bg-[#0f172a]/40 backdrop-blur-xl border-b border-white/5 px-6 md:px-12 py-4 flex justify-between items-center shrink-0 z-40">
            
            {{-- LOGO & TOMBOL KEMBALI KIRI --}}
            <div class="flex items-center gap-3 md:gap-4">
                {{-- 🚨 TOMBOL PANAH KEMBALI KE DASBOR 🚨 --}}
                <a href="{{ route('superadmin.dashboard') }}" class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-slate-800/50 border border-slate-700 text-slate-400 hover:text-emerald-400 hover:bg-slate-800 transition-all flex items-center justify-center shadow-sm group active:scale-95" title="Kembali ke Dasbor">
                    <i class="fa-solid fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
                </a>
                
                <h1 class="block font-black text-xl md:text-2xl uppercase tracking-tighter italic text-white leading-tight">
                    ROFF.<span class="text-emerald-500">SUPER</span>
                </h1>
            </div>
            
            {{-- PROFIL KANAN ATAS --}}
            <div class="flex items-center gap-5">
                <div class="flex items-center bg-slate-800/40 border border-slate-700 p-1 pr-4 rounded-full shadow-inner">
                    <div class="w-8 h-8 rounded-full overflow-hidden bg-emerald-500 flex items-center justify-center text-[10px] font-black text-white border border-slate-700 shadow-xl shadow-emerald-500/20">
                        SU
                    </div>
                    <div class="ml-3 hidden md:block">
                        <p class="text-[10px] font-black text-white uppercase tracking-widest leading-none">Superadmin</p>
                        <p class="text-[7px] font-bold text-emerald-500/60 uppercase mt-0.5 tracking-tighter">Verified Access</p>
                    </div>
                </div>
            </div>
        </header>

        {{-- AREA SCROLLABLE --}}
        <div class="p-6 md:p-12 flex-1 overflow-y-auto custom-scroll relative">
            
            {{-- Background Glow --}}
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-emerald-500/10 blur-[120px] rounded-full pointer-events-none -translate-y-1/2 translate-x-1/4"></div>

            {{-- ALERT MESSAGES --}}
            @if(session('success'))
                <div class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-400 px-6 py-4 rounded-2xl mb-8 text-xs font-bold shadow-lg flex items-center gap-3 backdrop-blur-sm relative z-10">
                    <i class="fa-solid fa-circle-check text-lg"></i> {{ session('success') }}
                </div>
            @endif

            {{-- HEADER HALAMAN & TOMBOL TAMBAH --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-8 md:mb-10 relative z-10">
                <div>
                    <div class="inline-flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/20 px-4 py-1.5 rounded-full mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_10px_rgba(16,185,129,1)]"></span>
                        <p class="text-[8px] md:text-[9px] font-black text-emerald-400 uppercase tracking-[0.4em]">Database Master</p>
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black text-white tracking-tighter leading-none mb-2">
                        Manajemen <span class="italic text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-200">User.</span>
                    </h1>
                    <p class="text-slate-400 font-medium text-sm">Kelola seluruh hak akses Admin dan data Pelanggan ROFF.</p>
                </div>

                <button onclick="openModal()" class="w-full md:w-auto bg-emerald-500 text-white px-8 py-4 md:py-4 rounded-[1.5rem] font-black uppercase text-[10px] tracking-[0.2em] transition-all flex items-center justify-center gap-3 shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/40 hover:-translate-y-1 active:scale-95 shrink-0 group">
                    <i class="fa-solid fa-user-plus group-hover:scale-110 transition-transform"></i> Tambah Admin Baru
                </button>
            </div>

            {{-- STATISTIK SINGKAT --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-8 md:mb-10 relative z-10">
                <div class="glass-panel p-6 md:p-8 rounded-[2rem] flex justify-between items-center transition-all hover:border-emerald-500/50 hover:bg-slate-800/80">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] mb-2">Total Pengguna</p>
                        <span class="text-3xl md:text-4xl font-black text-white italic">{{ $users->count() }}</span>
                    </div>
                    <div class="w-12 h-12 bg-slate-800 text-slate-400 border border-slate-700 rounded-full flex items-center justify-center text-xl shadow-inner"><i class="fa-solid fa-users"></i></div>
                </div>
                
                <div class="glass-panel relative overflow-hidden p-6 md:p-8 rounded-[2rem] flex justify-between items-center transition-all border-emerald-500/30">
                    <div class="absolute inset-0 bg-emerald-500/10"></div>
                    <div class="relative z-10">
                        <p class="text-[9px] font-black text-emerald-400 uppercase tracking-[0.3em] mb-2">Admin & Staff</p>
                        <span class="text-3xl md:text-4xl font-black text-emerald-400 italic">{{ $users->where('role', 'admin')->count() }}</span>
                    </div>
                    <div class="w-12 h-12 bg-emerald-500/20 border border-emerald-500/50 text-emerald-400 rounded-full flex items-center justify-center text-xl relative z-10 shadow-[0_0_15px_rgba(16,185,129,0.3)]"><i class="fa-solid fa-user-tie"></i></div>
                </div>
                
                <div class="glass-panel p-6 md:p-8 rounded-[2rem] flex justify-between items-center transition-all hover:border-blue-500/50 hover:bg-slate-800/80">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] mb-2">Pelanggan</p>
                        <span class="text-3xl md:text-4xl font-black text-white italic">{{ $users->where('role', 'pelanggan')->count() }}</span>
                    </div>
                    <div class="w-12 h-12 bg-slate-800 text-blue-400 border border-slate-700 rounded-full flex items-center justify-center text-xl shadow-inner"><i class="fa-solid fa-bag-shopping"></i></div>
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
                                <th class="px-8 py-6 text-center">Role</th>
                                <th class="px-8 py-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50 text-sm">
                            @forelse($users as $u)
                            <tr class="hover:bg-slate-800/30 transition-all group">
                                <td class="px-8 py-6">
                                    <p class="font-black text-white uppercase italic leading-tight group-hover:text-emerald-400 transition-colors">{{ $u->nama }}</p>
                                    <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-1">Daftar: {{ $u->created_at->format('d/m/Y') }}</p>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-xs text-slate-300 font-medium">{{ $u->email }}</p>
                                    <p class="text-[10px] text-slate-500 font-bold tracking-widest mt-1">
                                        <i class="fa-brands fa-whatsapp text-emerald-500 mr-1"></i> {{ $u->no_telp ?? '-' }}
                                    </p>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    @if($u->role == 'superadmin')
                                        <span class="bg-emerald-500/20 text-emerald-400 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] border border-emerald-500/30">Superadmin</span>
                                    @elseif($u->role == 'admin')
                                        <span class="bg-blue-500/20 text-blue-400 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] border border-blue-500/30">Admin</span>
                                    @elseif($u->role == 'pelanggan')
                                        <span class="bg-slate-700 text-slate-300 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] border border-slate-600">Pelanggan</span>
                                    @else
                                        <span class="bg-slate-800 text-slate-400 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] border border-slate-700">{{ $u->role }}</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-right">
                                    @if($u->id_user == auth()->user()->id_user || $u->role == 'superadmin')
                                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] italic pr-2">{{ $u->id_user == auth()->user()->id_user ? 'Akun Anda' : 'Protected' }}</span>
                                    @else
                                        <form action="{{ route('superadmin.users.destroy', $u->id_user) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini beserta semua data reservasinya?');" class="m-0">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-10 h-10 rounded-full bg-red-500/10 text-red-500 border border-red-500/20 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center ml-auto active:scale-90">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-24 text-center">
                                    <div class="flex flex-col items-center opacity-30">
                                        <i class="fa-solid fa-users-slash text-6xl mb-4 text-slate-400"></i>
                                        <p class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-400">Belum ada data pengguna</p>
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

    {{-- MODAL TAMBAH ADMIN (DARK THEME) --}}
    <div id="modalAdmin" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
        
        <div class="relative w-full max-w-md bg-slate-900 border border-slate-700 rounded-[2.5rem] shadow-2xl p-8 md:p-10 max-h-[90vh] overflow-y-auto custom-scroll">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-black uppercase tracking-tight text-white">Tambah <span class="text-emerald-500 italic">Admin.</span></h3>
                <button onclick="closeModal()" class="w-10 h-10 rounded-full bg-slate-800 text-slate-400 hover:bg-red-500 hover:text-white transition-colors flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <form action="{{ route('superadmin.users.store') }}" method="POST" class="space-y-5 m-0">
                @csrf
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Nama Admin</label>
                    <input type="text" name="nama" required placeholder="Contoh: Budi Santoso" class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-emerald-500 focus:bg-slate-800 outline-none transition-all placeholder:text-slate-600">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">No WhatsApp</label>
                    <input type="text" name="no_telp" required placeholder="0812xxxx" class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-emerald-500 focus:bg-slate-800 outline-none transition-all placeholder:text-slate-600">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Email Login</label>
                    <input type="email" name="email" required placeholder="admin@roff.com" class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-emerald-500 focus:bg-slate-800 outline-none transition-all placeholder:text-slate-600">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Kata Sandi</label>
                    <input type="password" name="password" required placeholder="Minimal 8 karakter" class="w-full bg-slate-800/50 border border-slate-700 px-5 py-4 rounded-xl text-sm font-bold text-white focus:border-emerald-500 focus:bg-slate-800 outline-none transition-all placeholder:text-slate-600">
                </div>
                
                <div class="pt-6 mt-6 border-t border-slate-800">
                    <button type="submit" class="w-full bg-emerald-500 text-white py-4 rounded-[1.2rem] font-black uppercase text-[11px] tracking-widest hover:bg-emerald-400 transition-all shadow-lg shadow-emerald-500/20 active:scale-95">
                        Simpan Akun Admin
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        const modal = document.getElementById('modalAdmin');
        function openModal() { modal.classList.remove('hidden'); }
        function closeModal() { modal.classList.add('hidden'); }
    </script>
</body>
</html>