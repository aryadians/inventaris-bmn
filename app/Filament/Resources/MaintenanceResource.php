<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceResource\Pages;
use App\Models\Maintenance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MaintenanceResource extends Resource
{
    protected static ?string $model = Maintenance::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Servis & Perbaikan';

    protected static ?string $pluralModelLabel = 'Servis & Perbaikan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('asset_id')
                    ->relationship('asset', 'nama_barang')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Aset yang Diservis'),
                Forms\Components\DatePicker::make('tanggal_servis')
                    ->required(),
                Forms\Components\Textarea::make('masalah')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('tindakan')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('vendor')
                    ->label('Vendor/Toko Servis'),
                Forms\Components\TextInput::make('biaya')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'PROSES' => 'Dalam Proses',
                        'SELESAI' => 'Selesai',
                    ])
                    ->required()
                    ->default('PROSES'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('asset.nama_barang')
                    ->label('Nama Aset')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_servis')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('masalah')
                    ->limit(40)
                    ->wrap(),
                Tables\Columns\TextColumn::make('biaya')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'PROSES' => 'warning',
                        'SELESAI' => 'success',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListMaintenances::route('/'),
            'create' => Pages\CreateMaintenance::route('/create'),
            'edit' => Pages\EditMaintenance::route('/{record}/edit'),
        ];
    }
}