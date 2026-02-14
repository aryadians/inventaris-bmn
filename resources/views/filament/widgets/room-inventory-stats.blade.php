<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($this->getRoomsData() as $room)
    <div class="p-5 bg-white rounded-xl shadow-sm border border-slate-200 transition-all hover:shadow-md">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-slate-800">{{ $room['name'] }}</h3>
            <span class="px-2 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold rounded uppercase">
                {{ $room['code'] }}
            </span>
        </div>
        
        <div class="space-y-3">
            <div class="flex items-center justify-between text-xs">
                <span class="text-slate-400">Total Aset</span>
                <span class="font-bold">{{ $room['total_assets'] }} Unit</span>
            </div>
            
            <!-- Health Bar -->
            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden flex">
                @php
                    $baikWidth = $room['total_assets'] > 0 ? ($room['baik'] / $room['total_assets']) * 100 : 0;
                    $rusakWidth = $room['total_assets'] > 0 ? (($room['rusak_ringan'] + $room['rusak_berat']) / $room['total_assets']) * 100 : 0;
                @endphp
                <div class="h-full bg-emerald-500" style="width: {{ $baikWidth }}%"></div>
                <div class="h-full bg-amber-400" style="width: {{ $rusakWidth }}%"></div>
            </div>
            
            <div class="grid grid-cols-3 gap-1 text-[9px] font-bold text-center uppercase">
                <div class="text-emerald-600">Baik: {{ $room['baik'] }}</div>
                <div class="text-amber-600">RR: {{ $room['rusak_ringan'] }}</div>
                <div class="text-red-600">RB: {{ $room['rusak_berat'] }}</div>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top border-slate-100 flex justify-end">
             <a href="/admin/assets?tableFilters[room_id][value]={{ $room['id'] }}" class="text-[10px] font-bold text-blue-600 hover:underline">Lihat Detail →</a>
        </div>
    </div>
    @endforeach
</div>
