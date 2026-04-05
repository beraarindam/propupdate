<?php

namespace App\Support;

use Illuminate\Http\Request;

final class AboutPageContent
{
    /**
     * @param  array<string, mixed>  $previous
     * @return array<string, mixed>
     */
    public static function fromRequest(Request $request, array $previous): array
    {
        $items = [];
        for ($i = 1; $i <= 3; $i++) {
            $items[] = [
                'icon' => trim((string) $request->input("about_value_{$i}_icon", '')),
                'title' => trim((string) $request->input("about_value_{$i}_title", '')),
                'text' => trim((string) $request->input("about_value_{$i}_text", '')),
            ];
        }

        return [
            'intro' => [
                'image_path' => data_get($previous, 'intro.image_path'),
                'image_url' => data_get($previous, 'intro.image_url'),
                'image_alt' => trim((string) $request->input('about_intro_image_alt', '')),
                'badge_num' => trim((string) $request->input('about_intro_badge_num', '')),
                'badge_text' => trim((string) $request->input('about_intro_badge_text', '')),
                'kicker' => trim((string) $request->input('about_intro_kicker', '')),
                'h2' => trim((string) $request->input('about_intro_h2', '')),
                'paragraph_1' => trim((string) $request->input('about_intro_paragraph_1', '')),
                'paragraph_2' => trim((string) $request->input('about_intro_paragraph_2', '')),
                'cta_text' => trim((string) $request->input('about_intro_cta_text', '')),
                'cta_href' => trim((string) $request->input('about_intro_cta_href', '')),
            ],
            'founder' => [
                'image_path' => data_get($previous, 'founder.image_path'),
                'image_url' => data_get($previous, 'founder.image_url'),
                'image_alt' => trim((string) $request->input('about_founder_image_alt', '')),
                'eyebrow' => trim((string) $request->input('about_founder_eyebrow', '')),
                'name' => trim((string) $request->input('about_founder_name', '')),
                'role' => trim((string) $request->input('about_founder_role', '')),
                'quote' => trim((string) $request->input('about_founder_quote', '')),
                'body_1' => trim((string) $request->input('about_founder_body_1', '')),
                'body_2' => trim((string) $request->input('about_founder_body_2', '')),
            ],
            'values' => [
                'kicker' => trim((string) $request->input('about_values_kicker', '')),
                'h2' => trim((string) $request->input('about_values_h2', '')),
                'items' => $items,
            ],
            'stats' => [
                'stat1_num' => trim((string) $request->input('about_stat_1_num', '')),
                'stat1_label' => trim((string) $request->input('about_stat_1_label', '')),
                'stat2_num' => trim((string) $request->input('about_stat_2_num', '')),
                'stat2_label' => trim((string) $request->input('about_stat_2_label', '')),
                'stat3_num' => trim((string) $request->input('about_stat_3_num', '')),
                'stat3_label' => trim((string) $request->input('about_stat_3_label', '')),
                'cta_label' => trim((string) $request->input('about_stats_cta_label', '')),
                'cta_tel' => trim((string) $request->input('about_stats_cta_tel', '')),
            ],
        ];
    }

    public static function validationRules(): array
    {
        $rules = [
            'about_intro_image' => 'nullable|image|max:5120|mimes:jpeg,png,jpg,gif,webp',
            'remove_about_intro_image' => 'nullable|boolean',
            'about_intro_image_alt' => 'nullable|string|max:255',
            'about_intro_badge_num' => 'nullable|string|max:50',
            'about_intro_badge_text' => 'nullable|string|max:255',
            'about_intro_kicker' => 'nullable|string|max:255',
            'about_intro_h2' => 'nullable|string|max:255',
            'about_intro_paragraph_1' => 'nullable|string|max:8000',
            'about_intro_paragraph_2' => 'nullable|string|max:8000',
            'about_intro_cta_text' => 'nullable|string|max:120',
            'about_intro_cta_href' => 'nullable|string|max:500',
            'about_founder_photo' => 'nullable|image|max:5120|mimes:jpeg,png,jpg,gif,webp',
            'remove_about_founder_photo' => 'nullable|boolean',
            'about_founder_image_alt' => 'nullable|string|max:255',
            'about_founder_eyebrow' => 'nullable|string|max:255',
            'about_founder_name' => 'nullable|string|max:255',
            'about_founder_role' => 'nullable|string|max:255',
            'about_founder_quote' => 'nullable|string|max:2000',
            'about_founder_body_1' => 'nullable|string|max:8000',
            'about_founder_body_2' => 'nullable|string|max:8000',
            'about_values_kicker' => 'nullable|string|max:255',
            'about_values_h2' => 'nullable|string|max:255',
            'about_stats_cta_label' => 'nullable|string|max:120',
            'about_stats_cta_tel' => 'nullable|string|max:120',
        ];

        for ($i = 1; $i <= 3; $i++) {
            $rules["about_value_{$i}_icon"] = 'nullable|string|max:120';
            $rules["about_value_{$i}_title"] = 'nullable|string|max:255';
            $rules["about_value_{$i}_text"] = 'nullable|string|max:2000';
        }

        for ($i = 1; $i <= 3; $i++) {
            $rules["about_stat_{$i}_num"] = 'nullable|string|max:50';
            $rules["about_stat_{$i}_label"] = 'nullable|string|max:255';
        }

        return $rules;
    }
}
