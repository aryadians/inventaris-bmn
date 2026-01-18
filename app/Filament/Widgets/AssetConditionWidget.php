<?php

namespace App\Filament\Widgets;

use App\Models\Asset;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class AssetConditionWidget extends BaseWidget
{
    protected static ?int $sort = 2; // Urutan ketiga, di sebelah chart

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Ringkasan Kondisi BMN';

    public function getTableRecordKey($record): string
    {
        return $record->kondisi;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Asset::query()
                    ->selectRaw('kondisi, count(*) as total, MIN(id) as id')
                    ->groupBy('kondisi')
            )
            ->columns([
                Tables\Columns\TextColumn::make('kondisi')
                    ->label('Kondisi Barang')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'BAIK' => 'success',
                        'RUSAK_RINGAN' => 'warning',
                        'RUSAK_BERAT' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('total')
                    ->label('Jumlah Unit')
                    ->suffix(' Unit')
                    ->alignEnd()
                    ->weight('bold'),
            ])
            ->paginated(false);
    }
}
