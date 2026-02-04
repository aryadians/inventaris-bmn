<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class AssetValueChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Nilai Aset per Kategori';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        // Grup aset berdasarkan kategori dan sum harga
        $data = \App\Models\Asset::with('category')->get()
            ->groupBy(fn ($asset) => $asset->category->nama_kategori ?? 'Tanpa Kategori')
            ->map(fn ($assets) => $assets->sum('harga_perolehan'));

        return [
            'datasets' => [
                [
                    'label' => 'Total Nilai Aset (Rp)',
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $data->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
