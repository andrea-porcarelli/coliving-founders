@php
    $year = now()->year;
@endphp

<footer class="bg-brand-900 text-paper mt-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid gap-10 md:grid-cols-3">
        <div>
            <p class="font-display text-2xl tracking-wide">COLIVING FOUNDERS</p>
            <p class="mt-3 text-sm text-paper/70 max-w-sm">
                An international network of community-driven colivings, created by founders who believe in collaboration over competition.
            </p>
        </div>

        <div>
            <p class="font-semibold uppercase text-xs tracking-widest text-paper/60">Explore</p>
            <ul class="mt-4 space-y-2 text-sm">
                <li><a href="/coliving-partners" class="hover:text-brand-200 transition">Coliving Partners</a></li>
                <li><a href="/for-companies" class="hover:text-brand-200 transition">For Companies</a></li>
                <li><a href="/about" class="hover:text-brand-200 transition">About</a></li>
                <li><a href="/contact" class="hover:text-brand-200 transition">Contact</a></li>
            </ul>
        </div>

        <div>
            <p class="font-semibold uppercase text-xs tracking-widest text-paper/60">Get in touch</p>
            <ul class="mt-4 space-y-2 text-sm">
                <li><a href="mailto:info@colivingfounders.com" class="hover:text-brand-200 transition">info@colivingfounders.com</a></li>
                <li><a href="tel:+393884462409" class="hover:text-brand-200 transition">+39 388 446 2409</a></li>
            </ul>
        </div>
    </div>

    <div class="border-t border-paper/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs text-paper/60">
            <p>&copy; {{ $year }} Coliving Founders. All rights reserved.</p>
            <p>colivingfounders.com</p>
        </div>
    </div>
</footer>
