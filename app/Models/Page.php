<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'locale',
        'seo',
        'published',
        'sort_order',
    ];

    protected $casts = [
        'seo' => 'array',
        'published' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class)->orderBy('sort_order');
    }

    public function publishedSections(): HasMany
    {
        return $this->sections()->where('published', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function seoTitle(): string
    {
        return data_get($this->seo, 'title') ?: $this->title;
    }

    public function seoDescription(): ?string
    {
        return data_get($this->seo, 'description');
    }
}
