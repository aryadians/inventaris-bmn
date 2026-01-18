<?php

namespace App\Filament\Resources;

use App\Exports\AssetsExport;
use App\Filament\Resources\AssetResource\Pages;
use App\Filament\Resources\AssetResource\RelationManagers;
use App\Models\Asset;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
                Forms\Components\Tabs::make('Asset Details')
                    ->tabs([
                        // TAB 1: INFORMASI UTAMA
                        Forms\Components\Tabs\Tab::make('General Info')
                            ->icon('heroicon-m-information-circle')
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
                                        })->required(),

                                    Forms\Components\TextInput::make('kode_barang')
                                        ->label('Kode Akun BMN')
                                        ->disabled()->dehydrated()
                                        ->placeholder('Otomatis dari kategori...'),

                                    Forms\Components\TextInput::make('nama_barang')
                                        ->label('Nama Barang')
                                        ->required(),

                                    Forms\Components\Select::make('kondisi')
                                        ->options([
                                            'BAIK' => 'Baik',
                                            'RUSAK_RINGAN' => 'Rusak Ringan',
                                            'RUSAK_BERAT' => 'Rusak Berat',
                                        ])->required(),
                                ]),
                            ]),

                        // TAB 2: LOKASI & PEMAKAI
                        Forms\Components\Tabs\Tab::make('Location & External')
                            ->icon('heroicon-m-map-pin')
                            ->schema([
                                Forms\Components\Select::make('room_id')
                                    ->relationship('room', 'nama_ruangan')
                                    ->label('Lokasi Internal (Ruangan)')
                                    ->searchable()->preload()->required(),

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
                                            ->columnSpanFull()
                                            ->required(fn($get) => $get('is_external')),
                                    ])->visible(fn($get) => $get('is_external')),
                            ]),

                        // TAB 3: FINANSIAL & QR
                        Forms\Components\Tabs\Tab::make('Financial & QR')
                            ->icon('heroicon-m-banknotes')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\DatePicker::make('tanggal_perolehan')->required(),
                                    Forms\Components\TextInput::make('harga_perolehan')
                                        ->required()->numeric()->prefix('Rp'),
                                    Forms\Components\Placeholder::make('info_masa_manfaat')
                                        ->label('Masa Manfaat BMN')
                                        ->content(fn($get) => $get('category_id')
                                            ? \App\Models\Category::find($get('category_id'))->masa_manfaat . ' Tahun'
                                            : '-'),
                                ]),
                                Forms\Components\Placeholder::make('qr_preview')
                                    ->label('QR Code Asset')
                                    ->content(function ($record) {
                                        if (!$record) return 'Simpan dahulu untuk melihat QR';
                                        $svg = QrCode::format('svg')->size(120)->generate($record->kode_barang . '-' . $record->nup);
                                        return new HtmlString('<div style="background:white; padding:10px; display:inline-block; border:1px solid #ccc; border-radius:8px;">' . $svg . '</div>');
                                    }),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('')
                    ->circular()
                    ->grow(false)
                    ->defaultImageUrl(url('/images/placeholder.png')),

                // LAYOUT STACK UNTUK NAMA & KATEGORI
                Tables\Columns\TextColumn::make('nama_barang')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn(Asset $record): string => $record->category?->nama_kategori ?? 'Uncategorized'),

                Tables\Columns\TextColumn::make('nup')
                    ->label('NUP')
                    ->formatStateUsing(fn($state) => "#{$state}")
                    ->badge()
                    ->color('gray'),

                // FINANSIAL DENGAN WARNA DINAMIS
                Tables\Columns\TextColumn::make('nilai_buku')
                    ->label('Financial Status')
                    ->money('IDR')
                    ->sortable()
                    ->description(fn(Asset $record): string => 'Depreciation: Rp ' . number_format(($record->harga_perolehan - $record->nilai_buku), 0, ',', '.'))
                    ->color(fn($state) => $state > 0 ? 'success' : 'danger'),

                // LOKASI DENGAN ICON
                Tables\Columns\TextColumn::make('room.nama_ruangan')
                    ->label('Current Location')
                    ->icon('heroicon-m-map-pin')
                    ->description(fn(Asset $record): string => $record->is_external ? "📍 {$record->nama_pemakai}" : '🏠 Internal')
                    ->sortable(),

                // KONDISI DENGAN BADGE INTERAKTIF
                Tables\Columns\SelectColumn::make('kondisi')
                    ->label('Condition')
                    ->options([
                        'BAIK' => 'Baik',
                        'RUSAK_RINGAN' => 'Rusak Ringan',
                        'RUSAK_BERAT' => 'Rusak Berat',
                    ])->selectablePlaceholder(false),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'nama_kategori'),
                Tables\Filters\SelectFilter::make('room_id')
                    ->label('Ruangan')
                    ->relationship('room', 'nama_ruangan'),
                Tables\Filters\SelectFilter::make('kondisi')
                    ->label('Kondisi')
                    ->options([
                        'BAIK' => 'Baik',
                        'RUSAK_RINGAN' => 'Rusak Ringan',
                        'RUSAK_BERAT' => 'Rusak Berat',
                    ]),
                Tables\Filters\SelectFilter::make('is_external')
                    ->label('Position')
                    ->options(['0' => 'Internal', '1' => 'External']),
            ])
            ->headerActions([
                Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function ($livewire) {
                        $records = $livewire->getFilteredTableQuery()->get();
                        if ($records->isEmpty()) {
                            Notification::make()
                                ->title('Tidak ada data untuk diekspor')
                                ->warning()
                                ->send();
                            return;
                        }
                        return Excel::download(new AssetsExport($records), 'Laporan_Aset_BMN.xlsx');
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([ // Action dikelompokkan agar rapi
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Action::make('cetak_label')
                        ->label('Cetak Label QR')
                        ->icon('heroicon-o-qr-code')
                        ->color('info')
                        ->url(fn($record) => route('cetak_label', $record->id))
                        ->openUrlInNewTab(),
                    Action::make('cetak_sptjm')
                        ->label('Cetak SPTJM')
                        ->icon('heroicon-o-document-check')
                        ->color('warning')
                        ->url(fn($record) => route('cetak_sptjm', $record->id))
                        ->openUrlInNewTab()
                        ->visible(fn($record) => $record->is_external),
                    Tables\Actions\DeleteAction::make(),
                ])->icon('heroicon-m-ellipsis-vertical'),
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
            ])
            ->emptyStateHeading('Tidak ada aset ditemukan')
            ->emptyStateIcon('heroicon-o-cube-transparent');
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
