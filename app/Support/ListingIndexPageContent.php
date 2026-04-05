<?php

namespace App\Support;

use Illuminate\Http\Request;

final class ListingIndexPageContent
{
    /**
     * @param  array<string, mixed>  $previous
     * @return array<string, string>
     */
    public static function fromRequest(Request $request, array $previous): array
    {
        return [
            'sidebar_title' => trim((string) $request->input('listing_sidebar_title', data_get($previous, 'sidebar_title', ''))),
            'sidebar_lead' => trim((string) $request->input('listing_sidebar_lead', data_get($previous, 'sidebar_lead', ''))),
            'empty_title' => trim((string) $request->input('listing_empty_title', data_get($previous, 'empty_title', ''))),
            'empty_message' => trim((string) $request->input('listing_empty_message', data_get($previous, 'empty_message', ''))),
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function validationRules(): array
    {
        return [
            'listing_sidebar_title' => 'nullable|string|max:255',
            'listing_sidebar_lead' => 'nullable|string|max:2000',
            'listing_empty_title' => 'nullable|string|max:255',
            'listing_empty_message' => 'nullable|string|max:4000',
        ];
    }
}
