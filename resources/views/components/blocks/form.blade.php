@props(['section'])

@php
    $formId = $section->get('form_id', 'contact');
    $title = $section->get('title');
    $intro = $section->get('intro');
@endphp

<x-blocks.wrap :section="$section">
    <div class="max-w-2xl mx-auto">
        @if ($title)
            <h2 class="font-display text-3xl sm:text-4xl tracking-tight text-center">{{ $title }}</h2>
        @endif
        @if ($intro)
            <p class="mt-4 text-center text-lg text-ink/70">{{ $intro }}</p>
        @endif

        <div class="mt-10 rounded-2xl bg-paper border border-black/10 p-8 shadow-sm">
            <p class="text-center text-sm text-ink/50 italic">
                Form "{{ $formId }}" — Livewire form component arriva in Fase 5.
            </p>
            <p class="mt-2 text-center text-xs text-ink/40">
                Placeholder per non bloccare il rendering della pagina.
            </p>
        </div>
    </div>
</x-blocks.wrap>
