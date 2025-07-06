<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChildRegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $child;

    /**
     * Create a new message instance.
     */
    public function __construct($child)
    {
        $this->child = $child;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject("Your Child's Registration Confirmation")
                    ->view('emails.child_registration_confirmation')
                    ->with([
                        'child' => $this->child,
                    ]);
    }
}
