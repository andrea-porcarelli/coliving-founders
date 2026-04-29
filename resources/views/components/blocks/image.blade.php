@props(['section'])

@php
    $url = $section->imageUrl('image', 'lg');
    $caption = $section->get('caption');
    $alt = $section->get('alt') ?: $caption ?: 'Image';
@endphp

<x-blocks.wrap :section="$section">
    @if ($url)
        <figure class="mx-auto max-w-5xl">
            <img src="{{ $url }}" alt="{{ $alt }}" class="w-full h-auto rounded-2xl shadow-md" loading="lazy" />
            @if ($caption)
                <figcaption class="mt-4 text-center text-sm text-ink/60">{{ $caption }}</figcaption>
            @endif
        </figure>
    @else
        <div class="mx-auto max-w-3xl rounded-2xl border-2 border-dashed border-black/15 bg-brand-50/30 py-16 px-6 text-center text-ink/40">
            <p class="text-sm">No image uploaded yet.</p>
        </div>
    @endif
</x-blocks.wrap>
