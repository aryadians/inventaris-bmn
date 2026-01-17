<?php

namespace App\Filament\Resources\AssetResource\Pages;

use App\Filament\Resources\AssetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssets extends ListRecords
{
    protected static string $resource = AssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tombol Cetak PDF Custom
            Actions\Action::make('cetak_laporan')
                ->label('Cetak Laporan PDF')
                ->icon('heroicon-o-printer')
                ->url(route('cetak_laporan')) // Memanggil route yang kita buat tadi
                ->openUrlInNewTab(), // Buka tab baru biar admin panel gak ketutup
            
            Actions\CreateAction::make(),
        ];
    }
}