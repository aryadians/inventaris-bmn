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
                    ->label('Tanggal Pindah')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ruangan_asal')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\IconColumn::make('panah')
                    ->defaultIcon('heroicon-m-arrow-right')
                    ->label(''),
                Tables\Columns\TextColumn::make('ruangan_tujuan')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('petugas')
                    ->label('Dipindahkan Oleh'),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
