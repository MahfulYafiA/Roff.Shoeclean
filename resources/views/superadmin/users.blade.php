<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - ROFF.SUPERADMIN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .sidebar-active { background: #10b981; color: white; box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3); }
        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
</head>
<body class="text-slate-900 antialiased flex h-screen overflow-hidden">

    {{-- SIDEBAR SUPERADMIN (TEMA EMERALD) --}}
    <aside class="w-72 bg-white border-r border-slate-200 flex flex-col h-full shrink-0 z-50">
        <div class="p-8">
            <h1 class="font-black text-2xl uppercase tracking-tighter italic text-slate-900">
                ROFF.<span class="text-emerald-500">SUPERADMIN</span>
            </h1>
        </div>

        <nav class="flex-1 px-4 space-y-2 mt-4">
            {{-- Menu Aktif --}}
            <a href="{{ route('superadmin.users') }}" class="flex items-center gap-4 sidebar-active px-6 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all">
                <i class="fa-solid fa-users-shield text-lg"></i>
                Manajemen User
            </a>
            
            <div class="pt-6 border-t border-slate-100 mt-4">
                <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-4 text-slate-400 hover:text-emerald-500 px-6 py-4 rounded-2xl font-bold text-[11px] uppercase tracking-widest transition-all group">
                    <i class="fa-solid fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
                    Dasbor Utama
                </a>
            </div>
        </nav>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 flex flex-col overflow-hidden bg-[#f8fafc]">
        
        {{-- HEADER --}}
        <header class="px-10 py-8 flex justify-between items-center bg-white border-b border-slate-200 shrink-0">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight uppercase">User <span class="text-emerald-500 italic">Management.</span></h2>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-[0.2em] mt-1 text-emerald-500/60">Superadmin Control Panel</p>
            </div>
            <div class="flex items-center gap-4">
                {{-- Tombol Tambah Admin dipindah ke header atas agar rapi --}}
                <button onclick="openModal()" class="bg-emerald-500 hover:bg-slate-900 text-white px-5 py-2.5 rounded-xl font-black uppercase text-[9px] tracking-widest transition-colors flex items-center gap-2 shadow-lg shadow-emerald-500/20">
                    <i class="fa-solid fa-user-plus text-sm"></i> Tambah Admin Baru
                </button>

                <div class="bg-slate-900 border border-slate-800 px-4 py-2.5 rounded-xl flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <p class="text-[9px] font-black text-white uppercase tracking-widest">Role: Superadmin</p>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-10 custom-scroll">
            
            {{-- ALERT MESSAGES --}}
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-6 py-4 rounded-2xl mb-8 text-xs font-bold shadow-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-lg"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-600 px-6 py-4 rounded-2xl mb-8 text-xs font-bold shadow-sm flex items-center gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-lg"></i> {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-6 py-4 rounded-2xl mb-8 text-xs font-bold shadow-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            {{-- STATISTIK SINGKAT (Menyesuaikan Gaya Admin) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm flex justify-between items-center">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Pengguna</p>
                        <span class="text-4xl font-black text-slate-900 italic">{{ $users->count() }}</span>
                    </div>
                    <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center text-xl"><i class="fa-solid fa-users"></i></div>
                </div>
                <div class="bg-emerald-500 p-8 rounded-[2rem] shadow-xl shadow-emerald-500/20 text-white flex justify-between items-center">
                    <div>
                        <p class="text-[10px] font-black text-emerald-100 uppercase tracking-widest mb-2">Admin & Staff</p>
                        <span class="text-4xl font-black text-white italic">{{ $users->where('id_role', 2)->count() }}</span>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-xl"><i class="fa-solid fa-user-tie"></i></div>
                </div>
                <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm flex justify-between items-center">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Customer</p>
                        <span class="text-4xl font-black text-slate-900 italic">{{ $users->where('id_role', 3)->count() }}</span>
                    </div>
                    <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center text-xl"><i class="fa-solid fa-bag-shopping"></i></div>
                </div>
            </div>

            {{-- TABEL PENGGUNA --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden mb-10">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Pengguna</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Email & Kontak</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Role</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $u)
                        <tr class="hover:bg-slate-50/50 transition-all">
                            <td class="px-8 py-6">
                                <p class="font-black text-slate-900 uppercase italic">{{ $u->nama }}</p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter mt-1">Daftar: {{ $u->created_at->format('d/m/Y') }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm text-slate-600 font-medium">{{ $u->email }}</p>
                                <p class="text-[10px] text-slate-500 font-bold mt-1"><i class="fa-brands fa-whatsapp text-emerald-500 mr-1"></i> {{ $u->no_telp ?? '-' }}</p>
                            </td>
                            <td class="px-8 py-6">
                                @if($u->id_role == 1)
                                    <span class="bg-slate-900 text-white px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">Superadmin</span>
                                @elseif($u->id_role == 2)
                                    <span class="bg-emerald-100 text-emerald-600 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border border-emerald-200">Admin</span>
                                @else
                                    <span class="bg-slate-100 text-slate-500 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border border-slate-200">Customer</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right">
                                @if($u->id_user == auth()->user()->id_user)
                                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest italic pr-2">Akun Anda</span>
                                @else
                                    <form action="{{ route('superadmin.users.destroy', $u->id_user) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini beserta semua data reservasinya?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-full bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors flex items-center justify-center ml-auto">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-20 text-center text-slate-400 italic text-sm uppercase tracking-widest">Belum ada data pengguna.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </main>

    {{-- MODAL TAMBAH ADMIN --}}
    <div id="modalAdmin" class="fixed inset-0 z-[60] hidden">
        {{-- Backdrop Blur --}}
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
        
        {{-- Modal Content --}}
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-[2rem] shadow-2xl p-8 z-10">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black uppercase tracking-tight text-slate-900">Tambah <span class="text-emerald-500 italic">Admin.</span></h3>
                <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-red-100 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('superadmin.users.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Nama Admin</label>
                    <input type="text" name="nama" required placeholder="Masukkan Nama Admin" class="w-full bg-slate-50 border border-slate-200 px-5 py-3 rounded-xl text-sm font-bold text-slate-700 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all">
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">No WhatsApp</label>
                    <input type="text" name="no_telp" required placeholder="Masukkan No WhatsApp Anda" class="w-full bg-slate-50 border border-slate-200 px-5 py-3 rounded-xl text-sm font-bold text-slate-700 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all">
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Email (Untuk Login)</label>
                    <input type="email" name="email" required placeholder="Masukkan Email Anda" class="w-full bg-slate-50 border border-slate-200 px-5 py-3 rounded-xl text-sm font-bold text-slate-700 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all">
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Kata Sandi</label>
                    <input type="password" name="password" required placeholder="Minimal 8 karakter" class="w-full bg-slate-50 border border-slate-200 px-5 py-3 rounded-xl text-sm font-bold text-slate-700 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all">
                </div>
                
                <div class="pt-4 border-t border-slate-100 mt-6">
                    <button type="submit" class="w-full bg-emerald-500 text-white py-4 rounded-xl font-black uppercase text-[10px] tracking-widest hover:bg-slate-900 transition-all shadow-lg shadow-emerald-500/20">
                        Simpan Akun Admin
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPT UNTUK MODAL POP-UP --}}
    <script>
        const modal = document.getElementById('modalAdmin');
        function openModal() { modal.classList.remove('hidden'); }
        function closeModal() { modal.classList.add('hidden'); }
    </script>
</body>
</html>