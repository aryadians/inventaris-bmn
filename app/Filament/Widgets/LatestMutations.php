<?php

namespace App\Filament\Widgets;

use App\Models\Mutation;
use App\Models\Asset;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestMutations extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Riwayat Mutasi BMN Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Mutation::query()->with(['asset', 'oldRoom', 'newRoom'])->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->color('gray'),

                Tables\Columns\TextColumn::make('asset.nama_barang')
                    ->label('Nama Barang')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('oldRoom.nama_ruangan')
                    ->label('Asal')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\IconColumn::make('arrow')
                    ->label('')
                    ->icon('heroicon-m-arrow-long-right')
                    ->grow(false),

                Tables\Columns\TextColumn::make('newRoom.nama_ruangan')
                    ->label('Tujuan')
                    ->badge()
                    ->color('success'),
            ])
            ->paginated(false);
    }
}
