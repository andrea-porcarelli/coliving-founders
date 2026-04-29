@props(['section', 'compact' => false])

@php
    $bg = data_get($section->style, 'bg');
    $isCompact = $compact || data_get($section->style, 'compact');

    $bgClass = match ($bg) {
        'tint' => 'bg-brand-50/50 border-y border-black/5',
        'brand' => 'bg-brand-600 text-paper',
        'dark' => 'bg-brand-900 text-paper',
        default => '',
    };

    $padding = $isCompact ? 'py-16 sm:py-20' : 'py-20 sm:py-28';
@endphp

<section {{ $attributes->merge(['class' => "$bgClass $padding"]) }} data-block="{{ $section->type }}" data-section-id="{{ $section->id }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{ $slot }}
    </div>
</section>
