<?php

namespace App\Mail;

use App\Models\TechnicalSupport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TechnicalSupportMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $support;

    public function __construct(TechnicalSupport $support)
    {
        $this->support = $support;
    }

    public function build()
    {
        return $this->subject($this->support->subject)
                    ->markdown('emails.technical_support')
                    ->with([
                        'support' => $this->support,
                    ]);
    }
}
