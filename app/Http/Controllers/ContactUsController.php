<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendInquiryRequest;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ContactUsController extends Controller
{
    public function index()
    {
        return Inertia::render('ContactUs');
    }

    public function store(SendInquiryRequest $request)
    {
        Mail::raw($request->validated('message'), function ($message) use ($request) {
            $message->to('ecprimecorp@gmail.com')
                ->from($request->validated('email'))
                ->subject('Website Inquiry');
        });

        return back()->with('message', [
            'content' => 'Your inquiry has been sent.',
            'type' => 'success',
        ]);
    }
}
