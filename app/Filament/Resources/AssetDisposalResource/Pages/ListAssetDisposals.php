<?php

namespace App\Filament\Resources\AssetDisposalResource\Pages;

use App\Filament\Resources\AssetDisposalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssetDisposals extends ListRecords
{
    protected static string $resource = AssetDisposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
