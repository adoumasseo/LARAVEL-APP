<?php

use Illuminate\Support\Facades\Route;
use App\Mail\DynamicEmail;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/send-email', function () {
    $subject = 'Test Email';
    $content = 'This is a test email content.';
    $recipients = ['adoumasseo@gmail.com'];

    foreach ($recipients as $recipient) {
        Mail::to($recipient)->send(new DynamicEmail($subject, $content));
    }

    return 'Email sent!';
});
