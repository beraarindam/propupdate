<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    protected $fillable = [
        'title',
        'caption',
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
}
