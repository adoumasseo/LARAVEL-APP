<?php

namespace App\Http\Controllers;

use App\Mail\DynamicEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $request->validate([
            'recipients' => 'required|array',
            'recipients.*' => 'email',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $recipients = $request->recipients;
        $subject = $request->subject;
        $content = $request->content;

        foreach ($recipients as $recipient) {
            Mail::to($recipient)->send(new DynamicEmail($subject, $content));
        }

        return response()->json(['message' => 'Emails sent successfully!']);
    }
}
