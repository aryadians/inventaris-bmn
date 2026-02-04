<?php

namespace App\Observers;

use App\Models\Maintenance;

class MaintenanceObserver
{
    /**
     * Handle the Maintenance "created" event.
     */
    public function created(Maintenance $maintenance): void
    {
        if ($maintenance->asset && $maintenance->asset->kondisi == 'BAIK') {
            $maintenance->asset->update(['kondisi' => 'RUSAK_RINGAN']);
        }
    }

    /**
     * Handle the Maintenance "updated" event.
     */
    public function updated(Maintenance $maintenance): void
    {
        if ($maintenance->wasChanged('status')) {
            $asset = $maintenance->asset;
            if (!$asset) return;

            if ($maintenance->status == 'completed' || $maintenance->status == 'SELESAI') {
                $asset->update(['kondisi' => 'BAIK']);
            } elseif ($maintenance->status == 'unrepairable') {
                $asset->update(['kondisi' => 'RUSAK_BERAT']);
            }
        }
    }

    /**
     * Handle the Maintenance "deleted" event.
     */
    public function deleted(Maintenance $maintenance): void
    {
        //
    }

    /**
     * Handle the Maintenance "restored" event.
     */
    public function restored(Maintenance $maintenance): void
    {
        //
    }

    /**
     * Handle the Maintenance "force deleted" event.
     */
    public function forceDeleted(Maintenance $maintenance): void
    {
        //
    }
}
