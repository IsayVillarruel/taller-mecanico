<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendNote extends Mailable
{
    use Queueable, SerializesModels;

    public $pdf;
    public $noteNumber;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pdf, $id)
    {
        $this->pdf = $pdf;
        $this->noteNumber = $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name = $this->noteNumber.".pdf";
        $subject = "Su Nota Informativa es la siguiente";

        return $this->subject($subject)
                    ->from('correo@correo.com')
                    ->view('admin.mail.note')
                    ->attachData($this->pdf, $name);
    }
}
