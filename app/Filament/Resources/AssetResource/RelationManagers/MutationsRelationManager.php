<?php

namespace App\Filament\Resources\AssetResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MutationsRelationManager extends RelationManager
{
    protected static string $relationship = 'mutations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ruangan_tujuan')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('ruangan_tujuan')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Mutasi')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ruangan_asal')
                    ->label('Dari')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('ruangan_tujuan')
                    ->label('Ke')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('penanggung_jawab_baru')
                    ->label('Pj. Baru'),
                Tables\Columns\TextColumn::make('petugas')
                    ->label('Petugas Input'),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([5, 10]); // Batasi jumlah baris agar aplikasi tetap ringan
    }
}
