<?php

namespace App\Filament\Widgets;

use App\Models\Room;
use App\Models\Asset;
use Filament\Widgets\Widget;

class RoomInventoryStats extends Widget
{
    protected static string $view = 'filament.widgets.room-inventory-stats';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 6;

    public function getRoomsData()
    {
        return Room::with('assets')->get()->map(function ($room) {
            return [
                'id' => $room->id,
                'name' => $room->nama_ruangan,
                'code' => $room->kode_ruangan,
                'total_assets' => $room->assets->count(),
                'baik' => $room->assets->where('kondisi', 'BAIK')->count(),
                'rusak_ringan' => $room->assets->where('kondisi', 'RUSAK_RINGAN')->count(),
                'rusak_berat' => $room->assets->where('kondisi', 'RUSAK_BERAT')->count(),
            ];
        });
    }
}
