<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavigationItem extends Model
{
    protected $fillable = [
        'label',
        'href',
        'sort_order',
        'published',
        'open_in_new_tab',
    ];

    protected $casts = [
        'published' => 'boolean',
        'open_in_new_tab' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopePublished($query)
    {
        return $query->where('published', true)->orderBy('sort_order');
    }
}
