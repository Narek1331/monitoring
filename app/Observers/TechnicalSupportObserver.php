<?php

namespace App\Observers;

use App\Models\TechnicalSupport;
use App\Mail\TechnicalSupportMail;
use Illuminate\Support\Facades\Mail;

class TechnicalSupportObserver
{
    /**
     * Handle the TechnicalSupport "created" event.
     */
    public function created(TechnicalSupport $technicalSupport): void
    {
        Mail::to('support@iqm-tools.ru')->send(new TechnicalSupportMail($technicalSupport));
    }

    /**
     * Handle the TechnicalSupport "updated" event.
     */
    public function updated(TechnicalSupport $technicalSupport): void
    {
        //
    }

    /**
     * Handle the TechnicalSupport "deleted" event.
     */
    public function deleted(TechnicalSupport $technicalSupport): void
    {
        //
    }

    /**
     * Handle the TechnicalSupport "restored" event.
     */
    public function restored(TechnicalSupport $technicalSupport): void
    {
        //
    }

    /**
     * Handle the TechnicalSupport "force deleted" event.
     */
    public function forceDeleted(TechnicalSupport $technicalSupport): void
    {
        //
    }
}
