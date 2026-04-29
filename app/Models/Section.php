<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Section extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'page_id',
        'type',
        'sort_order',
        'content',
        'style',
        'published',
    ];

    protected $casts = [
        'content' => 'array',
        'style' => 'array',
        'published' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return data_get($this->content, $key, $default);
    }

    public function styleAttr(string $key, mixed $default = null): mixed
    {
        return data_get($this->style, $key, $default);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('bg')->singleFile();
        $this->addMediaCollection('image')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('lg')->width(1920)->nonQueued();
        $this->addMediaConversion('md')->width(960)->nonQueued();
    }

    public function imageUrl(string $collection = 'image', string $conversion = 'md'): ?string
    {
        $media = $this->getFirstMedia($collection);
        return $media?->getUrl($conversion) ?: $media?->getUrl();
    }
}
