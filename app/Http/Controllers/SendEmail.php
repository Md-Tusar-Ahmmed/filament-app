<?php

namespace App\Http\Controllers;

use App\Mail\contactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendEmail extends Controller
{
    public function contactMail(Request $request){


        $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string',
            'msg' => 'required|string',
        ]);
        
        $toEmail = $request->email;
        $subject = $request->subject;
        $msg = $request->msg;

        Mail::to($toEmail)->send(new contactMail($msg, $subject));

        return back();
    }

}
