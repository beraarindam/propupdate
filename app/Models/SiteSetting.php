<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    protected $table = 'site_settings';

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
