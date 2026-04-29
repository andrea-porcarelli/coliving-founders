@props(['section'])

@php
    $title = $section->get('title');
    $intro = $section->get('intro');
    $items = $section->get('items', []);
@endphp

<x-blocks.wrap :section="$section">
    <div class="max-w-3xl">
        @if ($title)
            <h2 class="font-display text-3xl sm:text-4xl tracking-tight">{{ $title }}</h2>
        @endif

        @if ($intro)
            <p class="mt-6 text-lg text-ink/75">{{ $intro }}</p>
        @endif

        <ul class="mt-8 space-y-3">
            @foreach ($items as $item)
                <li class="flex items-start gap-3 text-lg">
                    <svg class="mt-1.5 h-2.5 w-2.5 flex-none text-brand-600" viewBox="0 0 8 8" fill="currentColor" aria-hidden="true"><circle cx="4" cy="4" r="4"/></svg>
                    <span>{{ $item }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</x-blocks.wrap>
