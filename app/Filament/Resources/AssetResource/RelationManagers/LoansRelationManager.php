<?php

namespace App\Filament\Resources\AssetResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class LoansRelationManager extends RelationManager
{
    protected static string $relationship = 'loans';

    // Judul Tab
    protected static ?string $title = 'Riwayat Peminjaman';

    public function form(Form $form): Form
    {
        // Kita biarkan form kosong saja, karena biasanya
        // kita tidak menambah peminjaman dari sini (tapi dari menu Peminjaman)
        return $form
            ->schema([
                Forms\Components\TextInput::make('keterangan')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('keterangan')
            ->columns([
                // Siapa yang pinjam?
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Peminjam')
                    ->weight('bold'),

                // Tanggal Pinjam
                Tables\Columns\TextColumn::make('tanggal_pinjam')
                    ->date('d M Y')
                    ->label('Tgl Pinjam'),

                // Tanggal Kembali (Realisasi)
                Tables\Columns\TextColumn::make('tanggal_kembali_realisasi')
                    ->date('d M Y')
                    ->label('Tgl Kembali')
                    ->placeholder('Belum Kembali'), // Teks jika masih dipinjam

                // Status
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'DIPINJAM' => 'warning',
                        'DIKEMBALIKAN' => 'success',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(), // Matikan tombol create biar alurnya tertib
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
