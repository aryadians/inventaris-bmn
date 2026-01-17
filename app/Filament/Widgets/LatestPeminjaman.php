<?php

namespace App\Filament\Widgets;

use App\Models\Loan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestPeminjaman extends BaseWidget
{
    // Judul Widget
    protected static ?string $heading = 'Monitoring Barang Dipinjam (Belum Kembali)';

    // Agar widget ini lebar (Full Width) di dashboard
    protected int | string | array $columnSpan = 'full';

    // Urutan widget (taruh paling bawah, setelah chart)
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Ambil data Loan yang statusnya masih 'DIPINJAM'
                Loan::query()->where('status', 'DIPINJAM')
            )
            ->defaultSort('tanggal_pinjam', 'desc') // Yang baru pinjam di atas
            ->columns([
                // Kolom Peminjam
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Peminjam')
                    ->weight('bold'),

                // Kolom Barang
                Tables\Columns\TextColumn::make('asset.nama_barang')
                    ->label('Barang')
                    ->description(fn(Loan $record) => $record->asset->kode_barang),

                // Tanggal Pinjam
                Tables\Columns\TextColumn::make('tanggal_pinjam')
                    ->date('d M Y')
                    ->label('Tgl Pinjam'),

                // Tanggal Rencana Kembali (Ada Warning Merah jika telat)
                Tables\Columns\TextColumn::make('tanggal_kembali_rencana')
                    ->label('Tenggat Waktu')
                    ->date('d M Y')
                    ->color(
                        fn($record) => ($record->tanggal_kembali_rencana < now()) ? 'danger' : 'success'
                    )
                    ->icon(
                        fn($record) => ($record->tanggal_kembali_rencana < now()) ? 'heroicon-o-exclamation-circle' : 'heroicon-o-clock'
                    ),

                // Tombol Aksi Cepat (Langsung cetak surat dari dashboard)
                Tables\Columns\TextColumn::make('id')
                    ->label('Aksi')
                    ->formatStateUsing(fn() => 'Cetak Surat')
                    ->color('info')
                    ->url(fn(Loan $record) => route('cetak_bukti', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->paginated(false); // Matikan halaman (tampilkan 5-10 data saja)
    }
}
