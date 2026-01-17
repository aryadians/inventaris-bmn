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
                Forms\Components\Section::make('Informasi Dasar')
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
                            Forms\Components\TextInput::make('kode_barang')->required(),
                            Forms\Components\TextInput::make('nama_barang')->required(),
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
                        ]),
                    ]),

                // --- FITUR LOKASI EKSTERNAL (BARU) ---
                Forms\Components\Section::make('Penggunaan Luar Kantor')
                    ->description('Gunakan bagian ini jika BMN dibawa ke Rumah Dinas atau digunakan Pihak Ketiga')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Toggle::make('is_external')
                            ->label('Status: Digunakan di Luar Kantor?')
                            ->live(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nama_pemakai')
                                    ->label('Nama Pegawai/Pihak Ke-3')
                                    ->required(fn($get) => $get('is_external')),
                                Forms\Components\TextInput::make('nip_pemakai')
                                    ->label('NIP Pegawai (Opsional)'),
                                Forms\Components\Textarea::make('alamat_eksternal')
                                    ->label('Alamat Detail Lokasi Barang')
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
                    ->description(fn(Asset $record): string => $record->is_external ? '📍 Luar Kantor' : '🏠 Internal')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('room.nama_ruangan')
                    ->label('Lokasi Terakhir')
                    ->sortable(),

                Tables\Columns\TextColumn::make('kondisi')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'BAIK' => 'success',
                        'RUSAK_RINGAN' => 'warning',
                        'RUSAK_BERAT' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('harga_perolehan')
                    ->money('IDR')
                    ->label('Harga'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('is_external')
                    ->label('Posisi Barang')
                    ->options([
                        '0' => 'Di Dalam Kantor',
                        '1' => 'Di Luar Kantor',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                // ACTION CETAK LABEL QR SATUAN
                Tables\Actions\Action::make('cetak_label')
                    ->label('Label')
                    ->icon('heroicon-o-qr-code')
                    ->color('info')
                    ->url(fn($record) => route('cetak_label', $record->id))
                    ->openUrlInNewTab(),

                // ACTION CETAK SPTJM (Hanya muncul jika di luar)
                Tables\Actions\Action::make('cetak_sptjm')
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
                        ->label('Cetak Laporan PDF')
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
            ->with(['room'])
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
