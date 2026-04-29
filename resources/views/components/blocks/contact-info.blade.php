@props(['section'])

@php
    $email = $section->get('email');
    $phone = $section->get('phone');
@endphp

<x-blocks.wrap :section="$section">
    <dl class="max-w-2xl mx-auto grid sm:grid-cols-2 gap-6">
        @if ($email)
            <div class="rounded-2xl border border-black/10 p-7 hover:border-brand-300 transition">
                <dt class="text-xs font-semibold uppercase tracking-widest text-ink/50">Email</dt>
                <dd class="mt-2 text-lg"><a href="mailto:{{ $email }}" class="text-brand-600 hover:underline">{{ $email }}</a></dd>
            </div>
        @endif
        @if ($phone)
            <div class="rounded-2xl border border-black/10 p-7 hover:border-brand-300 transition">
                <dt class="text-xs font-semibold uppercase tracking-widest text-ink/50">Phone</dt>
                <dd class="mt-2 text-lg"><a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="text-brand-600 hover:underline">{{ $phone }}</a></dd>
            </div>
        @endif
    </dl>
</x-blocks.wrap>
