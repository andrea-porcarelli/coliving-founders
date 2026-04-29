@props(['section'])

@php
    $title = $section->get('title');
    $items = $section->get('items', []);

    $partnerSlugs = collect($items)->pluck('partner_slug')->filter()->unique()->all();
    $partners = $partnerSlugs
        ? \App\Models\Partner::whereIn('slug', $partnerSlugs)->get()->keyBy('slug')
        : collect();
@endphp

<x-blocks.wrap :section="$section">
    @if ($title)
        <h2 class="font-display text-3xl sm:text-4xl tracking-tight text-center">{{ $title }}</h2>
    @endif

    <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-8 items-start">
        @foreach ($items as $person)
            @php
                $partner = $partners[$person['partner_slug'] ?? null] ?? null;
                $logoUrl = $partner?->logoUrl('thumb') ?? ($person['photo'] ?? null);
            @endphp
            <div class="text-center">
                <div class="mx-auto h-24 flex items-center justify-center">
                    @if ($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $partner?->name ?? $person['name'] ?? '' }} logo" class="max-h-24 w-auto" loading="lazy" />
                    @else
                        <span class="font-display text-3xl text-brand-600/50">
                            {{ collect(explode(' ', $person['name'] ?? ''))->take(2)->map(fn($n) => mb_substr($n, 0, 1))->implode('') }}
                        </span>
                    @endif
                </div>
                <h3 class="mt-6 font-display text-xl tracking-tight">{{ $person['name'] ?? '' }}</h3>
                <p class="mt-1 text-sm text-ink/70">{{ $person['role'] ?? '' }}</p>
            </div>
        @endforeach
    </div>
</x-blocks.wrap>
