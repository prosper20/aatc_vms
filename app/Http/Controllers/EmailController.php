<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\VisitApprovedEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\Visit;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
    public function send(Request $request)
    {

        \Log::info('EmailController@send triggered', [
            'visit_id' => $request->visit_id,
            'type' => $request->type,
        ]);

        $request->validate([
            'visit_id' => 'required|exists:visits,id',
            'type' => 'required|string|in:approved,invitation,welcome',
        ]);

        $visit = Visit::findOrFail($request->visit_id);

        \Log::info('Visit fetched', [
            'visitor_name' => $visit->visitor_name,
            'unique_code' => $visit->unique_code,
            'visitor_email' => $visit->visitor->email ?? 'N/A',
        ]);

        // Example switch on email type
        switch ($request->type) {
            case 'approved':
                $qrCodeSvg = QrCode::size(200)->generate($visit->unique_code);
                \Log::info('QR Code generated');

                Mail::to($visit->visitor->email)->send(new VisitApprovedEmail($visit, $qrCodeSvg));

                \Log::info('Approval email sent');
                break;

            case 'invitation':
                // Mail::to(...)->send(new VisitInvitationEmail(...));
                break;

            case 'welcome':
                // Mail::to(...)->send(new WelcomeEmail(...));
                break;

            default:
                return response()->json(['message' => 'Invalid email type'], 400);
        }

        return response()->json(['message' => 'Email sent successfully']);
    }
}
