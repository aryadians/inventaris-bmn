<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetDisposalResource\Pages;
use App\Models\AssetDisposal;
use App\Models\Asset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AssetDisposalResource extends Resource
{
    protected static ?string $model = AssetDisposal::class;
    protected static ?string $navigationIcon = 'heroicon-o-trash';
    protected static ?string $navigationGroup = 'Administrasi';
    protected static ?string $navigationLabel = 'Penghapusan Aset';
    protected static ?string $pluralModelLabel = 'Usulan Penghapusan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Penghapusan')
                    ->schema([
                        Forms\Components\Select::make('asset_id')
                            ->label('Aset yang Dihapus')
                            ->relationship('asset', 'nama_barang') // Pastikan relasi 'asset' ada di model AssetDisposal
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $asset = Asset::find($state);
                                    if ($asset) {
                                         // Mengakses accessor nilai_buku dari model Asset
                                         // Pastikan getNilaiBukuAttribute ada di model Asset
                                        $set('nilai_buku_saat_ini', $asset->nilai_buku ?? 0);
                                    }
                                }
                            })
                            // Hanya tampilkan aset yang belum dihapus (status != DIHAPUS)
                             ->options(function () {
                                return Asset::where('status', '!=', 'DIHAPUS')->pluck('nama_barang', 'id');
                            }),

                        Forms\Components\DatePicker::make('tanggal_penghapusan')
                            ->required()
                            ->default(now()),

                        Forms\Components\Select::make('jenis_penghapusan')
                            ->options([
                                'Pemusnahan' => 'Pemusnahan (Rusak Berat)',
                                'Lelang' => 'Lelang (Penjualan)',
                                'Hibah' => 'Hibah (Transfer Keluar)',
                            ])
                            ->default('Pemusnahan')
                            ->required()
                            ->reactive(),

                        Forms\Components\TextInput::make('no_sk')
                            ->label('Nomor SK Penghapusan')
                            ->required(),

                        Forms\Components\TextInput::make('nilai_buku_saat_ini')
                            ->label('Nilai Buku Terakhir')
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('harga_limit')
                                    ->label('Harga Limit (Lelang)')
                                    ->numeric()
                                    ->prefix('Rp'),
                                Forms\Components\TextInput::make('harga_terbentuk')
                                    ->label('Harga Terjual (Lelang)')
                                    ->numeric()
                                    ->prefix('Rp'),
                            ])
                            ->visible(fn (Forms\Get $get) => $get('jenis_penghapusan') === 'Lelang'),

                        Forms\Components\Textarea::make('keterangan')
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('file_sk')
                            ->label('Upload SK Penghapusan (PDF)')
                            ->acceptedFileTypes(['application/pdf'])
                            ->directory('sk-penghapusan')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('asset.nama_barang')
                    ->label('Nama Aset')
                    ->searchable()
                    ->sortable()
                    ->description(fn (AssetDisposal $record) => $record->asset->kode_barang ?? '-'),

                Tables\Columns\TextColumn::make('jenis_penghapusan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pemusnahan' => 'danger',
                        'Lelang' => 'success',
                        'Hibah' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('tanggal_penghapusan')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('no_sk')
                    ->label('No SK')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'approved' => 'warning',
                        'completed' => 'success',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_penghapusan')
                    ->options([
                        'Pemusnahan' => 'Pemusnahan',
                        'Lelang' => 'Lelang',
                        'Hibah' => 'Hibah',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                
                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn (AssetDisposal $record) => $record->status === 'draft')
                    ->action(function (AssetDisposal $record) {
                        $record->update([
                            'status' => 'approved',
                            'approved_by' => auth()->id()
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Disetujui')
                            ->body('Usulan penghapusan telah disetujui, menunggu eksekusi final.')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('complete')
                    ->label('Eksekusi (Hapus)')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Penghapusan Final')
                    ->modalDescription('Tindakan ini akan mengubah status aset menjadi DIHAPUS secara permanen di sistem. Lanjutkan?')
                    ->visible(fn (AssetDisposal $record) => $record->status === 'approved')
                    ->action(function (AssetDisposal $record) {
                        $record->update(['status' => 'completed']);
                        
                        // Logic update asset status ada di Model (booted method)
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Selesai')
                            ->body('Aset berhasil dihapus dari daftar aktif.')
                            ->success()
                            ->send();
                    }),
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
            'index' => Pages\ListAssetDisposals::route('/'),
            'create' => Pages\CreateAssetDisposal::route('/create'),
            'edit' => Pages\EditAssetDisposal::route('/{record}/edit'),
        ];
    }
}
