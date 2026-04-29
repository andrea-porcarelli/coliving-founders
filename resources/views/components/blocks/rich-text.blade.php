@props(['section'])

@php
    $title = $section->get('title');
    $body = $section->get('body_html', '');
    $align = $section->get('align', 'left');
    $alignClass = $align === 'center' ? 'text-center mx-auto' : '';
    $bg = data_get($section->style, 'bg');
    $proseClass = $bg === 'brand' || $bg === 'dark' ? 'prose-invert' : '';
@endphp

<x-blocks.wrap :section="$section">
    <div class="max-w-3xl {{ $alignClass }}">
        @if ($title)
            <h2 class="font-display text-3xl sm:text-4xl tracking-tight">{{ $title }}</h2>
        @endif

        <div class="prose {{ $proseClass }} mt-6 text-lg leading-relaxed max-w-none">
            {!! $body !!}
        </div>
    </div>
</x-blocks.wrap>
