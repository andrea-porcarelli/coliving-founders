@props(['label', 'name', 'help' => null, 'required' => false])

<div>
    <label for="{{ $name }}" class="block text-sm font-semibold text-ink/80">
        {{ $label }}
        @if ($required) <span class="text-brand-600">*</span> @endif
    </label>
    <div class="mt-1.5">{{ $slot }}</div>
    @if ($help)
        <p class="mt-1 text-xs text-ink/50">{{ $help }}</p>
    @endif
    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
