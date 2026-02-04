<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProcurementResource\Pages;
use App\Filament\Resources\ProcurementResource\RelationManagers;
use App\Models\Procurement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProcurementResource extends Resource
{
    protected static ?string $model = Procurement::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Pengadaan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pengajuan')
                    ->schema([
                        Forms\Components\TextInput::make('no_pengajuan')
                            ->label('No. Pengajuan')
                            ->default(fn () => 'PR/' . now()->format('Y/m') . '/' . str_pad(Procurement::whereMonth('created_at', now()->month)->count() + 1, 3, '0', STR_PAD_LEFT))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('tgl_pengajuan')
                            ->label('Tanggal Pengajuan')
                            ->default(now())
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'pending' => 'Pending Approval',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'ordered' => 'Ordered',
                                'received' => 'Received',
                            ])
                            ->default('draft')
                            ->required(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(3),
                    ])->columns(2),

                Forms\Components\Section::make('Daftar Barang')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('nama_barang')
                                    ->label('Nama Barang')
                                    ->required(),
                                Forms\Components\Select::make('category_id')
                                    ->label('Kategori')
                                    ->relationship('category', 'nama_kategori')
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\TextInput::make('jumlah')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->minValue(1),
                                Forms\Components\TextInput::make('harga_satuan')
                                    ->label('Harga Satuan (Est.)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),
                                Forms\Components\Textarea::make('spesifikasi')
                                    ->label('Spesifikasi')
                                    ->rows(2),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->reorderable(false)
                            ->collapsible(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_pengajuan')
                    ->label('No. Pengajuan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengaju')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tgl_pengajuan')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_estimasi')
                    ->label('Total Estimasi')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'secondary' => 'draft',
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                        'info' => 'ordered',
                        'primary' => 'received',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'ordered' => 'Ordered',
                        'received' => 'Received',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Procurement $record) => $record->status === 'pending')
                    ->action(function (Procurement $record) {
                        $record->update(['status' => 'approved']);
                    }),
                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Procurement $record) => $record->status === 'pending')
                    ->action(function (Procurement $record) {
                        $record->update(['status' => 'rejected']);
                    }),
                Tables\Actions\Action::make('receive')
                    ->label('Terima & Buat Aset')
                    ->icon('heroicon-o-archive-box-arrow-down')
                    ->color('primary')
                    ->form([
                        Forms\Components\Select::make('room_id')
                            ->label('Ruangan Tujuan')
                            ->relationship('room', 'nama_ruangan')
                            ->required()
                            ->searchable()
                            ->preload(),
                    ])
                    ->visible(fn (Procurement $record) => $record->status === 'approved' || $record->status === 'ordered')
                    ->action(function (Procurement $record, array $data) {
                        // Update status
                        $record->update(['status' => 'received']);

                        // Convert items to assets
                        foreach ($record->items as $item) {
                            for ($i = 0; $i < $item->jumlah; $i++) {
                                \App\Models\Asset::create([
                                    'nama_aset' => $item->nama_barang,
                                    'category_id' => $item->category_id,
                                    'room_id' => $data['room_id'],
                                    'tanggal_perolehan' => now(),
                                    'harga_perolehan' => $item->harga_satuan,
                                    'kondisi' => 'BAIK',
                                    'status' => 'AKTIF',
                                    'ket_lainnya' => 'Pengadaan dari ' . $record->no_pengajuan,
                                ]);
                            }
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Berhasil!')
                            ->body('Barang diterima dan ' . $record->items->sum('jumlah') . ' aset baru telah dibuat.')
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
            'index' => Pages\ListProcurements::route('/'),
            'create' => Pages\CreateProcurement::route('/create'),
            'edit' => Pages\EditProcurement::route('/{record}/edit'),
        ];
    }
}
