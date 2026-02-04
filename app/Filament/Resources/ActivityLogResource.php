<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use Spatie\Activitylog\Models\Activity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    
    protected static ?string $navigationLabel = 'Log Aktivitas';

    protected static ?string $pluralLabel = 'Log Aktivitas';

    protected static ?string $slug = 'activity-logs';

    protected static ?int $navigationSort = 99;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Detail Aktivitas')
                            ->schema([
                                Forms\Components\TextInput::make('causer_id')
                                    ->label('User ID')
                                    ->disabled(),
                                Forms\Components\TextInput::make('causer_type')
                                    ->label('User Type')
                                    ->disabled(),
                                Forms\Components\TextInput::make('subject_type')
                                    ->label('Model')
                                    ->disabled(),
                                Forms\Components\TextInput::make('event')
                                    ->label('Event')
                                    ->disabled(),
                                Forms\Components\DateTimePicker::make('created_at')
                                    ->label('Waktu')
                                    ->disabled(),
                            ])->columns(2),
                    ]),
                
                Forms\Components\Section::make('Perubahan Data')
                    ->schema([
                        Forms\Components\KeyValue::make('properties.attributes')
                            ->label('Data Baru')
                            ->keyLabel('Kolom')
                            ->valueLabel('Nilai'),
                            
                        Forms\Components\KeyValue::make('properties.old')
                            ->label('Data Lama')
                            ->keyLabel('Kolom')
                            ->valueLabel('Nilai'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i:s')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('description')
                    ->label('Log')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Modul')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->badge()
                    ->color('info'),
                    
                Tables\Columns\TextColumn::make('event')
                    ->label('Aksi')
                    ->badge()
                    ->colors([
                        'success' => 'created',
                        'warning' => 'updated',
                        'danger' => 'deleted',
                    ]),
                    
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Oleh')
                    ->description(fn ($record) => $record->causer?->email ?? '-')
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->options([
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageActivityLogs::route('/'),
        ];
    }
}
