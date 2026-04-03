<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $table = 'blog_posts';

    protected $fillable = [
        'slug',
        'title',
        'excerpt',
        'body',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'featured_image_url',
        'featured_image_path',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
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

    /**
     * Featured hero image: uploaded file first, then external URL.
     */
    public function featuredBannerUrl(): ?string
    {
        if ($this->featured_image_path) {
            return SiteSetting::resolvePublicUrl($this->featured_image_path);
        }

        return $this->featured_image_url ?: null;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at');
    }
}
