<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'banner_title',
        'banner_lead',
        'banner_image_url',
        'banner_image_path',
        'body_html',
        'extras',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'extras' => 'array',
            'is_published' => 'boolean',
        ];
    }

    public static function bySlug(?string $slug): ?self
    {
        if (! $slug) {
            return null;
        }

        return static::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->first();
    }

    public function browserTitle(): string
    {
        return $this->meta_title ?: $this->banner_title ?: $this->name;
    }

    public function hero(string $key, ?string $default = null): ?string
    {
        return data_get($this->extras ?? [], 'hero.'.$key, $default);
    }

    /**
     * Home page block content stored in extras.sections (managed from admin).
     *
     * @param  mixed  $default
     * @return mixed
     */
    public function section(string $path, $default = null)
    {
        return data_get($this->extras ?? [], 'sections.'.$path, $default);
    }

    /**
     * About Us page blocks stored in extras.about_page (managed from admin).
     *
     * @param  mixed  $default
     * @return mixed
     */
    public function aboutPage(string $path, $default = null)
    {
        return data_get($this->extras ?? [], 'about_page.'.$path, $default);
    }

    /**
     * Properties / projects index listing copy in extras.listing_index.
     *
     * @param  mixed  $default
     * @return mixed
     */
    public function listingIndex(string $path, $default = null)
    {
        return data_get($this->extras ?? [], 'listing_index.'.$path, $default);
    }

    /**
     * Banner / breadcrumb hero background: uploaded file first, then external URL.
     */
    public function bannerBackgroundUrl(): ?string
    {
        if ($this->banner_image_path) {
            $u = SiteSetting::resolvePublicUrl($this->banner_image_path);
            if ($u) {
                return $u;
            }
        }
        $url = $this->banner_image_url ? trim((string) $this->banner_image_url) : '';

        return $url !== '' ? $url : null;
    }

    /**
     * Resolve a link entered in the CMS (relative path, named route, or absolute URL).
     */
    public static function resolveHref(?string $href): string
    {
        $h = trim((string) $href);
        if ($h === '') {
            return route('properties.index');
        }
        if (preg_match('#^https?://#i', $h) || str_starts_with($h, '//')) {
            return $h;
        }
        if (str_starts_with($h, '/') || str_starts_with($h, '#') || str_starts_with($h, 'tel:') || str_starts_with($h, 'mailto:')) {
            return $h;
        }

        return url($h);
    }

    /**
     * Prefer a storage path (upload); fall back to legacy external URL from older CMS data.
     */
    public static function mediaPublicUrl(?string $storagePath, ?string $legacyUrl = null): ?string
    {
        if ($storagePath) {
            $u = SiteSetting::resolvePublicUrl($storagePath);
            if ($u) {
                return $u;
            }
        }
        $legacyUrl = $legacyUrl ? trim((string) $legacyUrl) : '';

        return $legacyUrl !== '' ? $legacyUrl : null;
    }
}
