<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailController;
Route::get('/', function () {
    return view('welcome');
});
Route::post('/send-mail', [MailController::class, 'sendEmail']);