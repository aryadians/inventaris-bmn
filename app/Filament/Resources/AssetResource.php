<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetResource\Pages;
use App\Filament\Resources\AssetResource\RelationManagers;
use App\Models\Asset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope; // Import Penting untuk Soft Delete
use SimpleSoftwareIO\QrCode\Facades\QrCode; // Import QR Code
use Illuminate\Support\HtmlString; // Import HTML String

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Label Navigasi
    protected static ?string $navigationLabel = 'Aset BMN';
    protected static ?string $pluralModelLabel = 'Data Aset';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // --- BAGIAN 1: UPLOAD FOTO ---
                Forms\Components\FileUpload::make('foto')
                    ->label('Foto Barang Fisik')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('aset-images')
                    ->visibility('public')
                    ->columnSpanFull(),

                // --- BAGIAN 2: DATA UTAMA ---
                // 1. Input Ruangan (Dropdown Relasi)
                Forms\Components\Select::make('room_id')
                    ->relationship('room', 'nama_ruangan')
                    ->label('Lokasi Ruangan')
                    ->searchable()
                    ->preload()
                    ->required(),

                // 2. Input Kode Barang
                Forms\Components\TextInput::make('kode_barang')
                    ->required(),

                // 3. Input Nama Barang
                Forms\Components\TextInput::make('nama_barang')
                    ->required(),

                // 4. NUP (Hidden - Diisi otomatis oleh logic create)
                Forms\Components\Hidden::make('nup'),

                // 5. Kondisi (Dropdown Pilihan)
                Forms\Components\Select::make('kondisi')
                    ->options([
                        'BAIK' => 'Baik',
                        'RUSAK_RINGAN' => 'Rusak Ringan',
                        'RUSAK_BERAT' => 'Rusak Berat',
                    ])
                    ->required(),

                // 6. Tanggal Perolehan
                Forms\Components\DatePicker::make('tanggal_perolehan')
                    ->required(),

                // 7. Harga (Format Rupiah)
                Forms\Components\TextInput::make('harga_perolehan')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Foto Thumbnail
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Fisik')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.png')),

                // 2. NUP
                Tables\Columns\TextColumn::make('nup')
                    ->label('NUP')
                    ->sortable(),

                // 3. QR Code Generator (SVG Base64)
                Tables\Columns\TextColumn::make('qr_code')
                    ->label('QR Scan')
                    ->html()
                    ->getStateUsing(function ($record) {
                        // Generate QR Code saat runtime
                        if (!$record->kode_barang || !$record->nup) return '-';

                        $svg = QrCode::format('svg')
                            ->size(50)
                            ->color(0, 0, 0)
                            ->backgroundColor(255, 255, 255)
                            ->margin(1)
                            ->generate($record->kode_barang . '-' . $record->nup);

                        $base64 = base64_encode($svg);

                        return new HtmlString('
                            <img src="data:image/svg+xml;base64,' . $base64 . '" 
                                 alt="QR" 
                                 style="background-color: white; padding: 2px; border-radius: 4px;"
                                 width="50" 
                                 height="50" 
                            />
                        ');
                    }),

                // 4. Data Barang
                Tables\Columns\TextColumn::make('kode_barang')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true), // Sembunyi default biar rapi

                Tables\Columns\TextColumn::make('nama_barang')
                    ->searchable()
                    ->weight('bold'),

                // 5. Lokasi
                Tables\Columns\TextColumn::make('room.nama_ruangan')
                    ->label('Lokasi')
                    ->sortable()
                    ->searchable(),

                // 6. Kondisi Badge
                Tables\Columns\TextColumn::make('kondisi')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'BAIK' => 'success',
                        'RUSAK_RINGAN' => 'warning',
                        'RUSAK_BERAT' => 'danger',
                    }),

                // 7. Harga
                Tables\Columns\TextColumn::make('harga_perolehan')
                    ->money('IDR')
                    ->label('Harga'),
            ])
            ->filters([
                // FILTER 1: SAMPAL (TRASHED)
                // Filter ini memungkinkan kita melihat barang yang sudah dihapus (soft delete)
                Tables\Filters\TrashedFilter::make(),

                // FILTER 2: Kondisi
                Tables\Filters\SelectFilter::make('kondisi')
                    ->options([
                        'BAIK' => 'Baik',
                        'RUSAK_RINGAN' => 'Rusak Ringan',
                        'RUSAK_BERAT' => 'Rusak Berat',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                // ACTION HAPUS (Soft Delete)
                Tables\Actions\DeleteAction::make(),

                // ACTION RESTORE (Muncul hanya jika barang ada di sampah)
                Tables\Actions\RestoreAction::make(),

                // ACTION FORCE DELETE (Hapus Permanen)
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

                    // 1. BULK CETAK USULAN
                    Tables\Actions\BulkAction::make('cetak_usulan')
                        ->label('Cetak Usulan Penghapusan')
                        ->icon('heroicon-o-printer')
                        ->color('warning')
                        ->action(function ($records) {
                            $ids = $records->pluck('id')->implode(',');
                            return redirect()->route('cetak_usulan', ['ids' => $ids]);
                        })
                        ->deselectRecordsAfterCompletion(),

                    // 2. BULK HAPUS (SOFT)
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Arsipkan Data'),

                    // 3. BULK RESTORE (PULIHKAN BANYAK)
                    Tables\Actions\RestoreBulkAction::make(),

                    // 4. BULK FORCE DELETE (HAPUS PERMANEN BANYAK)
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LoansRelationManager::class,       // Riwayat Peminjaman
            RelationManagers\MaintenancesRelationManager::class, // Riwayat Servis
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'edit' => Pages\EditAsset::route('/{record}/edit'),
        ];
    }

    // PENTING: Override fungsi query agar Filter Sampah (Trashed) berfungsi normal
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
