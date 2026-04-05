<?php

namespace App\Support;

use Illuminate\Http\Request;

final class HomePageSections
{
    /**
     * Build extras.sections for the home page. Pass previous sections so file paths are preserved when not replaced.
     *
     * @param  array<string, mixed>|null  $existingSections
     */
    public static function fromRequest(Request $request, ?array $existingSections = null): array
    {
        $ex = $existingSections ?? [];

        return [
            'categories' => self::categories($request, $ex['categories'] ?? []),
            'about' => self::about($request, $ex['about'] ?? []),
            'why' => self::why($request),
            'resale' => self::resale($request, $ex['resale'] ?? []),
            'launches' => self::launches($request),
            'services' => self::services($request),
        ];
    }

    public static function validationRules(): array
    {
        $rules = [
            'home_cat_source' => 'nullable|string|in:property_categories,custom',
            'hero_bg_alt' => 'nullable|string|max:255',
        ];

        for ($i = 0; $i < 4; $i++) {
            $rules["home_cat_{$i}_label"] = 'nullable|string|max:120';
            $rules["home_cat_{$i}_href"] = 'nullable|string|max:500';
            $rules["home_cat_{$i}_image"] = 'nullable|image|max:5120|mimes:jpeg,png,jpg,gif,webp';
            $rules["remove_home_cat_{$i}_image"] = 'nullable|boolean';
        }

        $rules += [
            'home_about_kicker' => 'nullable|string|max:255',
            'home_about_heading' => 'nullable|string|max:255',
            'home_about_body' => 'nullable|string|max:8000',
            'home_about_photo_top' => 'nullable|image|max:5120|mimes:jpeg,png,jpg,gif,webp',
            'home_about_photo_main' => 'nullable|image|max:5120|mimes:jpeg,png,jpg,gif,webp',
            'remove_home_about_photo_top' => 'nullable|boolean',
            'remove_home_about_photo_main' => 'nullable|boolean',
            'home_about_photo_top_alt' => 'nullable|string|max:255',
            'home_about_photo_main_alt' => 'nullable|string|max:255',
            'home_about_proof_title' => 'nullable|string|max:255',
            'home_about_proof_badge' => 'nullable|string|max:50',
            'home_about_stat1_val' => 'nullable|string|max:50',
            'home_about_stat1_label' => 'nullable|string|max:120',
            'home_about_stat2_val' => 'nullable|string|max:50',
            'home_about_stat2_label' => 'nullable|string|max:120',
            'home_about_stat3_val' => 'nullable|string|max:50',
            'home_about_stat3_label' => 'nullable|string|max:120',
            'home_about_btn_text' => 'nullable|string|max:120',
            'home_about_btn_url' => 'nullable|string|max:500',
            'home_why_eyebrow' => 'nullable|string|max:255',
            'home_why_title' => 'nullable|string|max:255',
            'home_why_title_accent' => 'nullable|string|max:255',
            'home_why_feature_1' => 'nullable|string|max:500',
            'home_why_feature_2' => 'nullable|string|max:500',
            'home_why_feature_3' => 'nullable|string|max:500',
            'home_why_feature_4' => 'nullable|string|max:500',
            'home_why_feature_5' => 'nullable|string|max:500',
            'home_why_quote' => 'nullable|string|max:2000',
            'home_why_showcase_label' => 'nullable|string|max:255',
            'home_why_chip_text' => 'nullable|string|max:255',
            'home_why_consult_text' => 'nullable|string|max:255',
            'home_why_cta_tel' => 'nullable|string|max:120',
            'home_why_website_url' => 'nullable|string|max:500',
            'home_why_website_label' => 'nullable|string|max:255',
            'home_resale_heading' => 'nullable|string|max:255',
            'home_resale_hero_image' => 'nullable|image|max:5120|mimes:jpeg,png,jpg,gif,webp',
            'remove_home_resale_hero_image' => 'nullable|boolean',
            'home_resale_hero_alt' => 'nullable|string|max:255',
            'home_launches_title' => 'nullable|string|max:255',
            'home_launches_lead' => 'nullable|string|max:4000',
            'home_launches_sub' => 'nullable|string|max:255',
            'home_launches_cta_line' => 'nullable|string|max:2000',
            'home_launches_aside_intro' => 'nullable|string|max:2000',
            'home_launches_form_title' => 'nullable|string|max:255',
            'home_launches_form_note' => 'nullable|string|max:2000',
            'home_services_kicker' => 'nullable|string|max:255',
            'home_services_title' => 'nullable|string|max:255',
            'home_services_lead' => 'nullable|string|max:2000',
        ];

        for ($i = 1; $i <= 4; $i++) {
            $rules["home_resale_card_{$i}_icon"] = 'nullable|string|max:120';
            $rules["home_resale_card_{$i}_title"] = 'nullable|string|max:255';
            $rules["home_resale_card_{$i}_text"] = 'nullable|string|max:2000';
            $rules["home_launches_benefit_{$i}_icon"] = 'nullable|string|max:120';
            $rules["home_launches_benefit_{$i}_title"] = 'nullable|string|max:255';
            $rules["home_launches_benefit_{$i}_text"] = 'nullable|string|max:2000';
        }

        for ($i = 0; $i < 5; $i++) {
            $rules["home_about_proof_{$i}"] = 'nullable|image|max:5120|mimes:jpeg,png,jpg,gif,webp';
            $rules["remove_home_about_proof_{$i}"] = 'nullable|boolean';
        }

        return $rules;
    }

