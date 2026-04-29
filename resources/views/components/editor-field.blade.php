@props(['label', 'name' => null, 'help' => null])

<label class="block">
    <span class="text-xs font-semibold uppercase tracking-widest text-ink/60">{{ $label }}</span>
    <div class="mt-1.5">{{ $slot }}</div>
    @if ($help)
        <span class="block mt-1 text-xs text-ink/50">{{ $help }}</span>
    @endif
</label>
