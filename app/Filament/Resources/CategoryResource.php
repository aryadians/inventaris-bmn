<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    // Memberi label yang lebih informatif di navigasi
    protected static ?string $navigationLabel = 'Referensi Kode Barang';
    protected static ?string $pluralModelLabel = 'Referensi Kode Barang';
    protected static ?string $modelLabel = 'Kategori Barang';

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view categories');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create categories');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('edit categories');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete categories');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()->can('delete categories');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Klasifikasi BMN')
                    ->description('Pastikan kode barang sesuai dengan Standar Akuntansi Pemerintah (SAP)')
                    ->schema([
                        Forms\Components\TextInput::make('kode_kategori')
                            ->label('Kode Barang (Akun BMN)')
                            ->placeholder('Contoh: 3.05.02.01.003')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('nama_kategori')
                            ->label('Nama Kategori / Jenis Barang')
                            ->placeholder('Contoh: Laptop / PC Tablet')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('masa_manfaat')
                            ->label('Masa Manfaat (Tahun)')
                            ->placeholder('Contoh: 5')
                            ->numeric()
                            ->required()
                            ->suffix('Tahun')
                            ->helperText('Durasi penyusutan barang sesuai aturan Kemenkeu.'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_kategori')
                    ->label('Kode BMN')
                    ->searchable()
                    ->sortable()
                    ->copyable() // Memudahkan admin copy-paste kode
                    ->fontFamily('mono'),

                Tables\Columns\TextColumn::make('nama_kategori')
                    ->label('Jenis Barang')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('masa_manfaat')
                    ->label('Masa Manfaat')
                    ->suffix(' Tahun')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('assets_count')
                    ->label('Total Unit')
                    ->counts('assets') // Menampilkan jumlah barang di tiap kategori
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            // Bisa ditambahkan AssetRelationManager jika ingin melihat daftar barang per kategori
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
