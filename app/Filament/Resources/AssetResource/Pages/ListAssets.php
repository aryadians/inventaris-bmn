<?php

namespace App\Filament\Resources\AssetResource\Pages;

use App\Filament\Resources\AssetResource;
use App\Imports\AssetsImport; // Panggil Class Import tadi
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel; // Panggil Library Excel
use Illuminate\Contracts\View\View;

class ListAssets extends ListRecords
{
    protected static string $resource = AssetResource::class;

    protected function getHeaderActions(): array
    {
        return [

            // 1. Tombol Download Template (BARU)
            Actions\Action::make('downloadTemplate')
                ->label('Download Template')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(url('template.xlsx')) // Mengarah ke file di folder public
                ->color('gray'),
            // Tombol Import Excel
            Actions\Action::make('importExcel')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    FileUpload::make('attachment')
                        ->label('Upload File Excel (.xlsx)')
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                        ->disk('local') // Simpan sementara di local
                        ->directory('temp-import')
                        ->required(),
                ])
                ->action(function (array $data) {
                    // Eksekusi Import
                    // Ambil path file yang baru diupload
                    $filePath = storage_path('app/' . $data['attachment']);
                    
                    Excel::import(new AssetsImport, $filePath);

                    // Notifikasi Sukses
                    \Filament\Notifications\Notification::make()
                        ->title('Import Berhasil')
                        ->body('Data aset berhasil ditambahkan ke database.')
                        ->success()
                        ->send();
                }),

            Actions\CreateAction::make(),
        ];
    }
}