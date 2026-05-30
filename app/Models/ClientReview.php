<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientReview extends Model
{
    protected $fillable = [
        'reviewer_name',
        'content',
        'rating',
        'image_path',
        'image_url',
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    public function avatarUrl(): ?string
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

    public function ratingStars(): int
    {
        return max(1, min(5, (int) $this->rating));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, static>
     */
    public static function publishedForDisplay()
    {
        return static::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();
    }
}
