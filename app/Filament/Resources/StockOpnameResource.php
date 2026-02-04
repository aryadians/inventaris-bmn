<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockOpnameResource\Pages;
use App\Filament\Resources\StockOpnameResource\RelationManagers;
use App\Models\StockOpname;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StockOpnameResource extends Resource
{
    protected static ?string $model = StockOpname::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggal')
                    ->required()
                    ->default(now()),
                Forms\Components\Select::make('room_id')
                    ->relationship('room', 'name')
                    ->label('Ruangan Target')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('assigned_user_id')
                    ->relationship('assignedUser', 'name')
                    ->label('Petugas Pemeriksa')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'on_progress' => 'Sedang Berjalan',
                        'completed' => 'Selesai',
                    ])
                    ->default('draft')
                    ->required(),
                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('room.name')
                    ->label('Ruangan')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('assignedUser.name')
                    ->label('Petugas'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'on_progress' => 'warning',
                        'completed' => 'success',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaults([
                Tables\Columns\TextColumn::make('created_at')->sortable('desc'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('scan')
                    ->label('Mulai audit')
                    ->icon('heroicon-o-qr-code')
                    ->color('success')
                    ->url(fn (StockOpname $record): string => Pages\ScanStockOpname::getUrl(['record' => $record])),
                Tables\Actions\Action::make('download_report')
                    ->label('Laporan')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('info')
                    ->action(function (StockOpname $record) {
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.stock_opname_report', ['record' => $record])
                            ->setPaper('a4', 'portrait');
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'Laporan-Audit-' . $record->id . '.pdf'
                        );
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
            'index' => Pages\ListStockOpnames::route('/'),
            'create' => Pages\CreateStockOpname::route('/create'),
            'edit' => Pages\EditStockOpname::route('/{record}/edit'),
            'scan' => Pages\ScanStockOpname::route('/{record}/scan'),
        ];
    }
}
