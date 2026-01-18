<x-filament::page>
    <form wire:submit.prevent="save">
        {{ $this->form }}
    </form>

    @if($selectedRoomId && !$opnameCompleted)
        <div class="mt-6">
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="p-4">Ditemukan</th>
                            <th scope="col" class="px-6 py-3">Nama Barang</th>
                            <th scope="col" class="px-6 py-3">NUP</th>
                            <th scope="col" class="px-6 py-3">Kondisi Asal</th>
                            <th scope="col" class="px-6 py-3">Kondisi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $asset)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="w-4 p-4">
                                    <input type="checkbox" wire:model.defer="assetStates.{{$asset->id}}.found" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                </td>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $asset->nama_barang }}
                                </th>
                                <td class="px-6 py-4">{{ $asset->nup }}</td>
                                <td class="px-6 py-4">{{ $asset->kondisi }}</td>
                                <td class="px-6 py-4">
                                    <select wire:model.defer="assetStates.{{$asset->id}}.new_condition" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        <option value="BAIK">Baik</option>
                                        <option value="RUSAK_RINGAN">Rusak Ringan</option>
                                        <option value="RUSAK_BERAT">Rusak Berat</option>
                                    </select>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada aset di ruangan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    
    @if($opnameCompleted)
        <div class="mt-6 p-4 bg-white rounded-lg shadow">
            <h3 class="text-lg font-medium text-gray-900">Hasil Stock Opname</h3>
            <dl class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-3">
                <div class="px-4 py-5 bg-green-100 shadow rounded-lg overflow-hidden sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Cocok</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $report['matched'] }}</dd>
                </div>
                <div class="px-4 py-5 bg-yellow-100 shadow rounded-lg overflow-hidden sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Kondisi Berubah</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $report['condition_changed'] }}</dd>
                </div>
                <div class="px-4 py-5 bg-red-100 shadow rounded-lg overflow-hidden sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Tidak Ditemukan</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $report['not_found'] }}</dd>
                </div>
            </dl>
        </div>
    @endif
</x-filament::page>
