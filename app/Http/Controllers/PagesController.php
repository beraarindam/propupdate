<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PagesController extends Controller
{
    public function about(): View
    {
        return view('frontend.about-us');
    }

    public function contact(): View
    {
        return view('frontend.contact');
    }

    public function contactSubmit(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:32',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|max:4000',
        ]);

        return redirect()
            ->route('pages.contact')
            ->with('contact_status', 'Thank you — we have received your message and will respond shortly.');
    }

    public function privacy(): View
    {
        return view('frontend.privacy-policy');
    }

    public function terms(): View
    {
        return view('frontend.terms-and-conditions');
    }
}
