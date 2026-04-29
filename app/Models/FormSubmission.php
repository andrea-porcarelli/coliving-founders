<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'type',
        'payload',
        'ip',
        'user_agent',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
