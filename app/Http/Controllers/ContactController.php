<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use App\Notifications\ContactFormSubmitted;
use App\Notifications\ContactFormConfirmation;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $contactData = [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
            ];

            // Send notification to admin using route notification
            Notification::route('mail', env('MAIL_ADMIN_EMAIL', 'admin@safepoint.com'))
                ->notify(new ContactFormSubmitted($contactData));

            // Send confirmation to user using route notification
            Notification::route('mail', $request->email)
                ->notify(new ContactFormConfirmation($request->name));

            return redirect()->back()->with('success', 'Your message has been sent successfully! We will get back to you soon.');
        } catch (\Exception $e) {
            \Log::error('Contact form error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an error sending your message. Please try again later.');
        }
    }
}
