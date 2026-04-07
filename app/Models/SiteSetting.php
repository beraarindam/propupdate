<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    protected $table = 'site_settings';

    protected function casts(): array
    {
        return [
            'promo_popup_enabled' => 'boolean',
            'google_reviews_enabled' => 'boolean',
            'google_places_api_key' => 'encrypted',
        ];
    }

    protected $fillable = [
        'site_name',
        'tagline',
        'logo_path',
        'favicon_path',
        'email',
        'phone',
        'whatsapp',
        'address',
        'website_url',
        'facebook_url',
        'instagram_url',
        'youtube_url',
        'linkedin_url',
        'twitter_url',
        'meta_title',
        'meta_description',
        'footer_text',
        'promo_popup_enabled',
        'promo_popup_image_path',
        'promo_popup_image_url',
        'promo_popup_link_url',
        'google_reviews_enabled',
        'google_place_id',
        'google_places_api_key',
    ];

    /**
     * Single site configuration row (id = 1).
     */
    public static function current(): self
    {
        $row = static::query()->first();
        if ($row) {
            return $row;
        }

        return static::query()->create([
            'site_name' => 'PropUpdate',
            'tagline' => 'Update your property search',
        ]);
    }

    /**
     * Public URL for a file on the "public" disk.
     * Uses the request base path when the app runs in a subdirectory (e.g. WAMP /public/…),
     * otherwise falls back to APP_URL + /storage/…
     */
    public static function resolvePublicUrl(?string $path): ?string
    {
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        $normalized = ltrim(str_replace('\\', '/', $path), '/');
        $suffix = '/storage/'.$normalized;

        if (! app()->runningInConsole()) {
            $base = rtrim(request()->getBasePath(), '/');
            if ($base !== '') {
                return $base.$suffix;
            }
        }

        return rtrim((string) config('app.url'), '/').$suffix;
    }

    public function logoUrl(): ?string
    {
        return static::resolvePublicUrl($this->logo_path);
    }

    public function faviconUrl(): ?string
    {
        return static::resolvePublicUrl($this->favicon_path);
    }

    /**
     * Promo modal image: uploaded file first, then external URL.
     */
    public function promoPopupBannerUrl(): ?string
    {
        $fromPath = static::resolvePublicUrl($this->promo_popup_image_path);
        if ($fromPath) {
            return $fromPath;
        }
        $u = $this->promo_popup_image_url ? trim((string) $this->promo_popup_image_url) : '';

        return $u !== '' ? $u : null;
    }

    /**
     * Stable id for localStorage: changing image/link shows the popup again for returning visitors.
     */
    public function promoPopupDismissSignature(): string
    {
        $raw = implode('|', [
            (string) ($this->promo_popup_image_path ?? ''),
            trim((string) ($this->promo_popup_image_url ?? '')),
            trim((string) ($this->promo_popup_link_url ?? '')),
            (string) ($this->id ?? '0'),
        ]);

        return hash('sha256', $raw);
    }

    public function telHref(): ?string
    {
        if (! $this->phone) {
            return null;
        }
        $digits = preg_replace('/\D+/', '', $this->phone);
        if ($digits === '') {
            return null;
        }
        if (strlen($digits) === 10) {
            $digits = '91'.$digits;
        }

        return 'tel:+'.$digits;
    }

    public function whatsappHref(): ?string
    {
        if (! $this->whatsapp) {
            return null;
        }
        $digits = preg_replace('/\D+/', '', $this->whatsapp);

        return $digits ? 'https://wa.me/'.$digits : null;
    }

    public function mailtoHref(): ?string
    {
        return $this->email ? 'mailto:'.$this->email : null;
    }
}
