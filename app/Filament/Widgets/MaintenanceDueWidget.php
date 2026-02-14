<?php

namespace App\Filament\Widgets;

use App\Models\Maintenance;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class MaintenanceDueWidget extends BaseWidget
{
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Jadwal Pemeliharaan Mendatang';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Maintenance::query()
                    ->where('status', '!=', 'completed')
                    ->where('status', '!=', 'cancelled')
                    ->orderBy('tanggal_servis')
            )
            ->columns([
                Tables\Columns\TextColumn::make('tanggal_servis')
                    ->label('Tgl Servis')
                    ->date('d M Y')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state <= now() ? 'danger' : 'warning'),
                
                Tables\Columns\TextColumn::make('asset.nama_barang')
                    ->label('Aset')
                    ->searchable(),

                Tables\Columns\TextColumn::make('masalah')
                    ->label('Masalah / Keterangan')
                    ->limit(50),

                Tables\Columns\TextColumn::make('vendor')
                    ->label('Vendor')
                    ->default('-'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'scheduled',
                        'danger' => 'overdue',
                    ]),
            ]);
    }
}
