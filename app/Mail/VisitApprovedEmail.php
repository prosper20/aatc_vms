<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Visit;

class VisitApprovedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $visit;
    public $qrCodeBase64;

    public function __construct(Visit $visit, string $qrCodeBase64)
    {
        $this->visit = $visit;
        $this->qrCodeBase64 = $qrCodeBase64;
    }

    public function build()
    {
        return $this->subject('Your Visit has been Confirmed!')
                    ->view('emails.visit-approved')
                    ->with([
                        'visit' => $this->visit,
                        'qrCodeBase64' => $this->qrCodeBase64,
                    ]);
    }

    public function attachments(): array
    {
        return [];
    }
}



// namespace App\Mail;

// use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Mail\Mailable;
// use Illuminate\Mail\Mailables\Content;
// use Illuminate\Mail\Mailables\Envelope;
// use Illuminate\Queue\SerializesModels;
// use App\Models\Visit;

// class VisitApprovedEmail extends Mailable
// {
//     use Queueable, SerializesModels;

//     public $visit;

//     /**
//      * Create a new message instance.
//      */
//     public function __construct(Visit $visit)
//     {
//         $this->visit = $visit;
//     }

//     public function build()
//     {
//         return $this->subject('Your Visit has been Approved!')
//                     ->view('emails.visit-approved')
//                     ->with([
//                         'visit' => $this->visit,
//                         'qrCodePath' => public_path('qrcodes/' . $this->visit->unique_code . '.png'),
//                     ]);
//     }

//     /**
//      * Get the attachments for the message.
//      *
//      * @return array<int, \Illuminate\Mail\Mailables\Attachment>
//      */
//     public function attachments(): array
//     {
//         return [];
//     }
// }
