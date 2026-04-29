@props(['section'])

@php
    $formId = $section->get('form_id', 'contact');
    $title = $section->get('title');
    $intro = $section->get('intro');
@endphp

<x-blocks.wrap :section="$section">
    <div class="max-w-3xl mx-auto">
        @if ($title)
            <h2 class="font-display text-3xl sm:text-4xl tracking-tight text-center">{{ $title }}</h2>
        @endif
        @if ($intro)
            <p class="mt-4 text-center text-lg text-ink/70 max-w-2xl mx-auto">{{ $intro }}</p>
        @endif

        <div class="mt-10 rounded-2xl bg-paper border border-black/10 p-6 sm:p-10 shadow-sm">
            @switch($formId)
                @case('partner')
                    <livewire:forms.partner-form :key="'form-' . $section->id" />
                    @break
                @case('workation')
                    <livewire:forms.workation-form :key="'form-' . $section->id" />
                    @break
                @default
                    <livewire:forms.contact-form :key="'form-' . $section->id" />
            @endswitch
        </div>
    </div>
</x-blocks.wrap>
