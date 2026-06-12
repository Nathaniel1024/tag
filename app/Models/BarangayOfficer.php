<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangayOfficer extends Model
{
    protected $fillable = [
        'fullname',
        'email',
        'username',
        'password',
        'contact',
        'address',
        'role',
        'last_seen',
    ];
    
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'last_seen' => 'datetime',
    ];
}
