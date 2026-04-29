@php
    $bgValue = data_get($this->editingStyle, 'bg', '');
    $compactValue = (bool) data_get($this->editingStyle, 'compact');
@endphp

<details class="rounded-xl border border-black/10 p-4 group">
    <summary class="cursor-pointer list-none flex items-center justify-between text-sm font-semibold text-ink/80">
        <span>Style</span>
        <svg class="w-4 h-4 text-ink/40 group-open:rotate-180 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </summary>

    <div class="mt-4 space-y-4">
        <x-editor-field label="Background">
            <select wire:model.live="editingStyle.bg" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600">
                <option value="">Default (white)</option>
                <option value="tint">Tint (light brand)</option>
                <option value="brand">Brand (solid blue)</option>
                <option value="dark">Dark (navy)</option>
            </select>
        </x-editor-field>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" wire:model.live="editingStyle.compact" class="rounded border-black/20 text-brand-600 focus:ring-brand-600" />
            Compact padding
        </label>
    </div>
</details>
