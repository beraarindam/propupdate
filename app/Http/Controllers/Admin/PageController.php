<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Support\AboutPageContent;
use App\Support\HomePageSections;
use App\Support\ListingIndexPageContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'banner_image' => 'nullable|image|max:5120|mimes:jpeg,png,jpg,gif,webp',
            'remove_banner_image' => 'nullable|boolean',
            'body_html' => 'nullable|string|max:200000',
            'is_published' => 'nullable|boolean',
        ];

        if ($page->slug === 'home') {
            $rules = array_merge($rules, [
                'hero_line1' => 'nullable|string|max:255',
                'hero_line2' => 'nullable|string|max:255',
                'hero_subtitle' => 'nullable|string|max:255',
                'hero_bg_alt' => 'nullable|string|max:255',
                'hero_search_placeholder' => 'nullable|string|max:255',
            ], HomePageSections::validationRules());
        }

        if ($page->slug === 'about-us') {
            $rules = array_merge($rules, AboutPageContent::validationRules());
        }

        if (in_array($page->slug, ['properties', 'projects', 'new-launches'], true)) {
            $rules = array_merge($rules, ListingIndexPageContent::validationRules());
        }

        $validated = $request->validate($rules);

        $fill = [
            'name' => $validated['name'],
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'body_html' => $validated['body_html'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ];

        if ($page->slug !== 'home') {
            $fill['banner_title'] = $validated['banner_title'] ?? null;
            $fill['banner_lead'] = $validated['banner_lead'] ?? null;
        }

        $page->fill($fill);

        if ($request->boolean('remove_banner_image')) {
            if ($page->banner_image_path) {
                Storage::disk('public')->delete($page->banner_image_path);
            }
            $page->banner_image_path = null;
            $page->banner_image_url = null;
        }

        if ($request->hasFile('banner_image')) {
            if ($page->banner_image_path) {
                Storage::disk('public')->delete($page->banner_image_path);
            }
            $page->banner_image_path = $request->file('banner_image')->store('pages/banners', 'public');
            $page->banner_image_url = null;
        }

        if ($page->slug === 'home') {
            $extras = is_array($page->extras) ? $page->extras : [];
            $prevSections = is_array($extras['sections'] ?? null) ? $extras['sections'] : [];
            $sections = HomePageSections::fromRequest($request, $prevSections);
            $this->applyHomePageFileUploads($request, $sections);

            $extras['hero'] = [
                'line1' => $request->input('hero_line1', ''),
                'line2' => $request->input('hero_line2', ''),
                'subtitle' => $request->input('hero_subtitle', ''),
                'bg_alt' => $request->input('hero_bg_alt', ''),
                'search_placeholder' => $request->input('hero_search_placeholder', ''),
            ];
            $extras['sections'] = $sections;
            $page->extras = $extras;
        }

        if ($page->slug === 'about-us') {
            $extras = is_array($page->extras) ? $page->extras : [];
            $prevAbout = is_array($extras['about_page'] ?? null) ? $extras['about_page'] : [];
            $about = AboutPageContent::fromRequest($request, $prevAbout);
            $this->applyAboutPageUploads($request, $about);
            $extras['about_page'] = $about;
            $page->extras = $extras;
        }

        if (in_array($page->slug, ['properties', 'projects', 'new-launches'], true)) {
            $extras = is_array($page->extras) ? $page->extras : [];
            $prevLi = is_array($extras['listing_index'] ?? null) ? $extras['listing_index'] : [];
            $extras['listing_index'] = ListingIndexPageContent::fromRequest($request, $prevLi);
            $page->extras = $extras;
        }

        $page->save();

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('page_status', 'Page saved successfully.');
    }

    /**
     * @param  array<string, mixed>  $sections
     */
    private function applyHomePageFileUploads(Request $request, array &$sections): void
    {
        $disk = 'public';

        for ($i = 0; $i < 4; $i++) {
            $pathKey = "categories.items.$i.image_path";
            if ($request->boolean("remove_home_cat_{$i}_image")) {
                $this->deletePublicFile(data_get($sections, $pathKey));
                data_set($sections, $pathKey, null);
            }
            if ($request->hasFile("home_cat_{$i}_image")) {
                $this->deletePublicFile(data_get($sections, $pathKey));
                data_set($sections, $pathKey, $request->file("home_cat_{$i}_image")->store('pages/home/categories', $disk));
                data_set($sections, "categories.items.$i.image_url", null);
            }
        }

        if ($request->boolean('remove_home_about_photo_top')) {
            $this->deletePublicFile(data_get($sections, 'about.photo_top_path'));
            data_set($sections, 'about.photo_top_path', null);
        }
        if ($request->hasFile('home_about_photo_top')) {
            $this->deletePublicFile(data_get($sections, 'about.photo_top_path'));
            data_set($sections, 'about.photo_top_path', $request->file('home_about_photo_top')->store('pages/home/about', $disk));
            data_set($sections, 'about.photo_top_url', null);
        }

        if ($request->boolean('remove_home_about_photo_main')) {
            $this->deletePublicFile(data_get($sections, 'about.photo_main_path'));
            data_set($sections, 'about.photo_main_path', null);
        }
        if ($request->hasFile('home_about_photo_main')) {
            $this->deletePublicFile(data_get($sections, 'about.photo_main_path'));
            data_set($sections, 'about.photo_main_path', $request->file('home_about_photo_main')->store('pages/home/about', $disk));
            data_set($sections, 'about.photo_main_url', null);
        }

        $proofPaths = data_get($sections, 'about.proof_avatar_paths', []);
        if (! is_array($proofPaths)) {
            $proofPaths = [];
        }
        $proofPaths = array_values(array_pad(array_slice($proofPaths, 0, 5), 5, null));
        $proofUrls = data_get($sections, 'about.proof_avatar_urls', []);
        if (! is_array($proofUrls)) {
            $proofUrls = [];
        }
        $proofUrls = array_values(array_pad(array_slice($proofUrls, 0, 5), 5, null));
        for ($i = 0; $i < 5; $i++) {
            if ($request->boolean("remove_home_about_proof_{$i}")) {
                $this->deletePublicFile($proofPaths[$i] ?? null);
                $proofPaths[$i] = null;
                $proofUrls[$i] = null;
            }
            if ($request->hasFile("home_about_proof_{$i}")) {
                $this->deletePublicFile($proofPaths[$i] ?? null);
                $proofPaths[$i] = $request->file("home_about_proof_{$i}")->store('pages/home/proof', $disk);
                $proofUrls[$i] = null;
            }
        }
        data_set($sections, 'about.proof_avatar_paths', $proofPaths);
        data_set($sections, 'about.proof_avatar_urls', $proofUrls);

        if ($request->boolean('remove_home_resale_hero_image')) {
            $this->deletePublicFile(data_get($sections, 'resale.hero_image_path'));
            data_set($sections, 'resale.hero_image_path', null);
        }
        if ($request->hasFile('home_resale_hero_image')) {
            $this->deletePublicFile(data_get($sections, 'resale.hero_image_path'));
            data_set($sections, 'resale.hero_image_path', $request->file('home_resale_hero_image')->store('pages/home/resale', $disk));
            data_set($sections, 'resale.hero_image_url', null);
        }
    }

    /**
     * @param  array<string, mixed>  $about
     */
    private function applyAboutPageUploads(Request $request, array &$about): void
    {
        $disk = 'public';

        if ($request->boolean('remove_about_intro_image')) {
            $this->deletePublicFile(data_get($about, 'intro.image_path'));
            data_set($about, 'intro.image_path', null);
            data_set($about, 'intro.image_url', null);
        }
        if ($request->hasFile('about_intro_image')) {
            $this->deletePublicFile(data_get($about, 'intro.image_path'));
            data_set($about, 'intro.image_path', $request->file('about_intro_image')->store('pages/about/intro', $disk));
            data_set($about, 'intro.image_url', null);
        }

        if ($request->boolean('remove_about_founder_photo')) {
            $this->deletePublicFile(data_get($about, 'founder.image_path'));
            data_set($about, 'founder.image_path', null);
            data_set($about, 'founder.image_url', null);
        }
        if ($request->hasFile('about_founder_photo')) {
            $this->deletePublicFile(data_get($about, 'founder.image_path'));
            data_set($about, 'founder.image_path', $request->file('about_founder_photo')->store('pages/about/founder', $disk));
            data_set($about, 'founder.image_url', null);
        }
    }

    private function deletePublicFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
