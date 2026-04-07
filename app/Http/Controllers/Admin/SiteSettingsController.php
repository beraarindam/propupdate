<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\GooglePlaceReviews;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SiteSettingsController extends Controller
{
    public function index(): View
    {
        return view('backend.site-settings', [
            'settings' => SiteSetting::current(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:64',
            'whatsapp' => 'nullable|string|max:64',
            'address' => 'nullable|string|max:2000',
            'website_url' => 'nullable|url|max:191',
            'facebook_url' => 'nullable|url|max:191',
            'instagram_url' => 'nullable|url|max:191',
            'youtube_url' => 'nullable|url|max:191',
            'linkedin_url' => 'nullable|url|max:191',
            'twitter_url' => 'nullable|url|max:191',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:2000',
            'footer_text' => 'nullable|string|max:5000',
            'logo' => 'nullable|image|max:4096|mimes:jpeg,png,jpg,gif,webp,svg',
            'favicon' => 'nullable|file|max:1024|mimes:png,jpg,jpeg,gif,svg,ico,x-icon',
            'promo_popup_enabled' => 'nullable|boolean',
            'promo_popup_image_url' => 'nullable|string|max:2000',
            'promo_popup_link_url' => 'nullable|string|max:500',
            'promo_popup_image' => 'nullable|image|max:5120|mimes:jpeg,png,jpg,gif,webp',
            'remove_promo_popup_image' => 'nullable|boolean',
            'google_reviews_enabled' => 'nullable|boolean',
            'google_place_id' => 'nullable|string|max:512',
            'google_places_api_key' => 'nullable|string|max:5000',
            'clear_google_places_api_key' => 'nullable|boolean',
        ]);

        $settings = SiteSetting::current();

        if ($request->hasFile('logo')) {
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $settings->logo_path = $request->file('logo')->store('site', 'public');
        }

        if ($request->hasFile('favicon')) {
            if ($settings->favicon_path) {
                Storage::disk('public')->delete($settings->favicon_path);
            }
            $settings->favicon_path = $request->file('favicon')->store('site', 'public');
        }

        if ($request->boolean('remove_promo_popup_image')) {
            if ($settings->promo_popup_image_path) {
                Storage::disk('public')->delete($settings->promo_popup_image_path);
            }
            $settings->promo_popup_image_path = null;
        }

        if ($request->hasFile('promo_popup_image')) {
            if ($settings->promo_popup_image_path) {
                Storage::disk('public')->delete($settings->promo_popup_image_path);
            }
            $settings->promo_popup_image_path = $request->file('promo_popup_image')->store('site/promo', 'public');
        }

        $settings->fill([
            'site_name' => $validated['site_name'] ?? null,
            'tagline' => $validated['tagline'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'whatsapp' => $validated['whatsapp'] ?? null,
            'address' => $validated['address'] ?? null,
            'website_url' => $validated['website_url'] ?? null,
            'facebook_url' => $validated['facebook_url'] ?? null,
            'instagram_url' => $validated['instagram_url'] ?? null,
            'youtube_url' => $validated['youtube_url'] ?? null,
            'linkedin_url' => $validated['linkedin_url'] ?? null,
            'twitter_url' => $validated['twitter_url'] ?? null,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'footer_text' => $validated['footer_text'] ?? null,
            'promo_popup_enabled' => $request->boolean('promo_popup_enabled'),
            'promo_popup_image_url' => $validated['promo_popup_image_url'] ?? null,
            'promo_popup_link_url' => $validated['promo_popup_link_url'] ?? null,
            'google_reviews_enabled' => $request->boolean('google_reviews_enabled'),
            'google_place_id' => isset($validated['google_place_id']) ? trim((string) $validated['google_place_id']) : null,
        ]);

        if ($request->boolean('clear_google_places_api_key')) {
            $settings->google_places_api_key = null;
        } elseif (filled($request->input('google_places_api_key'))) {
            $settings->google_places_api_key = $request->input('google_places_api_key');
        }

        $settings->save();

        GooglePlaceReviews::forgetCache((int) $settings->id);

        return redirect()
            ->route('admin.site-settings')
            ->with('site_settings_status', 'Site settings saved successfully.');
    }

    public function destroyLogo(): RedirectResponse
    {
        $settings = SiteSetting::current();
        if ($settings->logo_path) {
            Storage::disk('public')->delete($settings->logo_path);
            $settings->logo_path = null;
            $settings->save();
        }

        return redirect()->route('admin.site-settings')->with('site_settings_status', 'Logo removed.');
    }

    public function destroyFavicon(): RedirectResponse
    {
        $settings = SiteSetting::current();
        if ($settings->favicon_path) {
            Storage::disk('public')->delete($settings->favicon_path);
            $settings->favicon_path = null;
            $settings->save();
        }

        return redirect()->route('admin.site-settings')->with('site_settings_status', 'Favicon removed.');
    }

    public function destroyPromoPopupImage(): RedirectResponse
    {
        $settings = SiteSetting::current();
        if ($settings->promo_popup_image_path) {
            Storage::disk('public')->delete($settings->promo_popup_image_path);
            $settings->promo_popup_image_path = null;
            $settings->save();
        }

        return redirect()->route('admin.site-settings')->with('site_settings_status', 'Promo popup image removed.');
    }
}
