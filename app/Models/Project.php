<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'property_category_id',
        'property_area_id',
        'slug',
        'title',
        'summary',
        'body',
        'extras',
        'location',
        'address_line1',
        'address_line2',
        'locality',
        'city',
        'state',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'developer_name',
        'maps_link_url',
        'rera_number',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'featured_image_path',
        'featured_image_url',
        'gallery_paths',
        'master_plan_path',
        'floor_plan_paths',
        'is_published',
        'is_featured',
        'is_new_launch',
        'sort_order',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'extras' => 'array',
            'gallery_paths' => 'array',
            'floor_plan_paths' => 'array',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'is_new_launch' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function browserTitle(): string
    {
        return $this->meta_title ?: $this->title;
    }

    public function featuredBannerUrl(): ?string
    {
        if ($this->featured_image_path) {
            return static::resolveStorageOrRemoteUrl($this->featured_image_path);
        }

        $u = $this->featured_image_url ? trim((string) $this->featured_image_url) : '';

        return $u !== '' ? $u : null;
    }

    /**
     * @param  mixed  $default
     * @return mixed
     */
    public function extra(string $key, $default = null)
    {
        $e = $this->extras;

        return is_array($e) ? ($e[$key] ?? $default) : $default;
    }

    /**
     * Public URL for a stored path or an absolute http(s) string.
     */
    protected static function resolveStorageOrRemoteUrl(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $v = trim($value);
        if ($v === '') {
            return null;
        }
        if (preg_match('/\Ahttps?:\/\//i', $v)) {
            return $v;
        }

        return SiteSetting::resolvePublicUrl($v);
    }

    /**
     * @return array<int, string>
     */
    public function galleryPublicUrls(): array
    {
        $paths = $this->gallery_paths;
        if (! is_array($paths)) {
            return [];
        }
        $urls = [];
        foreach ($paths as $path) {
            if (! is_string($path) || $path === '') {
                continue;
            }
            $u = static::resolveStorageOrRemoteUrl($path);
            if ($u) {
                $urls[] = $u;
            }
        }

        return $urls;
    }

    public function masterPlanUrl(): ?string
    {
        return $this->master_plan_path ? static::resolveStorageOrRemoteUrl($this->master_plan_path) : null;
    }

    /**
     * @return array<int, string>
     */
    public function floorPlanPublicUrls(): array
    {
        $paths = $this->floor_plan_paths;
        if (! is_array($paths)) {
            return [];
        }
        $urls = [];
        foreach ($paths as $path) {
            if (! is_string($path) || $path === '') {
                continue;
            }
            $u = static::resolveStorageOrRemoteUrl($path);
            if ($u) {
                $urls[] = $u;
            }
        }

        return $urls;
    }

    /**
     * @return array<int, array{label: string, value: string}>
     */
    public function quickFactsRows(): array
    {
        $rows = $this->extra('quick_facts', []);
        if (! is_array($rows)) {
            return [];
        }
        $out = [];
        foreach ($rows as $r) {
            if (! is_array($r)) {
                continue;
            }
            $label = isset($r['label']) ? trim((string) $r['label']) : '';
            $value = isset($r['value']) ? trim((string) $r['value']) : '';
            if ($label === '') {
                continue;
            }
            $out[] = ['label' => $label, 'value' => $value];
        }

        return $out;
    }

    /**
     * @return array<int, array{unit_type: string, size_sqft: string, price_label: string}>
     */
    public function unitPricingRows(): array
    {
        $rows = $this->extra('unit_pricing', []);
        if (! is_array($rows)) {
            return [];
        }
        $out = [];
        foreach ($rows as $r) {
            if (! is_array($r)) {
                continue;
            }
            $out[] = [
                'unit_type' => trim((string) ($r['unit_type'] ?? '')),
                'size_sqft' => trim((string) ($r['size_sqft'] ?? '')),
                'price_label' => trim((string) ($r['price_label'] ?? '')),
            ];
        }

        return $out;
    }

    public function priceDisclaimer(): ?string
    {
        $s = $this->extra('price_disclaimer');
        if (! is_string($s)) {
            return null;
        }
        $s = trim($s);

        return $s !== '' ? $s : null;
    }

    /**
     * @return array<int, string>
     */
    public function amenitiesList(): array
    {
        $a = $this->extra('amenities', []);
        if (! is_array($a)) {
            return [];
        }

        return array_values(array_filter($a, fn ($s) => is_string($s) && trim($s) !== ''));
    }

    /**
     * @return array<int, array{label: string, value: string}>
     */
    public function specificationsRows(): array
    {
        $rows = $this->extra('specifications', []);
        if (! is_array($rows)) {
            return [];
        }
        $out = [];
        foreach ($rows as $r) {
            if (! is_array($r)) {
                continue;
            }
            $label = isset($r['label']) ? trim((string) $r['label']) : '';
            if ($label === '') {
                continue;
            }
            $out[] = ['label' => $label, 'value' => trim((string) ($r['value'] ?? ''))];
        }

        return $out;
    }

    /**
     * @return array<int, string>
     */
    public function expertProsList(): array
    {
        $a = $this->extra('expert_pros', []);
        if (! is_array($a)) {
            return [];
        }

        return array_values(array_filter($a, fn ($s) => is_string($s) && trim($s) !== ''));
    }

    /**
     * @return array<int, string>
     */
    public function expertConsList(): array
    {
        $a = $this->extra('expert_cons', []);
        if (! is_array($a)) {
            return [];
        }

        return array_values(array_filter($a, fn ($s) => is_string($s) && trim($s) !== ''));
    }

    /**
     * @return array<int, array{question: string, answer: string}>
     */
    public function faqsList(): array
    {
        $rows = $this->extra('faqs', []);
        if (! is_array($rows)) {
            return [];
        }
        $out = [];
        foreach ($rows as $r) {
            if (! is_array($r)) {
                continue;
            }
            $q = isset($r['question']) ? trim((string) $r['question']) : '';
            if ($q === '') {
                continue;
            }
            $out[] = ['question' => $q, 'answer' => trim((string) ($r['answer'] ?? ''))];
        }

        return $out;
    }

    public function developerAboutHtml(): ?string
    {
        $s = $this->extra('developer_about_html');
        if (! is_string($s)) {
            return null;
        }
        $s = trim($s);

        return $s !== '' ? $s : null;
    }

    public function locationAddressLine(): ?string
    {
        $fromFields = collect([
            $this->address_line1,
            $this->address_line2,
            $this->locality,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ])->filter()->implode(', ');
        if ($fromFields !== '') {
            return $fromFields;
        }

        $s = $this->extra('location_address');
        if (is_string($s) && trim($s) !== '') {
            return trim($s);
        }

        return $this->location ? trim((string) $this->location) : null;
    }

    public function ctaHeadline(): ?string
    {
        $s = $this->extra('cta_headline');
        if (! is_string($s)) {
            return null;
        }
        $s = trim($s);

        return $s !== '' ? $s : null;
    }

    public function ctaSubtext(): ?string
    {
        $s = $this->extra('cta_subtext');
        if (! is_string($s)) {
            return null;
        }
        $s = trim($s);

        return $s !== '' ? $s : null;
    }

    public function lastUpdatedNote(): ?string
    {
        $s = $this->extra('last_updated_note');
        if (! is_string($s)) {
            return null;
        }
        $s = trim($s);

        return $s !== '' ? $s : null;
    }

    public function quickFactsAsPlainText(): string
    {
        $rows = $this->quickFactsRows();
        if ($rows === []) {
            return '';
        }

        return collect($rows)
            ->map(fn ($r) => $r['label'].' | '.$r['value'])
            ->implode("\n");
    }

    public function unitPricingAsPlainText(): string
    {
        $rows = $this->unitPricingRows();
        if ($rows === []) {
            return '';
        }

        return collect($rows)
            ->map(function ($r) {
                $parts = array_values(array_filter([
                    $r['unit_type'],
                    $r['size_sqft'],
                    $r['price_label'],
                ], fn ($v) => $v !== ''));

                return implode(' | ', $parts);
            })
            ->filter(fn ($line) => $line !== '')
            ->implode("\n");
    }

    public function specificationsAsPlainText(): string
    {
        $rows = $this->specificationsRows();
        if ($rows === []) {
            return '';
        }

        return collect($rows)
            ->map(fn ($r) => $r['label'].' | '.$r['value'])
            ->implode("\n");
    }

    public function expertProsAsPlainText(): string
    {
        return implode("\n", $this->expertProsList());
    }

    public function expertConsAsPlainText(): string
    {
        return implode("\n", $this->expertConsList());
    }

    public function projectFaqsAsPlainText(): string
    {
        $faqs = $this->faqsList();
        if ($faqs === []) {
            return '';
        }
        $blocks = [];
        foreach ($faqs as $f) {
            $blocks[] = $f['question'].':::'."\n".$f['answer'];
        }

        return implode("\n---\n", $blocks);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at');
    }

    public function scopeNewLaunch(Builder $query): Builder
    {
        return $query->where('is_new_launch', true);
    }
}
