<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExclusiveResaleListing extends Model
{
    protected $fillable = [
        'property_code',
        'title',
        'status_badge',
        'location',
        'property_type',
        'configuration',
        'area_display',
        'market_price',
        'asking_price',
        'rate_per_sqft',
        'image_path',
        'image_url',
        'sort_order',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'sort_order' => 'integer',
        ];
    }

    public function enquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class, 'exclusive_resale_listing_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->where(function (Builder $q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function imagePublicUrl(): ?string
    {
        if ($this->image_path) {
            $u = SiteSetting::resolvePublicUrl($this->image_path);
            if ($u) {
                return $u;
            }
        }
        $ext = $this->image_url ? trim((string) $this->image_url) : '';
        if ($ext !== '' && preg_match('/\Ahttps?:\/\//i', $ext)) {
            return $ext;
        }

        return null;
    }

    public function displayCode(): string
    {
        $c = $this->property_code ? trim((string) $this->property_code) : '';

        return $c !== '' ? $c : 'PU-'.$this->id;
    }
}
