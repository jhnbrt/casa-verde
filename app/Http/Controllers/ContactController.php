<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Receive the contact form and email it to the resort.
     * The recipient is MAIL_FROM_ADDRESS in .env (set it to the resort's inbox).
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $body = "From: {$data['name']} <{$data['email']}>\n\n{$data['message']}";

        Mail::raw($body, function ($mail) use ($data) {
            $mail->to(config('mail.from.address'))
                ->replyTo($data['email'], $data['name'])
                ->subject('[Casa Verde website] '.$data['subject']);
        });

        return back()->with('contact_status', 'Thank you! Your message has been sent. We will reply within 24 hours.');
    }
}
