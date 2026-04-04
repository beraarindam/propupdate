<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enquiry extends Model
{
    public const SOURCE_CONTACT = 'contact';

    public const SOURCE_PRE_REGISTER = 'pre_register';

    public const SOURCE_PROPERTY = 'property';

    protected $fillable = [
        'source',
        'property_id',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'ip_address',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function sourceLabel(): string
    {
        return match ($this->source) {
            self::SOURCE_PRE_REGISTER => 'Pre-register',
            self::SOURCE_PROPERTY => 'Property listing',
            default => 'Contact form',
        };
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function isUnread(): bool
    {
        return $this->read_at === null;
    }

    public function markRead(): void
    {
        if ($this->read_at === null) {
            $this->forceFill(['read_at' => now()])->save();
        }
    }
}
