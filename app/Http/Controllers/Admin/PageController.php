<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        return view('backend.pages.index', [
            'pages' => Page::query()->orderBy('name')->get(),
        ]);
    }

    public function edit(Page $page): View
    {
        return view('backend.pages.edit', [
            'page' => $page,
        ]);
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $rules = [
            'name' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:5000',
            'meta_keywords' => 'nullable|string|max:255',
            'banner_title' => 'nullable|string|max:255',
            'banner_lead' => 'nullable|string|max:10000',
            'banner_image_url' => 'nullable|string|max:2000',
            'body_html' => 'nullable|string|max:200000',
            'is_published' => 'nullable|boolean',
        ];

        if ($page->slug === 'home') {
            $rules = array_merge($rules, [
                'hero_line1' => 'nullable|string|max:255',
                'hero_line2' => 'nullable|string|max:255',
                'hero_subtitle' => 'nullable|string|max:255',
                'hero_bg_url' => 'nullable|string|max:2000',
                'hero_search_placeholder' => 'nullable|string|max:255',
            ]);
        }

        $validated = $request->validate($rules);

        $page->fill([
            'name' => $validated['name'],
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'banner_title' => $validated['banner_title'] ?? null,
            'banner_lead' => $validated['banner_lead'] ?? null,
            'banner_image_url' => $validated['banner_image_url'] ?? null,
            'body_html' => $validated['body_html'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ]);

        if ($page->slug === 'home') {
            $page->extras = [
                'hero' => [
                    'line1' => $request->input('hero_line1', ''),
                    'line2' => $request->input('hero_line2', ''),
                    'subtitle' => $request->input('hero_subtitle', ''),
                    'bg_url' => $request->input('hero_bg_url', ''),
                    'search_placeholder' => $request->input('hero_search_placeholder', ''),
                ],
            ];
        }

        $page->save();

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('page_status', 'Page saved successfully.');
    }
}
