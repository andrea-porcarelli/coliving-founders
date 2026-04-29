<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Partner extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'slug',
        'name',
        'location',
        'description',
        'website',
        'rooms',
        'published',
        'sort_order',
    ];

    protected $casts = [
        'published' => 'boolean',
        'rooms' => 'integer',
        'sort_order' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
        $this->addMediaCollection('photos');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->width(320)->height(320)->nonQueued();
        $this->addMediaConversion('card')->width(640)->height(480)->nonQueued();
    }

    public function logoUrl(string $conversion = 'card'): ?string
    {
        $media = $this->getFirstMedia('logo');
        return $media?->getUrl($conversion) ?: $media?->getUrl();
    }

    public function scopePublished($query)
    {
        return $query->where('published', true)->orderBy('sort_order');
    }
}
