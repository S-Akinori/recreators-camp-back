<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Mail\ContactNotificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    //
    public function send(Request $request){
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'content' => 'required',
        ]);

        Mail::to($request->email)->send(new ContactMail($request->all()));
        Mail::to(config('mail.from.address'))->send(new ContactNotificationMail($request->all()));
        return response()->json('Mail Sent.', 200);
    }
}
