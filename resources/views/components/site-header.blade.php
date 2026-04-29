@php
    $nav = [
        ['label' => 'Home', 'route' => 'home', 'href' => '/'],
        ['label' => 'Coliving Partners', 'route' => 'partners', 'href' => '/coliving-partners'],
        ['label' => 'For Companies', 'route' => 'companies', 'href' => '/for-companies'],
        ['label' => 'About', 'route' => 'about', 'href' => '/about'],
        ['label' => 'Contact', 'route' => 'contact', 'href' => '/contact'],
    ];
@endphp

<header
    x-data="{ open: false, scrolled: false }"
    x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 8)"
    :class="scrolled ? 'bg-paper/95 backdrop-blur shadow-sm' : 'bg-paper'"
    class="sticky top-0 z-50 transition-colors duration-200 border-b border-black/5"
>
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between" aria-label="Primary">
        <a href="/" class="flex items-center gap-2 group">
            <span class="font-display text-xl tracking-wide text-brand-600 group-hover:text-brand-700 transition">
                COLIVING<br class="hidden">FOUNDERS
            </span>
        </a>

        <ul class="hidden lg:flex items-center gap-8">
            @foreach ($nav as $item)
                <li>
                    <a href="{{ $item['href'] }}"
                       class="text-sm font-medium text-ink/80 hover:text-brand-600 transition relative
                              {{ request()->is(trim($item['href'], '/') ?: '/') ? 'text-brand-600' : '' }}">
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="flex items-center gap-3">
            <a href="#become-a-partner"
               class="hidden sm:inline-flex items-center gap-2 rounded-full bg-brand-600 px-5 py-2.5 text-sm font-semibold text-paper shadow-sm hover:bg-brand-700 transition">
                Become a Partner
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M7.05 4.55a.75.75 0 011.06 0l4.95 4.95a.75.75 0 010 1.06l-4.95 4.95a.75.75 0 01-1.06-1.06L11.44 10 7.05 5.61a.75.75 0 010-1.06z"/></svg>
            </a>

            <button type="button"
                    @click="open = !open"
                    class="lg:hidden inline-flex items-center justify-center p-2 rounded-md text-ink hover:bg-black/5"
                    :aria-expanded="open"
                    aria-controls="mobile-menu"
                    aria-label="Toggle navigation">
                <svg x-show="!open" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="open" x-cloak class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </nav>

    <div id="mobile-menu" x-show="open" x-cloak x-transition class="lg:hidden border-t border-black/5">
        <ul class="px-4 py-4 space-y-2">
            @foreach ($nav as $item)
                <li>
                    <a href="{{ $item['href'] }}" class="block py-2 text-base font-medium text-ink/80 hover:text-brand-600">{{ $item['label'] }}</a>
                </li>
            @endforeach
            <li class="pt-2">
                <a href="#become-a-partner" class="inline-flex items-center gap-2 rounded-full bg-brand-600 px-5 py-2.5 text-sm font-semibold text-paper">Become a Partner</a>
            </li>
        </ul>
    </div>
</header>
