<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyArea extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'image_path',
        'image_url',
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'property_area_id');
    }

    public function browserTitle(): string
    {
        return $this->meta_title ?: $this->name;
    }

    /**
     * Uploaded image first, then external URL.
     */
    public function bannerImageUrl(): ?string
    {
        if ($this->image_path) {
            return SiteSetting::resolvePublicUrl($this->image_path);
        }

        return $this->image_url ? (string) $this->image_url : null;
    }

    /**
     * Options for property assign &lt;select&gt;: id =&gt; name, with empty “None” first.
     *
     * @return array<int|string, string>
     */
    public static function optionsForPropertyAssign(): array
    {
        $opts = ['' => '— None —'];
        foreach (static::query()->orderBy('sort_order')->orderBy('name')->get() as $area) {
            $opts[$area->id] = $area->name;
        }

        return $opts;
    }
}
