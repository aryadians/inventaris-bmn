<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanResource\Pages;
use App\Models\Loan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LoanResource extends Resource
{
    protected static ?string $model = Loan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    // Ganti Label Menu jadi Bahasa Indonesia
    protected static ?string $navigationLabel = 'Peminjaman Barang';
    protected static ?string $pluralModelLabel = 'Data Peminjaman';
    protected static ?string $navigationGroup = 'Transaksi'; // Biar ada grup menu baru

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view loans');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create loans');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('edit loans');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete loans');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()->can('delete loans');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Peminjaman')
                    ->description('Isi data peminjaman aset BMN')
                    ->schema([
                        // 1. Pilih Peminjam (User)
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Nama Peminjam')
                            ->searchable()
                            ->preload()
                            ->required(),

                        // 2. Pilih Barang (LOGIC CANGGIH DI SINI)
                        Forms\Components\Select::make('asset_id')
                            ->label('Pilih Barang')
                            ->relationship('asset', 'nama_barang', function (Builder $query) {
                                // Filter: HANYA tampilkan barang BAIK
                                // DAN barang yang TIDAK sedang dipinjam (status DIPINJAM di tabel loans)
                                return $query->where('kondisi', 'BAIK')
                                    ->whereDoesntHave('loans', function ($q) {
                                        $q->where('status', 'DIPINJAM');
                                    });
                            })
                            // Tampilkan Nama + Kode Barang di dropdown
                            ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->nama_barang} - {$record->kode_barang}")
                            ->searchable()
                            ->preload()
                            ->required(),

                        // 3. Tanggal Pinjam
                        Forms\Components\DatePicker::make('tanggal_pinjam')
                            ->default(now())
                            ->required(),

                        // 4. Rencana Kembali
                        Forms\Components\DatePicker::make('tanggal_kembali_rencana')
                            ->label('Rencana Kembali')
                            ->required(),

                        // 5. Status (Otomatis DIPINJAM saat baru buat)
                        Forms\Components\Select::make('status')
                            ->options([
                                'DIPINJAM' => 'Sedang Dipinjam',
                                'DIKEMBALIKAN' => 'Sudah Dikembalikan',
                            ])
                            ->default('DIPINJAM')
                            ->required(),

                        // 6. Keterangan
                        Forms\Components\Textarea::make('keterangan')
                            ->columnSpanFull(),
                    ])->columns(2),

                // Section Khusus Pengembalian (Hanya muncul kalau lagi Edit Data)
                Forms\Components\Section::make('Update Pengembalian')
                    ->schema([
                        Forms\Components\DatePicker::make('tanggal_kembali_realisasi')
                            ->label('Tanggal Dikembalikan (Realisasi)'),
                    ])
                    ->hidden(fn(string $operation) => $operation === 'create'), // Sembunyikan saat Create
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Nama Peminjam
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Peminjam')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

                // Nama Barang & Kode
                Tables\Columns\TextColumn::make('asset.nama_barang')
                    ->label('Barang')
                    ->description(fn(Loan $record) => $record->asset->kode_barang ?? '-')
                    ->searchable(),

                // Tanggal Pinjam
                Tables\Columns\TextColumn::make('tanggal_pinjam')
                    ->date('d M Y')
                    ->sortable(),

                // Tanggal Rencana Kembali (Warna Merah kalau Telat!)
                Tables\Columns\TextColumn::make('tanggal_kembali_rencana')
                    ->label('Tenggat')
                    ->date('d M Y')
                    ->sortable()
                    ->color(
                        fn($record) => ($record->status === 'DIPINJAM' && $record->tanggal_kembali_rencana < now())
                            ? 'danger'
                            : null
                    ),

                // Status Badge
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'DIPINJAM' => 'warning',      // Kuning
                        'DIKEMBALIKAN' => 'success',  // Hijau
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                // Filter Status (Dipinjam / Kembali)
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'DIPINJAM' => 'Sedang Dipinjam',
                        'DIKEMBALIKAN' => 'Sudah Dikembalikan',
                    ]),
            ])
            ->actions([
                // 1. Tombol Cetak Surat Jalan (Yang tadi sudah dibuat)
                Tables\Actions\Action::make('cetak_surat')
                    ->label('Surat Jalan')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn(Loan $record) => route('cetak_bukti', $record->id))
                    ->openUrlInNewTab(),

                // 2. TOMBOL BARU: Kembalikan Barang (Quick Return)
                Tables\Actions\Action::make('kembalikan')
                    ->label('Kembalikan')
                    ->icon('heroicon-o-check-circle') // Ikon Centang
                    ->color('success') // Warna Hijau
                    ->requiresConfirmation() // Muncul pop-up konfirmasi "Yakin?"
                    ->modalHeading('Konfirmasi Pengembalian')
                    ->modalDescription('Apakah barang ini benar-benar sudah kembali dalam kondisi baik?')
                    ->modalSubmitActionLabel('Ya, Sudah Kembali')

                    // Hanya muncul jika status masih DIPINJAM dan user punya izin
                    ->visible(fn(Loan $record) => $record->status === 'DIPINJAM' && auth()->user()->can('approve loans'))

                    // Logic Update Database saat diklik
                    ->action(function (Loan $record) {
                        $record->update([
                            'status' => 'DIKEMBALIKAN',
                            'tanggal_kembali_realisasi' => now(), // Isi tanggal hari ini otomatis
                        ]);

                        // Kirim notifikasi sukses di pojok kanan
                        \Filament\Notifications\Notification::make()
                            ->title('Berhasil')
                            ->body('Barang telah ditandai kembali.')
                            ->success()
                            ->send();
                    }),

                // Tombol Edit & Delete bawaan
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(), // Opsional: Bisa disembunyikan biar data gak hilang
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
            'index' => Pages\ListLoans::route('/'),
            'create' => Pages\CreateLoan::route('/create'),
            'edit' => Pages\EditLoan::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->hasRole('Peminjam')) {
            return parent::getEloquentQuery()->where('user_id', auth()->id());
        }
        return parent::getEloquentQuery();
    }
}
