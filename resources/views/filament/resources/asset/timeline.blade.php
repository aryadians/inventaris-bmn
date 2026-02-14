<div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-300 before:to-transparent">

    @php
        $history = collect([]);
        
        // 1. Data Perolehan
        $history->push([
            'date' => $record->tanggal_perolehan,
            'title' => 'Barang Terdaftar (Perolehan)',
            'description' => 'Aset masuk ke sistem dengan harga Rp ' . number_format($record->harga_perolehan, 0, ',', '.'),
            'icon' => 'heroicon-m-shopping-cart',
            'color' => 'bg-emerald-500',
        ]);

        // 2. Data Mutasi
        foreach ($record->mutations as $mutation) {
            $history->push([
                'date' => $mutation->created_at,
                'title' => 'Mutasi Ruangan',
                'description' => "Pindah dari {$mutation->ruangan_asal} ke {$mutation->ruangan_tujuan} (PJ: {$mutation->penanggung_jawab_baru})",
                'icon' => 'heroicon-m-arrows-right-left',
                'color' => 'bg-blue-500',
            ]);
        }

        // 3. Data Pemeliharaan
        foreach ($record->maintenances->where('status', 'completed') as $maintenance) {
            $history->push([
                'date' => $maintenance->tanggal_selesai,
                'title' => 'Pemeliharaan Selesai',
                'description' => "Selesai diperbaiki: {$maintenance->masalah}. Biaya: Rp " . number_format($maintenance->biaya, 0, ',', '.'),
                'icon' => 'heroicon-m-wrench-screwdriver',
                'color' => 'bg-amber-500',
            ]);
        }

        // 4. Data Peminjaman
        foreach ($record->loans as $loan) {
            $history->push([
                'date' => $loan->tanggal_pinjam,
                'title' => 'Aset Dipinjam',
                'description' => "Dipinjam oleh {$loan->user->name}. Rencana kembali: " . ($loan->tanggal_kembali_rencana ? $loan->tanggal_kembali_rencana->format('d/m/Y') : '-'),
                'icon' => 'heroicon-m-user-group',
                'color' => 'bg-purple-500',
            ]);
        }

        $sortedHistory = $history->sortByDesc('date');
    @endphp

    @foreach($sortedHistory as $item)
    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
        <!-- Icon -->
        <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 {{ $item['color'] }} text-white">
            @svg($item['icon'], 'w-5 h-5')
        </div>
        <!-- Content -->
        <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-4 rounded border border-slate-200 bg-white shadow">
            <div class="flex items-center justify-between space-x-2 mb-1">
                <div class="font-bold text-slate-900">{{ $item['title'] }}</div>
                <time class="font-medium text-blue-500 text-xs">{{ \Carbon\Carbon::parse($item['date'])->format('d M Y') }}</time>
            </div>
            <div class="text-slate-500 text-sm italic">{{ $item['description'] }}</div>
        </div>
    </div>
    @endforeach

</div>
