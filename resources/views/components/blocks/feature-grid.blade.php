@props(['section'])

@php
    $title = $section->get('title');
    $intro = $section->get('intro');
    $columns = (int) $section->get('columns', 3);
    $items = $section->get('items', []);

    $gridClass = match ($columns) {
        2 => 'sm:grid-cols-2',
        4 => 'sm:grid-cols-2 lg:grid-cols-4',
        default => 'sm:grid-cols-2 lg:grid-cols-3',
    };
@endphp

<x-blocks.wrap :section="$section">
    @if ($title || $intro)
        <div class="max-w-3xl text-center mx-auto">
            @if ($title)
                <h2 class="font-display text-3xl sm:text-4xl tracking-tight">{{ $title }}</h2>
            @endif
            @if ($intro)
                <p class="mt-4 text-lg text-ink/70">{{ $intro }}</p>
            @endif
        </div>
    @endif

    <div class="mt-12 grid gap-8 {{ $gridClass }}">
        @foreach ($items as $item)
            <div class="rounded-2xl bg-paper border border-black/5 p-7 shadow-sm">
                @if (! empty($item['icon']))
                    <div class="mb-4 inline-flex items-center justify-center w-11 h-11 rounded-lg bg-brand-50 text-brand-600">
                        {!! $item['icon'] !!}
                    </div>
                @endif
                <h3 class="font-display text-xl tracking-tight">{{ $item['title'] ?? '' }}</h3>
                <p class="mt-3 text-base text-ink/70 leading-relaxed">{{ $item['text'] ?? '' }}</p>
            </div>
        @endforeach
    </div>
</x-blocks.wrap>
