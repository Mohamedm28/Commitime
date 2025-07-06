<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $child;
    public $reportData;

    public function __construct($child, $reportData)
    {
        $this->child = $child;
        $this->reportData = $reportData;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Sendreport',
        );
    }

    // //public function build()
    // {
    //     return $this->subject('Daily Screen Time Report')
    //                 ->view('emails.daily_report');
    // }
    public function content(): Content
    {
        return new Content(
            view: 'emails.daily_report',
            with:[
                'child'=>$this->child,
                'reportData'=>$this->reportData
            ]
        );
    }
}
