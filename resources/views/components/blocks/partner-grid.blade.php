@props(['section'])

@php
    $title = $section->get('title');
    $partners = \App\Models\Partner::published()->get();
@endphp

<x-blocks.wrap :section="$section">
    @if ($title)
        <h2 class="font-display text-3xl sm:text-4xl tracking-tight text-center">{{ $title }}</h2>
    @endif

    <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($partners as $partner)
            <a href="{{ route('partner.show', $partner) }}" class="group rounded-2xl bg-paper border border-black/10 p-6 hover:border-brand-300 hover:shadow-md transition flex flex-col">
                <div class="h-32 flex items-center justify-center bg-brand-50/50 rounded-lg overflow-hidden">
                    @if ($partner->logoUrl('thumb'))
                        <img src="{{ $partner->logoUrl('thumb') }}" alt="{{ $partner->name }} logo" class="max-h-24 w-auto" loading="lazy" />
                    @else
                        <span class="font-display text-2xl text-brand-600/40">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($partner->name, 0, 1)) }}</span>
                    @endif
                </div>

                <h3 class="mt-5 font-display text-xl tracking-tight">{{ $partner->name }}</h3>
                <p class="mt-1 text-sm font-medium text-brand-600">{{ $partner->location }}</p>
                <p class="mt-3 text-sm text-ink/70 flex-1">{{ \Illuminate\Support\Str::limit($partner->description, 130) }}</p>

                <span class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-brand-600 group-hover:gap-2 transition-all">
                    Learn more
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M7.05 4.55a.75.75 0 011.06 0l4.95 4.95a.75.75 0 010 1.06l-4.95 4.95a.75.75 0 01-1.06-1.06L11.44 10 7.05 5.61a.75.75 0 010-1.06z"/></svg>
                </span>
            </a>
        @endforeach
    </div>
</x-blocks.wrap>
