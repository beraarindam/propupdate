<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use App\Models\Page;
use Illuminate\Contracts\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $items = GalleryItem::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('frontend.gallery.index', [
            'page' => Page::bySlug('gallery'),
            'items' => $items,
        ]);
    }
}
