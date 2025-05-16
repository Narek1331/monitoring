<?php

namespace App\Mail;

use App\Models\TechnicalSupport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TechnicalSupportMail extends Mailable
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
