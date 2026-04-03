<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PropertyListingController extends Controller
{
    public function index(): View
    {
        $properties = Property::query()
            ->published()
            ->with(['category', 'type'])
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(12);

        return view('frontend.properties.index', [
            'page' => null,
            'properties' => $properties,
        ]);
    }

    public function show(Property $property): View
    {
        if (! $property->is_published || $property->published_at === null) {
            throw new NotFoundHttpException;
        }

        $property->load(['category', 'type']);

        return view('frontend.properties.show', [
            'page' => null,
            'property' => $property,
        ]);
    }
}
