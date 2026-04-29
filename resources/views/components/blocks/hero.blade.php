@props(['section'])

@php
    $title = $section->get('title');
    $highlight = $section->get('title_highlight');
    $subtitle = $section->get('subtitle');
    $ctas = $section->get('ctas', []);
    $isCompact = data_get($section->style, 'compact');
    $bgImage = $section->imageUrl('bg', 'lg');
    $hasBg = (bool) $bgImage;
    $textColor = $hasBg ? 'text-paper' : 'text-ink';
    $subTextColor = $hasBg ? 'text-paper/85' : 'text-ink/70';
    $highlightColor = $hasBg ? 'text-paper' : 'text-brand-600';
@endphp

<x-blocks.wrap
    :section="$section"
    :compact="(bool) $isCompact"
    class="relative isolate overflow-hidden {{ $hasBg ? '' : '' }}"
    style="{{ $hasBg ? 'background-image: url(' . $bgImage . '); background-size: cover; background-position: center;' : '' }}"
>
    @if ($hasBg)
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-brand-900/60 via-brand-900/40 to-brand-900/70"></div>
    @endif

    <div class="text-center max-w-4xl mx-auto {{ $textColor }}">
        <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl tracking-tight leading-[1.05]">
            {{ $title }}
            @if ($highlight)
                <span class="block {{ $highlightColor }}">{{ $highlight }}</span>
            @endif
        </h1>

        @if ($subtitle)
            <p class="mt-8 text-lg sm:text-xl {{ $subTextColor }} max-w-3xl mx-auto">{{ $subtitle }}</p>
        @endif

        @if (! empty($ctas))
            <div class="mt-10 flex flex-wrap items-center justify-center gap-3 sm:gap-4">
                @foreach ($ctas as $cta)
                    @php
                        $isPrimary = ($cta['style'] ?? 'primary') === 'primary';
                        if ($hasBg) {
                            $btnClass = $isPrimary
                                ? 'bg-paper text-brand-700 hover:bg-brand-50 shadow-lg'
                                : 'border border-paper/40 text-paper hover:bg-paper/10';
                        } else {
                            $btnClass = $isPrimary
                                ? 'bg-brand-600 text-paper hover:bg-brand-700 shadow-sm'
                                : 'border border-ink/15 text-ink hover:bg-black/[0.03]';
                        }
                    @endphp
                    <a href="{{ $cta['href'] }}" class="inline-flex items-center gap-2 rounded-full px-7 py-3.5 text-sm font-semibold transition {{ $btnClass }}">
                        {{ $cta['label'] }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-blocks.wrap>
