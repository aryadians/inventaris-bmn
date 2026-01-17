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
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\HtmlString;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Aset BMN';
    protected static ?string $pluralModelLabel = 'Data Aset';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar & Klasifikasi')
                    ->schema([
                        Forms\Components\FileUpload::make('foto')
                            ->label('Foto Barang Fisik')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('aset-images')
                            ->visibility('public')
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(2)->schema([
                            // FITUR KATEGORI OTOMATIS
                            Forms\Components\Select::make('category_id')
                                ->label('Kategori Barang (BMN)')
                                ->relationship('category', 'nama_kategori')
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(function ($state, Forms\Set $set) {
                                    if ($state) {
                                        $category = \App\Models\Category::find($state);
                                        $set('kode_barang', $category->kode_kategori);
                                    }
                                })
                                ->required(),

                            Forms\Components\TextInput::make('kode_barang')
                                ->label('Kode Akun BMN')
                                ->disabled()
                                ->dehydrated()
                                ->placeholder('Otomatis dari kategori...'),

                            Forms\Components\TextInput::make('nama_barang')
                                ->label('Nama Barang')
                                ->required(),

                            Forms\Components\Select::make('room_id')
                                ->relationship('room', 'nama_ruangan')
                                ->label('Lokasi Internal (Ruangan)')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Forms\Components\Select::make('kondisi')
                                ->options([
                                    'BAIK' => 'Baik',
                                    'RUSAK_RINGAN' => 'Rusak Ringan',
                                    'RUSAK_BERAT' => 'Rusak Berat',
                                ])->required(),

                            Forms\Components\Placeholder::make('info_masa_manfaat')
                                ->label('Masa Manfaat BMN')
                                ->content(fn($get) => $get('category_id')
                                    ? \App\Models\Category::find($get('category_id'))->masa_manfaat . ' Tahun'
                                    : '-'),
                        ]),
                    ]),

                Forms\Components\Section::make('Penggunaan Luar Kantor')
                    ->description('Aktifkan jika barang dibawa ke Rumah Dinas atau Pihak Ke-3')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Toggle::make('is_external')
                            ->label('Status: Digunakan di Luar Kantor?')
                            ->live(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nama_pemakai')
                                    ->label('Nama Pegawai/Pemakai')
                                    ->required(fn($get) => $get('is_external')),
                                Forms\Components\TextInput::make('nip_pemakai')
                                    ->label('NIP Pegawai'),
                                Forms\Components\Textarea::make('alamat_eksternal')
                                    ->label('Alamat Lengkap Lokasi Barang')
                                    ->placeholder('Contoh: Rumah Dinas Lapas Jombang Blok A No. 10')
                                    ->columnSpanFull()
                                    ->required(fn($get) => $get('is_external')),
                            ])->visible(fn($get) => $get('is_external')),
                    ]),

                Forms\Components\Section::make('Data Perolehan & QR')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\DatePicker::make('tanggal_perolehan')->required(),
                            Forms\Components\TextInput::make('harga_perolehan')
                                ->required()->numeric()->prefix('Rp'),
                        ]),
                        Forms\Components\Placeholder::make('qr_preview')
                            ->label('QR Code Asset')
                            ->content(function ($record) {
                                if (!$record) return 'Simpan dahulu untuk melihat QR';
                                $svg = QrCode::format('svg')->size(120)->generate($record->kode_barang . '-' . $record->nup);
                                return new HtmlString('<div style="background:white; padding:10px; display:inline-block; border:1px solid #ccc;">' . $svg . '</div>');
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Fisik')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.png')),

                Tables\Columns\TextColumn::make('nup')
                    ->label('NUP')
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_barang')
                    ->searchable()
                    ->description(fn(Asset $record): string => $record->is_external ? '📍 Luar: ' . $record->nama_pemakai : '🏠 Internal')
                    ->weight('bold'),

                // FITUR PENYUSUTAN (BARU)
                Tables\Columns\TextColumn::make('nilai_buku')
                    ->label('Nilai Buku')
                    ->money('IDR')
                    ->description(fn(Asset $record): string => 'Penyusutan: Rp ' . number_format(($record->harga_perolehan - $record->nilai_buku), 0, ',', '.'))
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('kode_barang')
                    ->label('Kode BMN')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('room.nama_ruangan')
                    ->label('Lokasi')
                    ->sortable(),

                Tables\Columns\TextColumn::make('kondisi')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'BAIK' => 'success',
                        'RUSAK_RINGAN' => 'warning',
                        'RUSAK_BERAT' => 'danger',
                    }),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'nama_kategori'),
                Tables\Filters\SelectFilter::make('is_external')
                    ->label('Posisi')
                    ->options(['0' => 'Di Kantor', '1' => 'Di Luar']),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                // Tombol Cetak Label QR
                Action::make('cetak_label')
                    ->label('Label')
                    ->icon('heroicon-o-qr-code')
                    ->color('info')
                    ->url(fn($record) => route('cetak_label', $record->id))
                    ->openUrlInNewTab(),

                // Tombol Cetak SPTJM
                Action::make('cetak_sptjm')
                    ->label('SPTJM')
                    ->icon('heroicon-o-document-check')
                    ->color('warning')
                    ->url(fn($record) => route('cetak_sptjm', $record->id))
                    ->openUrlInNewTab()
                    ->visible(fn($record) => $record->is_external),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('export_pdf')
                        ->label('Cetak Laporan Inventaris')
                        ->icon('heroicon-o-printer')
                        ->color('success')
                        ->action(fn(Collection $records) => static::exportPdf($records)),

                    Tables\Actions\DeleteBulkAction::make()->label('Arsipkan Data'),
                ]),
            ]);
    }

    public static function exportPdf(Collection $records)
    {
        $data = ['records' => $records, 'date' => now()->format('d F Y')];
        $pdf = Pdf::loadView('pdf.assets_report', $data);
        return response()->streamDownload(fn() => print($pdf->output()), "Laporan_BMN.pdf");
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LoansRelationManager::class,
            RelationManagers\MaintenancesRelationManager::class,
            RelationManagers\MutationsRelationManager::class,
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['room', 'category'])
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
