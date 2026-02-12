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
                Forms\Components\Section::make('Laporan Kerusakan')
                    ->schema([
                        Forms\Components\Select::make('asset_id')
                            ->relationship('asset', 'nama_barang')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Aset yang Bermasalah'),
                        Forms\Components\Select::make('pelapor_id')
                            ->relationship('pelapor', 'name')
                            ->default(auth()->id())
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Pelapor'),
                        Forms\Components\DatePicker::make('tanggal_lapor')
                            ->default(now())
                            ->required(),
                        Forms\Components\Textarea::make('masalah')
                            ->label('Deskripsi Masalah / Kerusakan')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('bukti_foto')
                            ->image()
                            ->directory('maintenance-proofs')
                            ->columnSpanFull(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Tindak Lanjut Perbaikan')
                    ->description('Diisi oleh teknisi atau admin saat perbaikan dilakukan.')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Select::make('status')
                                ->options([
                                    'pending' => 'Menunggu (Pending)',
                                    'processing' => 'Sedang Diproses',
                                    'completed' => 'Selesai (Fixed)',
                                    'unrepairable' => 'Rusak Berat (Tidak Bisa Diperbaiki)'
                                ])
                                ->default('pending')
                                ->required(),
                            Forms\Components\DatePicker::make('tanggal_selesai'),
                        ]),
                        Forms\Components\Textarea::make('tindakan')
                            ->label('Tindakan Perbaikan')
                            ->columnSpanFull(),
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('vendor')
                                ->label('Nama Vendor / Teknisi'),
                            Forms\Components\TextInput::make('biaya')
                                ->numeric()
                                ->prefix('Rp')
                                ->default(0),
                        ]),
                    ])
                    ->collapsible()
                    ->collapsed(fn ($record) => $record === null), // Auto collapse on create
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->columns([
                Tables\Columns\ImageColumn::make('bukti_foto')
                    ->circular(),
                Tables\Columns\TextColumn::make('asset.nama_barang')
                    ->label('Aset')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Maintenance $record): string => $record->asset->kode_barang ?? '-'),
                Tables\Columns\TextColumn::make('pelapor.name')
                    ->label('Pelapor')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_lapor')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'processing' => 'warning',
                        'completed' => 'success',
                        'unrepairable' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('biaya')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->defaults([
                Tables\Columns\TextColumn::make('tanggal_lapor')->sortable('desc'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'unrepairable' => 'Rusak Berat',
                    ]),
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