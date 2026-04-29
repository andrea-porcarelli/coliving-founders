@props(['collection' => 'image', 'currentUrl' => null, 'label' => 'Image'])

<div class="space-y-3">
    <span class="text-xs font-semibold uppercase tracking-widest text-ink/60">{{ $label }}</span>

    @if ($currentUrl)
        <div class="relative rounded-lg overflow-hidden border border-black/10 bg-brand-50/30">
            <img src="{{ $currentUrl }}" alt="{{ $label }}" class="w-full h-40 object-cover" />
            <button type="button"
                    wire:click="removeImage('{{ $collection }}')"
                    wire:confirm="Remove this image?"
                    class="absolute top-2 right-2 px-2.5 py-1 rounded-full bg-paper/95 text-xs font-semibold text-red-600 hover:bg-paper shadow">
                Remove
            </button>
        </div>
    @endif

    <div>
        <input type="file"
               wire:model="pendingImage"
               accept="image/*"
               class="block w-full text-sm text-ink/70 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100" />

        <div wire:loading wire:target="pendingImage" class="mt-2 text-xs text-ink/50">Uploading…</div>
        @error('pendingImage') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div wire:show="pendingImage">
        <button type="button"
                wire:click="uploadImage('{{ $collection }}')"
                wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand-600 text-paper text-sm font-semibold hover:bg-brand-700 disabled:opacity-50">
            <svg wire:loading wire:target="uploadImage" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"/><path d="M12 2a10 10 0 0110 10" stroke-linecap="round"/></svg>
            <span wire:loading.remove wire:target="uploadImage">Save image</span>
            <span wire:loading wire:target="uploadImage">Saving…</span>
        </button>
    </div>
</div>
