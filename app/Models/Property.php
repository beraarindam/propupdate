<?php

namespace App\Models;

use App\Support\ProjectDetailTextParsers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Property extends Model
{
    public const LISTING_SALE = 'sale';

    public const LISTING_RENT = 'rent';

    public const LISTING_BOTH = 'both';

    protected $fillable = [
        'property_category_id',
        'property_area_id',
        'property_type_id',
        'slug',
        'title',
        'listing_type',
        'price',
        'price_currency',
        'price_on_request',
        'maintenance_charges',
        'bedrooms',
        'bathrooms',
        'balconies',
        'parking_covered',
        'built_up_area_sqft',
        'carpet_area_sqft',
        'plot_area_sqft',
        'floor_number',
        'total_floors',
        'facing',
        'furnishing',
        'age_of_property_years',
        'possession_status',
        'developer_name',
        'rera_number',
        'developer_description',
        'project_land_area',
        'total_units',
        'towers_blocks_summary',
        'unit_variants_summary',
        'maps_link_url',
        'price_disclaimer',
        'configuration_rows',
        'unit_mix',
        'specifications',
        'expert_pros',
        'expert_cons',
        'project_faqs',
        'master_plan_path',
        'master_plan_paths',
        'floor_plan_paths',
        'address_line1',
        'address_line2',
        'locality',
        'city',
        'state',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'summary',
        'description',
        'amenities',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'featured_image_path',
        'featured_image_url',
        'gallery_paths',
        'is_published',
        'is_featured',
        'is_new_launch',
        'sort_order',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'price_on_request' => 'boolean',
            'bedrooms' => 'decimal:1',
            'bathrooms' => 'decimal:1',
            'built_up_area_sqft' => 'decimal:2',
            'carpet_area_sqft' => 'decimal:2',
            'plot_area_sqft' => 'decimal:2',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'amenities' => 'array',
            'configuration_rows' => 'array',
            'unit_mix' => 'array',
            'specifications' => 'array',
            'expert_pros' => 'array',
            'expert_cons' => 'array',
            'project_faqs' => 'array',
            'master_plan_paths' => 'array',
            'floor_plan_paths' => 'array',
            'gallery_paths' => 'array',
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(PropertyCategory::class, 'property_category_id');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(PropertyArea::class, 'property_area_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class, 'property_type_id');
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

        return $this->featured_image_url ? (string) $this->featured_image_url : null;
    }

    /**
     * Public URL for a stored path or an absolute http(s) string (e.g. CDN / Unsplash in JSON).
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
        return $this->masterPlanPublicUrls()[0] ?? null;
    }

    /**
     * @return array<int, string>
     */
    public function masterPlanPublicUrls(): array
    {
        $paths = is_array($this->master_plan_paths) ? $this->master_plan_paths : [];
        if (is_string($this->master_plan_path) && $this->master_plan_path !== '') {
            array_unshift($paths, $this->master_plan_path);
        }
        $paths = array_values(array_unique(array_filter($paths, fn ($path) => is_string($path) && $path !== '')));

        $urls = [];
        foreach ($paths as $path) {
            $u = static::resolveStorageOrRemoteUrl($path);
            if ($u) {
                $urls[] = $u;
            }
        }

        return $urls;
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
     * Key facts table: auto-filled from project fields + extra configuration rows.
     *
     * @return array<int, array{label: string, value: string}>
     */
    public function allConfigurationRows(): array
    {
        $rows = [];
        $loc = collect([$this->locality, $this->city, $this->state])->filter()->implode(', ');
        if ($loc !== '') {
            $rows[] = ['label' => 'Project location', 'value' => $loc];
        }
        if ($this->project_land_area) {
            $rows[] = ['label' => 'Total land area', 'value' => (string) $this->project_land_area];
        }
        if ($this->total_units !== null) {
            $rows[] = ['label' => 'No. of units', 'value' => number_format((int) $this->total_units)];
        }
        if ($this->towers_blocks_summary) {
            $rows[] = ['label' => 'Towers / blocks', 'value' => (string) $this->towers_blocks_summary];
        }
        if ($this->unit_variants_summary) {
            $rows[] = ['label' => 'Unit variants', 'value' => (string) $this->unit_variants_summary];
        }
        if ($this->possession_status) {
            $rows[] = ['label' => 'Possession', 'value' => (string) $this->possession_status];
        }
        foreach (is_array($this->configuration_rows) ? $this->configuration_rows : [] as $r) {
            $label = isset($r['label']) ? trim((string) $r['label']) : '';
            $value = isset($r['value']) ? trim((string) $r['value']) : '';
            if ($label === '') {
                continue;
            }
            $rows[] = ['label' => $label, 'value' => $value];
        }

        return $rows;
    }

    /** Pipe rows for admin form (Label | Value). */
    public function configurationExtraAsPlainText(): string
    {
        if (! is_array($this->configuration_rows) || $this->configuration_rows === []) {
            return '';
        }

        return collect($this->configuration_rows)
            ->map(fn ($r) => ($r['label'] ?? '').' | '.($r['value'] ?? ''))
            ->implode("\n");
    }

    /** Unit mix for admin (Unit | Size | Price). */
    public function unitMixAsPlainText(): string
    {
        if (! is_array($this->unit_mix) || $this->unit_mix === []) {
            return '';
        }

        return collect($this->unit_mix)
            ->map(function ($r) {
                $parts = array_values(array_filter([
                    trim((string) ($r['unit_type'] ?? '')),
                    trim((string) ($r['size_sqft'] ?? '')),
                    trim((string) ($r['price_label'] ?? '')),
                ], fn ($v) => $v !== ''));

                return implode(' | ', $parts);
            })
            ->filter(fn ($line) => $line !== '')
            ->implode("\n");
    }

    public function specificationsAsPlainText(): string
    {
        if (! is_array($this->specifications) || $this->specifications === []) {
            return '';
        }

        return collect($this->specifications)
            ->map(fn ($r) => ($r['label'] ?? '').' | '.($r['value'] ?? ''))
            ->implode("\n");
    }

    public function expertProsAsPlainText(): string
    {
        if (! is_array($this->expert_pros)) {
            return '';
        }

        return implode("\n", array_filter($this->expert_pros, fn ($s) => is_string($s) && $s !== ''));
    }

    public function expertConsAsPlainText(): string
    {
        if (! is_array($this->expert_cons)) {
            return '';
        }

        return implode("\n", array_filter($this->expert_cons, fn ($s) => is_string($s) && $s !== ''));
    }

    public function projectFaqsAsPlainText(): string
    {
        $faqs = $this->projectFaqsList();
        if ($faqs === []) {
            return '';
        }
        $blocks = [];
        foreach ($faqs as $f) {
            $blocks[] = $f['question'].':::'."\n".$f['answer'];
        }

        return implode("\n---\n", $blocks);
    }

    /**
     * @return array<int, array{question: string, answer: string}>
     */
    public function projectFaqsList(): array
    {
        if (! is_array($this->project_faqs) || $this->project_faqs === []) {
            return [];
        }

        $blocks = [];
        foreach ($this->project_faqs as $f) {
            if (! is_array($f)) {
                continue;
            }
            $q = isset($f['question']) ? trim((string) $f['question']) : '';
            if ($q === '') {
                continue;
            }
            $a = isset($f['answer']) ? trim((string) $f['answer']) : '';
            $blocks[] = $q.':::'."\n".$a;
        }

        // Re-parse normalized plain text to repair older mixed rows.
        return ProjectDetailTextParsers::parseFaqs(implode("\n---\n", $blocks), 80);
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

    public static function listingTypeOptions(): array
    {
        return [
            self::LISTING_SALE => 'For sale',
            self::LISTING_RENT => 'For rent',
            self::LISTING_BOTH => 'Sale & rent',
        ];
    }
}
