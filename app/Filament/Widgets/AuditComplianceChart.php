<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class AuditComplianceChart extends ChartWidget
{
    protected static ?string $heading = 'Kepatuhan Audit Terakhir';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $lastOpname = \App\Models\StockOpname::latest('created_at')->with('details')->first();

        if (!$lastOpname) {
             return [
                'datasets' => [
                    [
                        'data' => [0, 0, 0],
                        'backgroundColor' => ['#4ade80', '#f87171', '#fbbf24'],
                    ],
                ],
                'labels' => ['Sesuai', 'Hilang', 'Salah Ruangan'],
            ];
        }

        $found = $lastOpname->details->where('status', 'found')->count();
        $missing = $lastOpname->details->where('status', 'missing')->count();
        $wrongRoom = $lastOpname->details->where('status', 'wrong_room')->count();

        return [
            'datasets' => [
                [
                    'data' => [$found, $missing, $wrongRoom],
                    'backgroundColor' => ['#4ade80', '#f87171', '#fbbf24'],
                ],
            ],
            'labels' => ['Sesuai', 'Hilang', 'Salah Ruangan'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
