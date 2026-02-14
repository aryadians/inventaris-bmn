<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $asset->nama_barang }} - SIMA Lapas Jombang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-900">

    <div class="max-w-lg mx-auto min-h-screen shadow-2xl bg-white flex flex-col relative">
        
        <!-- Top Nav -->
        <div class="absolute top-0 left-0 right-0 p-6 flex justify-between items-center z-10">
            <div class="w-10 h-10 rounded-2xl glass flex items-center justify-center shadow-sm border border-white/50">
                <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
            </div>
            <div class="px-4 py-2 rounded-2xl glass shadow-sm border border-white/50 text-[10px] font-black uppercase tracking-widest text-slate-500">
                Aset #{{ $asset->nup }}
            </div>
        </div>

        <!-- Hero Section -->
        <div class="relative h-[400px] bg-indigo-600 rounded-b-[3rem] overflow-hidden shadow-2xl">
            @if($asset->foto)
                <img src="{{ asset('storage/' . $asset->foto) }}" class="w-full h-full object-cover">
            @else
                <div class="flex flex-col items-center justify-center h-full text-indigo-200">
                    <svg class="w-24 h-24 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <p class="font-bold text-sm tracking-widest uppercase opacity-50">Gambar Tidak Tersedia</p>
                </div>
            @endif
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-indigo-900/80 via-transparent to-transparent"></div>
            
            <!-- Badge over Image -->
            <div class="absolute bottom-10 left-8">
                <span class="px-4 py-2 bg-emerald-400 text-indigo-950 text-[11px] font-black rounded-xl uppercase tracking-widest shadow-xl">
                    {{ $asset->category->nama_kategori ?? 'Aset BMN' }}
                </span>
                <h1 class="text-3xl font-extrabold text-white mt-4 leading-tight">{{ $asset->nama_barang }}</h1>
            </div>
        </div>

        <!-- Main Info Content -->
        <div class="px-8 -mt-8 relative z-20">
            @if(session('success'))
                <div class="mb-6 p-5 bg-emerald-500 text-white rounded-3xl text-sm font-bold shadow-xl shadow-emerald-200 flex items-center animate-bounce">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Dashboard Grid -->
            <div class="grid grid-cols-2 gap-4">
                <div class="p-5 bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-100/50">
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-500 mb-3">
                        @svg('heroicon-o-map-pin', 'w-4 h-4')
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">Lokasi</p>
                    <p class="text-sm font-bold text-slate-700 truncate">{{ $asset->room->nama_ruangan ?? 'Gudang' }}</p>
                </div>
                <div class="p-5 bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-100/50">
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500 mb-3">
                        @svg('heroicon-o-check-circle', 'w-4 h-4')
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">Kondisi</p>
                    <p class="text-sm font-bold text-slate-700">{{ $asset->kondisi ?? 'BAIK' }}</p>
                </div>
            </div>

            <!-- Detailed Info -->
            <div class="mt-8 space-y-4">
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                    <span class="text-xs font-bold text-slate-500">Kode Akun</span>
                    <span class="text-xs font-mono font-bold text-slate-700">{{ $asset->kode_barang }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                    <span class="text-xs font-bold text-slate-500">Penanggung Jawab</span>
                    <span class="text-xs font-bold text-slate-700">{{ $asset->room->penanggung_jawab ?? '-' }}</span>
                </div>
            </div>

            <!-- Call to Actions -->
            <div class="mt-10 grid grid-cols-2 gap-4">
                <button onclick="toggleForm('damage')" class="group p-6 bg-rose-50 rounded-[2.5rem] border-2 border-rose-100 flex flex-col items-center justify-center transition-all active:scale-95 hover:bg-rose-100">
                    <div class="w-12 h-12 bg-rose-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-rose-200 mb-3 group-hover:rotate-12 transition-transform">
                        @svg('heroicon-o-exclamation-triangle', 'w-6 h-6')
                    </div>
                    <span class="text-xs font-black text-rose-700 uppercase">Lapor Rusak</span>
                </button>

                <button onclick="toggleForm('loan')" class="group p-6 bg-indigo-50 rounded-[2.5rem] border-2 border-indigo-100 flex flex-col items-center justify-center transition-all active:scale-95 hover:bg-indigo-100">
                    <div class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-200 mb-3 group-hover:-rotate-12 transition-transform">
                        @svg('heroicon-o-calendar', 'w-6 h-6')
                    </div>
                    <span class="text-xs font-black text-indigo-700 uppercase">Pinjam</span>
                </button>
            </div>

            <!-- Forms Container -->
            <div class="mt-8 pb-12">
                <!-- Damage Form -->
                <div id="form-damage" class="hidden animate-in fade-in slide-in-from-bottom-4 duration-500 p-8 bg-slate-900 rounded-[3rem] text-white">
                    <h3 class="text-xl font-bold mb-6">Laporan Kerusakan</h3>
                    <form action="{{ route('public.asset.report') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                        <div class="space-y-5">
                            <input type="text" name="reporter_name" placeholder="Nama Anda" required class="w-full bg-slate-800 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-rose-500">
                            <textarea name="problem" placeholder="Jelaskan kendala barang..." required rows="3" class="w-full bg-slate-800 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-rose-500"></textarea>
                            <input type="file" name="photo" accept="image/*" class="text-xs text-slate-400">
                            <button type="submit" class="w-full py-4 bg-rose-500 text-white font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-rose-900/20">Kirim Laporan</button>
                        </div>
                    </form>
                </div>

                <!-- Loan Form -->
                <div id="form-loan" class="hidden animate-in fade-in slide-in-from-bottom-4 duration-500 p-8 bg-indigo-950 rounded-[3rem] text-white">
                    <h3 class="text-xl font-bold mb-6">Ajukan Peminjaman</h3>
                    <form action="{{ route('public.asset.loan') }}" method="POST">
                        @csrf
                        <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                        <div class="space-y-5">
                            <input type="text" name="requester_name" placeholder="Nama Lengkap" required class="w-full bg-indigo-900 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-emerald-400">
                            <input type="tel" name="requester_phone" placeholder="Nomor WhatsApp (08...)" required class="w-full bg-indigo-900 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-emerald-400">
                            <input type="number" name="duration_days" value="1" min="1" class="w-full bg-indigo-900 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-emerald-400">
                            <button type="submit" class="w-full py-4 bg-emerald-400 text-indigo-950 font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-emerald-400/20">Konfirmasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleForm(type) {
            const damage = document.getElementById('form-damage');
            const loan = document.getElementById('form-loan');
            
            if (type === 'damage') {
                damage.classList.toggle('hidden');
                loan.classList.add('hidden');
            } else {
                loan.classList.toggle('hidden');
                damage.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
