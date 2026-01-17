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
use Illuminate\Database\Eloquent\SoftDeletingScope;
// Import Wajib 1: Library QR Code
use SimpleSoftwareIO\QrCode\Facades\QrCode;
// Import Wajib 2: Helper untuk menampilkan HTML/Gambar
use Illuminate\Support\HtmlString;

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
                // Kolom NUP
                Tables\Columns\TextColumn::make('nup')
                    ->label('NUP')
                    ->sortable(),

                // === KOLOM QR CODE (VERSI ANTI-GAGAL) ===
                Tables\Columns\TextColumn::make('qr_code')
                    ->label('QR Scan')
                    ->html()
                    ->getStateUsing(function ($record) {
                        // 1. Generate SVG Code
                        $svg = QrCode::format('svg')
                            ->size(50)
                            ->color(0, 0, 0)
                            ->backgroundColor(255, 255, 255)
                            ->margin(1)
                            ->generate($record->kode_barang . '-' . $record->nup);

                        // 2. Ubah jadi Base64 String (Agar dianggap gambar oleh browser)
                        $base64 = base64_encode($svg);

                        // 3. Return sebagai tag IMG dengan background putih paksa
                        return new HtmlString('
                            <img src="data:image/svg+xml;base64,' . $base64 . '"
                                 alt="QR"
                                 style="background-color: white; padding: 2px; border-radius: 4px;"
                                 width="50"
                                 height="50"
                            />
                        ');
                    }),
                // ========================================

                // Kolom Kode & Nama
                Tables\Columns\TextColumn::make('kode_barang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_barang')
                    ->searchable()
                    ->weight('bold'),

                // Kolom Lokasi (Relasi ke Rooms)
                Tables\Columns\TextColumn::make('room.nama_ruangan')
                    ->label('Lokasi')
                    ->sortable()
                    ->searchable(),

                // Kolom Kondisi (Badge Warna-warni)
                Tables\Columns\TextColumn::make('kondisi')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'BAIK' => 'success',        // Hijau
                        'RUSAK_RINGAN' => 'warning', // Kuning
                        'RUSAK_BERAT' => 'danger',   // Merah
                    }),

                // Kolom Harga (Format IDR)
                Tables\Columns\TextColumn::make('harga_perolehan')
                    ->money('IDR')
                    ->label('Harga'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            RelationManagers\LoansRelationManager::class, // Yang tadi (Peminjaman)
            RelationManagers\MaintenancesRelationManager::class, // YANG BARU (Servis)
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
}
