<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClearanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref',
        'owner_key',
        'owner_name',
        'owner_email',
        'name',
        'email',
        'address',
        'age',
        'contact',
        'purpose',
        'purpose_reason',
        'status',
        'date_requested',
        'valid_until',
        'id_file_name',
        'id_file_path',
        'id_file_mime',
        'pdf_saved',
        'saved_cert_type',
        'saved_template',
        'saved_paper_html',
    ];

    protected $casts = [
        'date_requested' => 'date',
        'valid_until' => 'date',
        'pdf_saved' => 'boolean',
        'saved_template' => 'array',
    ];
}
