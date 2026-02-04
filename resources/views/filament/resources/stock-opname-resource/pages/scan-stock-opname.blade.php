<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Input Section -->
        <div class="p-4 bg-white rounded-xl shadow border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <label class="block text-sm font-medium mb-1">Scan QR Code / Barcode</label>
            <input type="text" 
                wire:model="scannedCode" 
                wire:keydown.enter="scan"
                autofocus
                class="w-full text-lg p-3 border rounded-lg dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500"
                placeholder="Klik disini lalu scan aset..."
            >
            <p class="text-xs text-gray-500 mt-2">Pastikan kursor aktif di kolom ini saat scanning.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-3 gap-4">
            <div class="p-4 bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 rounded-lg text-center">
                <div class="text-2xl font-bold">{{ $this->stats['total'] }}</div>
                <div class="text-xs uppercase tracking-wide">Total Scan</div>
            </div>
            <div class="p-4 bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-300 rounded-lg text-center">
                <div class="text-2xl font-bold">{{ $this->stats['found'] }}</div>
                <div class="text-xs uppercase tracking-wide">Sesuai</div>
            </div>
            <div class="p-4 bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-300 rounded-lg text-center">
                <div class="text-2xl font-bold">{{ $this->stats['wrong_room'] }}</div>
                <div class="text-xs uppercase tracking-wide">Salah Ruangan</div>
            </div>
        </div>

        <!-- Recent Scans List -->
        <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
             <div class="p-4 border-b dark:border-gray-700 font-bold">Riwayat Scan Terakhir</div>
             <ul>
                 @foreach($this->recentScans as $detail)
                    <li class="p-3 border-b dark:border-gray-700 flex justify-between items-center last:border-0">
                        <div>
                            <div class="font-bold">{{ $detail->asset->nama_barang }}</div>
                            <div class="text-xs text-gray-500">{{ $detail->asset->kode_barang }} - {{ $detail->asset->nup }}</div>
                        </div>
                        <div class="@if($detail->status == 'found') text-green-600 dark:text-green-400 @else text-red-600 dark:text-red-400 @endif font-bold text-sm uppercase">
                            {{ str_replace('_', ' ', $detail->status) }}
                        </div>
                    </li>
                 @endforeach
                 @if($this->recentScans->isEmpty())
                    <li class="p-4 text-center text-gray-500">Belum ada data scan.</li>
                 @endif
             </ul>
        </div>
        </div>

        <!-- Finish Button -->
        <div class="mt-8">
            <x-filament::button 
                wire:click="finish" 
                color="danger" 
                class="w-full h-12 text-lg"
                wire:confirm="Yakin ingin menyelesaikan audit? Aset yang belum discan akan ditandai sebagai HILANG."
            >
                Selesaikan & Finalisasi Audit
            </x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
