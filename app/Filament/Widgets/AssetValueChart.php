<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class AssetValueChart extends ChartWidget
{
    protected static bool $isLazy = true;
    protected static ?string $heading = 'Distribusi Nilai Aset per Kategori';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        // Optimized: Gunakan agregasi database langsung daripada koleksi PHP
        $data = \App\Models\Asset::query()
            ->leftJoin('categories', 'assets.category_id', '=', 'categories.id')
            ->selectRaw('COALESCE(categories.nama_kategori, "Tanpa Kategori") as label, SUM(assets.harga_perolehan) as total')
            ->groupBy('label')
            ->pluck('total', 'label');

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
