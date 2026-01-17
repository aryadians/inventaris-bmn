<?php

namespace App\Filament\Resources\AssetResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MaintenancesRelationManager extends RelationManager
{
    protected static string $relationship = 'maintenances';

    // Judul Tab
    protected static ?string $title = 'Riwayat Perbaikan (Servis)';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggal_servis')
                    ->required()
                    ->default(now()),

                Forms\Components\TextInput::make('masalah')
                    ->label('Keluhan / Kerusakan')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('tindakan')
                    ->label('Tindakan Perbaikan')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('vendor')
                    ->label('Nama Bengkel/Teknisi')
                    ->placeholder('Misal: Toko Komputer Jaya'),

                Forms\Components\TextInput::make('biaya')
                    ->prefix('Rp')
                    ->numeric()
                    ->default(0),

                Forms\Components\Select::make('status')
                    ->options([
                        'PROSES' => 'Sedang Dikerjakan',
                        'SELESAI' => 'Selesai',
                    ])
                    ->default('SELESAI'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal_servis')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('masalah')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('tindakan')
                    ->limit(30),

                Tables\Columns\TextColumn::make('biaya')
                    ->money('IDR') // Format Rupiah otomatis
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'PROSES' => 'warning',
                        'SELESAI' => 'success',
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Catat Servis Baru'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