    /**
     * @param  array<string, mixed>  $existing
     * @return array<string, mixed>
     */
    private static function categories(Request $request, array $existing): array
    {
        $source = $request->input('home_cat_source', 'property_categories');
        if (! in_array($source, ['property_categories', 'custom'], true)) {
            $source = 'property_categories';
        }

        $prevItems = $existing['items'] ?? [];
        $items = [];
        for ($i = 0; $i < 4; $i++) {
            $p = is_array($prevItems[$i] ?? null) ? $prevItems[$i] : [];
            $items[] = [
                'label' => trim((string) $request->input("home_cat_{$i}_label", '')),
                'href' => trim((string) $request->input("home_cat_{$i}_href", '')),
                'image_path' => $p['image_path'] ?? null,
                'image_url' => $p['image_url'] ?? null,
            ];
        }

        return [
            'source' => $source,
            'items' => $items,
        ];
    }

    /**
     * @param  array<string, mixed>  $prev
     * @return array<string, mixed>
     */
    private static function about(Request $request, array $prev): array
    {
        return [
            'kicker' => trim((string) $request->input('home_about_kicker', '')),
            'heading' => trim((string) $request->input('home_about_heading', '')),
            'body' => trim((string) $request->input('home_about_body', '')),
            'photo_top_path' => $prev['photo_top_path'] ?? null,
            'photo_main_path' => $prev['photo_main_path'] ?? null,
            'photo_top_url' => $prev['photo_top_url'] ?? null,
            'photo_main_url' => $prev['photo_main_url'] ?? null,
            'photo_top_alt' => trim((string) $request->input('home_about_photo_top_alt', '')),
            'photo_main_alt' => trim((string) $request->input('home_about_photo_main_alt', '')),
            'proof_title' => trim((string) $request->input('home_about_proof_title', '')),
            'proof_badge' => trim((string) $request->input('home_about_proof_badge', '')),
            'proof_avatar_paths' => $prev['proof_avatar_paths'] ?? [],
            'proof_avatar_urls' => $prev['proof_avatar_urls'] ?? [],
            'stat1_val' => trim((string) $request->input('home_about_stat1_val', '')),
            'stat1_label' => trim((string) $request->input('home_about_stat1_label', '')),
            'stat2_val' => trim((string) $request->input('home_about_stat2_val', '')),
            'stat2_label' => trim((string) $request->input('home_about_stat2_label', '')),
            'stat3_val' => trim((string) $request->input('home_about_stat3_val', '')),
            'stat3_label' => trim((string) $request->input('home_about_stat3_label', '')),
            'btn_text' => trim((string) $request->input('home_about_btn_text', '')),
            'btn_url' => trim((string) $request->input('home_about_btn_url', '')),
        ];
    }

    private static function why(Request $request): array
    {
        $features = [];
        for ($i = 1; $i <= 5; $i++) {
            $t = trim((string) $request->input("home_why_feature_{$i}", ''));
            if ($t !== '') {
                $features[] = $t;
            }
        }

        return [
            'eyebrow' => trim((string) $request->input('home_why_eyebrow', '')),
            'title' => trim((string) $request->input('home_why_title', '')),
            'title_accent' => trim((string) $request->input('home_why_title_accent', '')),
            'features' => $features,
            'quote' => trim((string) $request->input('home_why_quote', '')),
            'showcase_label' => trim((string) $request->input('home_why_showcase_label', '')),
            'chip_text' => trim((string) $request->input('home_why_chip_text', '')),
            'consult_text' => trim((string) $request->input('home_why_consult_text', '')),
            'cta_tel' => trim((string) $request->input('home_why_cta_tel', '')),
            'website_url' => trim((string) $request->input('home_why_website_url', '')),
            'website_label' => trim((string) $request->input('home_why_website_label', '')),
        ];
    }

    /**
     * @param  array<string, mixed>  $prev
     * @return array<string, mixed>
     */
    private static function resale(Request $request, array $prev): array
    {
        $cards = [];
        for ($i = 1; $i <= 4; $i++) {
            $cards[] = [
                'icon' => trim((string) $request->input("home_resale_card_{$i}_icon", '')),
                'title' => trim((string) $request->input("home_resale_card_{$i}_title", '')),
                'text' => trim((string) $request->input("home_resale_card_{$i}_text", '')),
            ];
        }

        return [
            'heading' => trim((string) $request->input('home_resale_heading', '')),
            'hero_image_path' => $prev['hero_image_path'] ?? null,
            'hero_image_url' => $prev['hero_image_url'] ?? null,
            'hero_alt' => trim((string) $request->input('home_resale_hero_alt', '')),
            'cards' => $cards,
        ];
    }

    private static function launches(Request $request): array
    {
        $benefits = [];
        for ($i = 1; $i <= 4; $i++) {
            $benefits[] = [
                'icon' => trim((string) $request->input("home_launches_benefit_{$i}_icon", '')),
                'title' => trim((string) $request->input("home_launches_benefit_{$i}_title", '')),
                'text' => trim((string) $request->input("home_launches_benefit_{$i}_text", '')),
            ];
        }

        return [
            'title' => trim((string) $request->input('home_launches_title', '')),
            'lead' => trim((string) $request->input('home_launches_lead', '')),
            'sub' => trim((string) $request->input('home_launches_sub', '')),
            'benefits' => $benefits,
            'cta_line' => trim((string) $request->input('home_launches_cta_line', '')),
            'aside_intro' => trim((string) $request->input('home_launches_aside_intro', '')),
            'form_title' => trim((string) $request->input('home_launches_form_title', '')),
            'form_note' => trim((string) $request->input('home_launches_form_note', '')),
        ];
    }

    private static function services(Request $request): array
    {
        return [
            'kicker' => trim((string) $request->input('home_services_kicker', '')),
            'title' => trim((string) $request->input('home_services_title', '')),
            'lead' => trim((string) $request->input('home_services_lead', '')),
        ];
    }
}
