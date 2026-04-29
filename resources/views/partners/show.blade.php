@php
    $schemaBuilder = app(\App\Services\SchemaBuilder::class);
    $crumbs = $schemaBuilder->breadcrumbs([
        ['Home', url('/')],
        ['Coliving Partners', url('/coliving-partners')],
        [$partner->name, route('partner.show', $partner)],
    ]);

    $schemaHtml = $schemaBuilder->renderAll([
        $schemaBuilder->lodgingBusiness($partner),
        $crumbs,
    ]);
@endphp

<x-layouts.app
    :title="$partner->name . ' — Coliving Founders'"
    :description="\Illuminate\Support\Str::limit($partner->description, 155)"
    :canonical="route('partner.show', $partner)"
    :og-image="$partner->logoUrl('card')"
    :schema="$schemaHtml"
>
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28">
        <p class="text-sm uppercase tracking-widest text-brand-600 font-semibold">{{ $partner->location }}</p>
        <h1 class="mt-2 font-display text-5xl sm:text-6xl">{{ $partner->name }}</h1>

        @if ($partner->logoUrl())
            <img src="{{ $partner->logoUrl() }}" alt="{{ $partner->name }} logo" class="mt-8 h-24 w-auto" loading="lazy" />
        @endif

        <p class="mt-8 text-lg text-ink/75 max-w-3xl">{{ $partner->description }}</p>

        <dl class="mt-10 grid sm:grid-cols-3 gap-6">
            <div class="rounded-xl border border-black/10 p-5">
                <dt class="text-xs font-semibold uppercase tracking-widest text-ink/50">Location</dt>
                <dd class="mt-1 text-base">{{ $partner->location }}</dd>
            </div>
            @if ($partner->rooms)
                <div class="rounded-xl border border-black/10 p-5">
                    <dt class="text-xs font-semibold uppercase tracking-widest text-ink/50">Rooms</dt>
                    <dd class="mt-1 text-base">{{ $partner->rooms }}</dd>
                </div>
            @endif
            @if ($partner->website)
                <div class="rounded-xl border border-black/10 p-5">
                    <dt class="text-xs font-semibold uppercase tracking-widest text-ink/50">Website</dt>
                    <dd class="mt-1 text-base"><a href="{{ $partner->website }}" target="_blank" rel="noopener" class="text-brand-600 hover:underline">Visit site →</a></dd>
                </div>
            @endif
        </dl>

        <div class="mt-12">
            <a href="/coliving-partners" class="inline-flex items-center gap-2 text-sm text-ink/70 hover:text-brand-600">
                ← Back to all partners
            </a>
        </div>
    </section>

    <x-become-a-partner-cta />
</x-layouts.app>
