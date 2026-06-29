<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResidentRegistrationRequest extends Model
{
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'fullname',
        'username',
        'email',
        'password_hash',
        'contact',
        'age',
        'address',
        'profile_image',
        'profile_image_mime',
        'status',
        'reviewed_by',
        'reviewed_at',
        'decision_reason',
    ];

    protected $hidden = [
        'password_hash',
        'profile_image',
        'profile_image_mime',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function getFullnameAttribute($value): string
    {
        $parts = array_filter([
            trim((string) ($this->attributes['first_name'] ?? '')),
            trim((string) ($this->attributes['middle_name'] ?? '')),
            trim((string) ($this->attributes['last_name'] ?? '')),
        ], static fn ($part) => $part !== '');

        if (! empty($parts)) {
            return implode(' ', $parts);
        }

        return trim((string) ($value ?? ''));
    }
}
