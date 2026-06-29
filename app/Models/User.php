<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Idinagdag ito para sa API tokens

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * Ito ang mga fields na pwedeng i-save mula sa form.
     */
    protected $fillable = [
        'id',
        'username',
        'name',
        'fullname',
        'first_name',
        'middle_name',
        'last_name',
        'profile_image',
        'profile_image_mime',
        'age',
        'contact',
        'address',
        'email',
        'email_verified_at',
        'password',
        'role',
        'remember_token',
        'created_at',
        'updated_at',
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

        return trim((string) ($value ?? $this->attributes['name'] ?? ''));
    }

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'profile_image',
        'profile_image_mime',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
