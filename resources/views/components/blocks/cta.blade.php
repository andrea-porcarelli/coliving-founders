@props(['section'])

@php
    $title = $section->get('title');
    $text = $section->get('text');
    $button = $section->get('button');
    $bg = data_get($section->style, 'bg', 'brand');
    $textColor = in_array($bg, ['brand', 'dark']) ? 'text-paper' : 'text-ink';
    $btnClass = in_array($bg, ['brand', 'dark'])
        ? 'bg-paper text-brand-700 hover:bg-brand-50'
        : 'bg-brand-600 text-paper hover:bg-brand-700';
@endphp

<x-blocks.wrap :section="$section">
    <div class="text-center max-w-3xl mx-auto {{ $textColor }}">
        <h2 class="font-display text-3xl sm:text-5xl tracking-tight">{{ $title }}</h2>
        @if ($text)
            <p class="mt-4 text-lg opacity-90">{{ $text }}</p>
        @endif
        @if ($button)
            <a href="{{ $button['href'] }}" class="mt-8 inline-flex items-center gap-2 rounded-full px-7 py-3.5 text-sm font-semibold shadow {{ $btnClass }} transition">
                {{ $button['label'] }}
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M7.05 4.55a.75.75 0 011.06 0l4.95 4.95a.75.75 0 010 1.06l-4.95 4.95a.75.75 0 01-1.06-1.06L11.44 10 7.05 5.61a.75.75 0 010-1.06z"/></svg>
            </a>
        @endif
    </div>
</x-blocks.wrap>
