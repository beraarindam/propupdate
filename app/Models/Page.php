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
}
