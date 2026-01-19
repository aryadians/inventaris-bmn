<?php

namespace App\Filament\Widgets;

use App\Models\Loan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestPeminjaman extends BaseWidget
{
    protected static ?string $heading = 'Monitoring Barang Dipinjam (Belum Kembali)';

    protected int | string | array $columnSpan = 'full';

    // Urutan paling akhir (5)
    protected static ?int $sort = 4;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Loan::query()->where('status', 'DIPINJAM')->with(['user', 'asset'])
            )
            ->defaultSort('tanggal_pinjam', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Peminjam')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('asset.nama_barang')
                    ->label('Barang')
                    ->description(fn(Loan $record) => $record->asset?->kode_barang ?? '-'),

                Tables\Columns\TextColumn::make('tanggal_pinjam')
                    ->date('d M Y')
                    ->label('Tgl Pinjam'),

                Tables\Columns\TextColumn::make('tanggal_kembali_rencana')
                    ->label('Tenggat Waktu')
                    ->date('d M Y')
                    ->color(fn($record) => ($record->tanggal_kembali_rencana < now()) ? 'danger' : 'success')
                    ->icon(fn($record) => ($record->tanggal_kembali_rencana < now()) ? 'heroicon-o-exclamation-circle' : 'heroicon-o-clock'),

                Tables\Columns\TextColumn::make('id')
                    ->label('Aksi')
                    ->formatStateUsing(fn() => 'Cetak Surat')
                    ->color('info')
                    ->url(fn(Loan $record) => route('cetak_bukti', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->paginated(false);
    }
}
