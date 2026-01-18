<?php

namespace App\Filament\Resources\AssetResource\Pages;

use App\Filament\Resources\AssetResource;
use App\Exports\AssetsExport;
use App\Imports\AssetsImport;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ListAssets extends ListRecords
{
    protected static string $resource = AssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function () {
                    $query = $this->getFilteredTableQuery();
                    $data = $query->get();
                    return Excel::download(new AssetsExport($data), 'assets.xlsx');
                })
                ->color('primary'),

            // 1. Tombol Download Template Excel
            Actions\Action::make('downloadTemplate')
                ->label('Download Template')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(url('template.xlsx')) // Pastikan file template.xlsx ada di folder public/
                ->color('gray'),

            // 2. Tombol Import Excel
            Actions\Action::make('importExcel')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    FileUpload::make('attachment')
                        ->label('Upload File Excel (.xlsx)')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel'
                        ])
                        ->disk('local')
                        ->directory('temp-import')
                        ->preserveFilenames()
                        ->required(),
                ])
                ->action(function (array $data) {
                    try {
                        // Ambil path file
                        $filePath = Storage::disk('local')->path($data['attachment']);

                        // Eksekusi Import menggunakan Library Excel
                        Excel::import(new AssetsImport, $filePath);

                        // Hapus file setelah import agar storage tidak penuh
                        Storage::disk('local')->delete($data['attachment']);

                        Notification::make()
                            ->title('Import Berhasil')
                            ->body('Data aset berhasil diimpor ke database.')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Import Gagal')
                            ->body('Terjadi kesalahan: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            // 3. Tombol Laporan Penyusutan (Akuntansi BMN)
            Actions\Action::make('cetak_penyusutan')
                ->label('Laporan Penyusutan')
                ->icon('heroicon-o-document-chart-bar')
                ->color('warning')
                ->url(route('cetak_penyusutan'))
                ->openUrlInNewTab(),

            // 4. Tombol Tambah Data Manual
            Actions\CreateAction::make()
                ->label('Tambah Aset'),
        ];
    }
}
