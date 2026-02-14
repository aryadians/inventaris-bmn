<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($this->getRoomsData() as $room)
    <div class="group relative p-6 bg-white rounded-3xl shadow-sm border border-slate-100 transition-all hover:shadow-xl hover:-translate-y-1 overflow-hidden">
        <!-- Decor Gradient -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
        
        <div class="relative">
            <div class="flex items-center justify-between mb-6">
                <div class="p-3 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-100 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <span class="text-[10px] font-black text-indigo-300 uppercase tracking-widest">{{ $room['code'] }}</span>
            </div>
            
            <h3 class="text-xl font-bold text-slate-800 mb-1">{{ $room['name'] }}</h3>
            <p class="text-xs text-slate-400 font-medium mb-6">Penanggung Jawab: <span class="text-slate-600">{{ $room['pj'] ?? '-' }}</span></p>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-slate-400">Status Ketersediaan</span>
                    <span class="px-2 py-0.5 bg-slate-100 rounded text-slate-600 font-bold">{{ $room['total_assets'] }} Aset</span>
                </div>
                
                <!-- Health Bar -->
                <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden flex shadow-inner">
                    @php
                        $baikWidth = $room['total_assets'] > 0 ? ($room['baik'] / $room['total_assets']) * 100 : 0;
                        $rrWidth = $room['total_assets'] > 0 ? ($room['rusak_ringan'] / $room['total_assets']) * 100 : 0;
                        $rbWidth = $room['total_assets'] > 0 ? ($room['rusak_berat'] / $room['total_assets']) * 100 : 0;
                    @endphp
                    <div class="h-full bg-emerald-500 transition-all duration-1000" style="width: {{ $baikWidth }}%"></div>
                    <div class="h-full bg-amber-400 transition-all duration-1000" style="width: {{ $rrWidth }}%"></div>
                    <div class="h-full bg-rose-500 transition-all duration-1000" style="width: {{ $rbWidth }}%"></div>
                </div>
                
                <div class="flex justify-between items-center gap-2">
                    <div class="flex-1 flex flex-col">
                        <span class="text-[10px] font-bold text-emerald-600 uppercase">Baik</span>
                        <span class="text-lg font-black text-slate-700">{{ $room['baik'] }}</span>
                    </div>
                    <div class="flex-1 flex flex-col border-l border-slate-100 pl-3">
                        <span class="text-[10px] font-bold text-amber-600 uppercase">RR</span>
                        <span class="text-lg font-black text-slate-700">{{ $room['rusak_ringan'] }}</span>
                    </div>
                    <div class="flex-1 flex flex-col border-l border-slate-100 pl-3">
                        <span class="text-[10px] font-bold text-rose-600 uppercase">RB</span>
                        <span class="text-lg font-black text-slate-700">{{ $room['rusak_berat'] }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-50 flex items-center justify-between">
                 <div class="flex -space-x-2">
                    <div class="w-6 h-6 rounded-full bg-indigo-100 border-2 border-white"></div>
                    <div class="w-6 h-6 rounded-full bg-indigo-200 border-2 border-white"></div>
                    <div class="w-6 h-6 rounded-full bg-indigo-300 border-2 border-white"></div>
                 </div>
                 <a href="/admin/assets?tableFilters[room_id][value]={{ $room['id'] }}" class="inline-flex items-center text-xs font-bold text-indigo-600 group-hover:translate-x-1 transition-transform">
                    Kelola Aset 
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                 </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
