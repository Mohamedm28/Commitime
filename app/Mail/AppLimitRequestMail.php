<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppLimitRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function build()
    {
        return $this->subject('Child App Limit Request')
                    ->view('emails.app_limit_request')
                    ->with([
                        'app_name' => $this->request->app_name,
                        'time_limit' => $this->request->time_limit,
                        'approve_url' => url("/api/app-limits/approve/{$this->request->id}")
                    ]);
    }
}
