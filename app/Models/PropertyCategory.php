<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyCategory extends Model
{
    protected $fillable = [
        'parent_id',
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

    public function parent(): BelongsTo
    {
        return $this->belongsTo(PropertyCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(PropertyCategory::class, 'parent_id')
            ->orderBy('sort_order')
            ->orderBy('name');
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
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
     * IDs of this category and all nested descendants (for parent dropdown / cycle guard).
     *
     * @return array<int, int>
     */
    public function branchIds(): array
    {
        $ids = [$this->id];
        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->branchIds());
        }

        return $ids;
    }

    /**
     * Flat options for &lt;select&gt;: value = id, label = indented name. Excludes $excludeIds from the tree.
     *
     * @param  array<int, int>  $excludeIds
     * @return array<int, string>
     */
    public static function nestedSelectOptions(array $excludeIds = []): array
    {
        $options = ['' => '— Top level (no parent) —'];

        $walk = function (?int $parentId, int $depth) use (&$options, &$walk, $excludeIds) {
            $query = static::query()
                ->where('parent_id', $parentId)
                ->orderBy('sort_order')
                ->orderBy('name');

            foreach ($query->get() as $cat) {
                if (in_array($cat->id, $excludeIds, true)) {
                    continue;
                }
                $prefix = $depth > 0 ? str_repeat('— ', $depth) : '';
                $options[$cat->id] = $prefix.$cat->name;
                $walk($cat->id, $depth + 1);
            }
        };

        $walk(null, 0);

        return $options;
    }

    /**
     * @return array<int|string, string>
     */
    public static function optionsForPropertyAssign(): array
    {
        $options = static::nestedSelectOptions();
        $options[''] = '— None —';

        return $options;
    }
}
