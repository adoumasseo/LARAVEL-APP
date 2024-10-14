<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Log;


use Illuminate\Http\Request;

class MailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipients' => 'required|array',
            'recipients.*' => 'email',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $recipients = $request->recipients;
        $subject = $request->subject;
        $content = $request->content;

        foreach ($recipients as $recipient) {
            $mail = Mail::to($recipient)->send(new TestMail($subject, $content));
        }

        return response()->json(['message' => 'Emails sent successfully!', 'email' => $mail]);
    }
}
