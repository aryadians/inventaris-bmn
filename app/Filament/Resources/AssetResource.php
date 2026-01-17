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
// --- IMPORT UNTUK PDF ---
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;

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
                Forms\Components\FileUpload::make('foto')
                    ->label('Foto Barang Fisik')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('aset-images')
                    ->visibility('public')
                    ->columnSpanFull(),
            // Di dalam public static function form(Form $form)
            Forms\Components\Placeholder::make('qr_preview')
                ->label('QR Code Asset')
                ->content(function ($record) {
                    if (!$record) return 'Simpan dahulu untuk melihat QR';
                    $svg = QrCode::format('svg')->size(120)->generate($record->kode_barang . '-' . $record->nup);
                    return new HtmlString('<div style="background:white; padding:10px; display:inline-block;">' . $svg . '</div>');
                }),

                Forms\Components\Select::make('room_id')
                    ->relationship('room', 'nama_ruangan')
                    ->label('Lokasi Ruangan')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('kode_barang')
                    ->required(),

                Forms\Components\TextInput::make('nama_barang')
                    ->required(),

                Forms\Components\Hidden::make('nup'),

                Forms\Components\Select::make('kondisi')
                    ->options([
                        'BAIK' => 'Baik',
                        'RUSAK_RINGAN' => 'Rusak Ringan',
                        'RUSAK_BERAT' => 'Rusak Berat',
                    ])
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_perolehan')
                    ->required(),

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
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Fisik')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.png')),

                Tables\Columns\TextColumn::make('nup')
                    ->label('NUP')
                    ->sortable(),

//                 Tables\Columns\TextColumn::make('qr_code')
//                     // Di dalam Tables\Columns\TextColumn::make('qr_code')
// ->getStateUsing(function ($record) {
//     if (!$record || !$record->kode_barang || !$record->nup) return '-';
    
//     // Gunakan try-catch agar jika error tidak membuat halaman stuck
//     try {
//         $svg = QrCode::format('svg')->size(50)->generate($record->kode_barang . '-' . $record->nup);
//         return new HtmlString('<img src="data:image/svg+xml;base64,' . base64_encode($svg) . '" width="50" height="50" />');
//     } catch (\Exception $e) {
//         return 'Error QR';
//     }
// }),

                Tables\Columns\TextColumn::make('kode_barang')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('nama_barang')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('room.nama_ruangan')
                    ->label('Lokasi')
                    ->sortable()
                    ->searchable(),

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
            // TOMBOL CETAK LABEL SATUAN
            Tables\Actions\Action::make('cetak_label')
                ->label('Label')
                ->icon('heroicon-o-qr-code')
                ->color('info')
                ->url(fn($record) => route('cetak_label', $record->id))
                ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // --- FITUR CETAK PDF BARU ---
                    Tables\Actions\BulkAction::make('export_pdf')
                        ->label('Cetak Laporan PDF')
                        ->icon('heroicon-o-printer')
                        ->color('success')
                        ->action(fn(Collection $records) => static::exportPdf($records)),

                    Tables\Actions\BulkAction::make('cetak_usulan')
                        ->label('Cetak Usulan Penghapusan')
                        ->icon('heroicon-o-document-text')
                        ->color('warning')
                        ->action(function ($records) {
                            $ids = $records->pluck('id')->implode(',');
                            return redirect()->route('cetak_usulan', ['ids' => $ids]);
                        }),

                    Tables\Actions\DeleteBulkAction::make()->label('Arsipkan Data'),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    // --- LOGIKA GENERATE PDF ---
    public static function exportPdf(Collection $records)
    {
        $data = [
            'records' => $records,
            'date' => now()->format('d F Y'),
        ];

        $pdf = Pdf::loadView('pdf.assets_report', $data);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            "Laporan_BMN_" . now()->format('Y-m-d') . ".pdf"
        );
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
            ->select(['id', 'nama_barang', 'kode_barang', 'nup', 'room_id', 'kondisi', 'harga_perolehan']) // Ambil kolom yang perlu saja
            ->with(['room:id,nama_ruangan']) // Ambil relasi ruangan hanya ID dan Nama
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
