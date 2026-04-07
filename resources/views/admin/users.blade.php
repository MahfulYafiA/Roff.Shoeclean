<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pelanggan - ROFF.ADMIN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .sidebar-active { background: #2563eb; color: white; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3); }
        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
</head>
<body class="text-slate-900 antialiased flex h-screen overflow-hidden">

    {{-- SIDEBAR ADMIN (TEMA BIRU) --}}
    <aside class="w-72 bg-white border-r border-slate-200 flex flex-col h-full shrink-0 z-50">
        <div class="p-8">
            <h1 class="font-black text-2xl uppercase tracking-tighter italic text-slate-900">
                ROFF.<span class="text-blue-600">ADMIN</span>
            </h1>
        </div>

        <nav class="flex-1 px-4 space-y-2 mt-4">
            {{-- Menu Aktif --}}
            <a href="{{ route('admin.users') }}" class="flex items-center gap-4 sidebar-active px-6 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all">
                <i class="fa-solid fa-users text-lg"></i>
                Kelola Pelanggan
            </a>
            
            <div class="pt-6 border-t border-slate-100 mt-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 text-slate-400 hover:text-blue-600 px-6 py-4 rounded-2xl font-bold text-[11px] uppercase tracking-widest transition-all group">
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
                <h2 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Customer <span class="text-blue-600 italic">Management.</span></h2>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-[0.2em] mt-1 text-blue-600/60">Admin Control Panel</p>
            </div>
            <div class="bg-blue-50 border border-blue-100 px-4 py-2 rounded-xl">
                <p class="text-[9px] font-black text-blue-600 uppercase tracking-widest">Role: Administrator</p>
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

            {{-- STATISTIK SINGKAT --}}
            <div class="mb-10 w-full max-w-sm">
                <div class="bg-blue-600 p-8 rounded-[2rem] shadow-xl shadow-blue-500/20">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-blue-100 uppercase tracking-widest mb-2">Total Pelanggan</p>
                            <span class="text-4xl font-black text-white italic">{{ $totalPelanggan ?? $users->count() }}</span>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center text-white text-2xl">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABEL PELANGGAN --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Pelanggan</th>
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
                                {{-- 🚨 UPDATE IKON WA MENJADI HIJAU (EMERALD) --}}
                                <p class="text-[10px] text-slate-500 font-bold mt-1">
                                    <i class="fa-brands fa-whatsapp text-emerald-500 mr-1"></i> {{ $u->no_telp ?? '-' }}
                                </p>
                            </td>
                            <td class="px-8 py-6">
                                <span class="bg-slate-100 text-slate-500 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border border-slate-200">Customer</span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <form action="{{ route('admin.users.destroy', $u->id_user) }}" method="POST" onsubmit="return confirm('Hapus pelanggan ini beserta semua data reservasinya?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-full bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors flex items-center justify-center ml-auto">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-20 text-center text-slate-400 italic text-sm uppercase tracking-widest">Belum ada pelanggan yang mendaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>