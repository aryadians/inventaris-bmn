<?php

namespace App\Observers;

use App\Models\Loan;
use App\Models\User;
use App\Notifications\PeminjamanBaru;
use Illuminate\Support\Facades\Notification;

class LoanObserver
{
    /**
     * Handle the Loan "created" event.
     */
    public function created(Loan $loan): void
    {
        // Ambil semua user dengan role 'Admin'
        $admins = User::role('Admin')->get();

        // Kirim notifikasi ke setiap admin
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new PeminjamanBaru($loan));
        }
    }

    /**
     * Handle the Loan "updated" event.
     */
    public function updated(Loan $loan): void
    {
        //
    }

    /**
     * Handle the Loan "deleted" event.
     */
    public function deleted(Loan $loan): void
    {
        //
    }

    /**
     * Handle the Loan "restored" event.
     */
    public function restored(Loan $loan): void
    {
        //
    }

    /**
     * Handle the Loan "force deleted" event.
     */
    public function forceDeleted(Loan $loan): void
    {
        //
    }
}
