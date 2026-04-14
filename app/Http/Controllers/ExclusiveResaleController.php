<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\ExclusiveResaleListing;
use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExclusiveResaleController extends Controller
{
    public function index(): View
    {
        $listings = ExclusiveResaleListing::query()
            ->published()
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return view('frontend.exclusive-resale.index', [
            'page' => Page::bySlug('exclusive-resale'),
            'listings' => $listings,
        ]);
    }

    public function submitEnquiry(Request $request, ExclusiveResaleListing $listing): RedirectResponse
    {
        if (! $listing->is_published) {
            throw new NotFoundHttpException;
        }
        if ($listing->published_at !== null && $listing->published_at->isFuture()) {
            throw new NotFoundHttpException;
        }

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:32',
            'message' => 'required|string|max:4000',
        ]);

        Enquiry::create([
            'source' => Enquiry::SOURCE_EXCLUSIVE_RESALE,
            'exclusive_resale_listing_id' => $listing->id,
            'property_id' => null,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'subject' => Str::limit('Exclusive resale: '.$listing->displayCode().' — '.$listing->title, 200),
            'message' => $data['message'],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('exclusive-resale.index')
            ->withFragment('er-'.$listing->id)
            ->with('exclusive_resale_enquiry_status', 'Thank you — your enquiry for '.$listing->title.' was sent. We will respond shortly.');
    }
}
