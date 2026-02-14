<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Aset - SIMA Lapas Jombang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">

    <div class="max-w-md mx-auto min-h-screen bg-white shadow-lg overflow-hidden flex flex-col">
        <!-- Header -->
        <div class="bg-blue-600 p-6 text-white text-center">
            <h1 class="text-xl font-bold uppercase tracking-wide">Informasi Aset BMN</h1>
            <p class="text-blue-100 text-sm">Lapas Kelas IIB Jombang</p>
        </div>

        <!-- Hero Image -->
        <div class="h-48 bg-slate-200 relative overflow-hidden">
            @if($asset->foto)
                <img src="{{ asset('storage/' . $asset->foto) }}" class="w-full h-full object-cover">
            @else
                <div class="flex items-center justify-center h-full text-slate-400">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            @endif
            <div class="absolute bottom-4 left-4">
                <span class="bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                    {{ $asset->category->nama_kategori ?? 'Aset' }}
                </span>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6 flex-grow">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-100 text-emerald-800 rounded-lg text-sm font-medium border border-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            <h2 class="text-2xl font-bold mb-1">{{ $asset->nama_barang }}</h2>
            <p class="text-slate-500 text-sm mb-6">NUP: #{{ $asset->nup }} | Kode: {{ $asset->kode_barang }}</p>

            <div class="grid grid-cols-2 gap-4 mb-8">
                <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-tighter">Lokasi</p>
                    <p class="text-sm font-semibold">{{ $asset->room->nama_ruangan ?? 'Gudang' }}</p>
                </div>
                <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-tighter">Status</p>
                    <p class="text-sm font-semibold flex items-center">
                        <span class="w-2 h-2 rounded-full mr-2 {{ $asset->status == 'Tersedia' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                        {{ $asset->status ?? 'Tersedia' }}
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-4">
                <button onclick="toggleForm('damage')" class="w-full py-4 bg-red-50 text-red-700 font-bold rounded-xl border border-red-100 flex items-center justify-center transition-all hover:bg-red-100">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Laporkan Kerusakan
                </button>

                <button onclick="toggleForm('loan')" class="w-full py-4 bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-200 flex items-center justify-center transition-all hover:bg-blue-700 active:scale-95">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Ajukan Peminjaman
                </button>
            </div>

            <!-- Damage Report Form (Hidden) -->
            <div id="form-damage" class="hidden mt-8 p-6 bg-slate-50 rounded-2xl border border-slate-200">
                <h3 class="font-bold text-lg mb-4">Form Laporan Kerusakan</h3>
                <form action="{{ route('public.asset.report') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">Nama Pelapor</label>
                            <input type="text" name="reporter_name" required class="w-full p-3 rounded-lg border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">Masalah / Kendala</label>
                            <textarea name="problem" required rows="3" class="w-full p-3 rounded-lg border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">Foto Bukti (Opsional)</label>
                            <input type="file" name="photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                        <button type="submit" class="w-full py-3 bg-red-600 text-white font-bold rounded-lg mt-4">Kirim Laporan</button>
                    </div>
                </form>
            </div>

            <!-- Loan Form (Hidden) -->
            <div id="form-loan" class="hidden mt-8 p-6 bg-slate-50 rounded-2xl border border-slate-200">
                <h3 class="font-bold text-lg mb-4">Form Peminjaman</h3>
                <form action="{{ route('public.asset.loan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">Nama Peminjam</label>
                            <input type="text" name="requester_name" required class="w-full p-3 rounded-lg border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">No. WhatsApp</label>
                            <input type="tel" name="requester_phone" required class="w-full p-3 rounded-lg border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">Durasi (Hari)</label>
                            <input type="number" name="duration_days" value="1" min="1" required class="w-full p-3 rounded-lg border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-lg mt-4">Ajukan ke Admin</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-6 bg-slate-50 text-center">
            <p class="text-[10px] text-slate-400">© {{ date('Y') }} SIMA Lapas Kelas IIB Jombang<br>Sistem Informasi Manajemen Aset</p>
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
