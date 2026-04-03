<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PagesController extends Controller
{
    public function about(): View
    {
        return view('frontend.about-us', [
            'page' => Page::bySlug('about-us'),
        ]);
    }

    public function contact(): View
    {
        return view('frontend.contact', [
            'page' => Page::bySlug('contact'),
        ]);
    }

    public function contactSubmit(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:32',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|max:4000',
        ]);

        Enquiry::create([
            'source' => Enquiry::SOURCE_CONTACT,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('pages.contact')
            ->with('contact_status', 'Thank you — we have received your message and will respond shortly.');
    }

    public function privacy(): View
    {
        return view('frontend.privacy-policy', [
            'page' => Page::bySlug('privacy-policy'),
        ]);
    }

    public function terms(): View
    {
        return view('frontend.terms-and-conditions', [
            'page' => Page::bySlug('terms-and-conditions'),
        ]);
    }
}
