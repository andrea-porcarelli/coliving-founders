@props(['section'])

@php
    $title = $section->get('title');
    $items = $section->get('items', []);
@endphp

<x-blocks.wrap :section="$section">
    @if ($title)
        <h2 class="font-display text-3xl sm:text-4xl tracking-tight text-center">{{ $title }}</h2>
    @endif

    <ol class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($items as $i => $item)
            <li class="relative">
                <div class="flex items-center gap-4">
                    <span class="flex-none flex items-center justify-center w-12 h-12 rounded-full bg-brand-600 text-paper font-display text-lg">{{ $i + 1 }}</span>
                    <h3 class="font-display text-lg tracking-tight">{{ $item['title'] ?? '' }}</h3>
                </div>
                <p class="mt-3 text-base text-ink/70">{{ $item['text'] ?? '' }}</p>
            </li>
        @endforeach
    </ol>
</x-blocks.wrap>
