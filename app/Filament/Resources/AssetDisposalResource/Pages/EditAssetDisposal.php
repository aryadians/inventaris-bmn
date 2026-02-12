<?php

namespace App\Filament\Resources\AssetDisposalResource\Pages;

use App\Filament\Resources\AssetDisposalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAssetDisposal extends EditRecord
{
    protected static string $resource = AssetDisposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
