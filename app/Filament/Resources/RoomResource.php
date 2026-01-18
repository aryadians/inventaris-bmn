<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Filament\Resources\RoomResource\RelationManagers;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Data Ruangan';
    protected static ?string $pluralModelLabel = 'Data Ruangan';

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view rooms');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create rooms');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('edit rooms');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete rooms');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()->can('delete rooms');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode_ruangan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_ruangan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('penanggung_jawab')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_ruangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_ruangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('penanggung_jawab')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}
