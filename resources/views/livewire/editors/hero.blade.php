@php
    $section = \App\Models\Section::find($this->editingSectionId);
    $bgUrl = $section?->imageUrl('bg', 'md');
@endphp

<div class="space-y-4">
    <x-editor-field label="Title">
        <input type="text" wire:model="editingContent.title" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600" />
    </x-editor-field>

    <x-editor-field label="Title highlight" help="Appears below the title in brand color (optional).">
        <input type="text" wire:model="editingContent.title_highlight" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600" />
    </x-editor-field>

    <x-editor-field label="Subtitle">
        <textarea wire:model="editingContent.subtitle" rows="3" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600"></textarea>
    </x-editor-field>

    <x-image-uploader collection="bg" :current-url="$bgUrl" label="Background image (optional)" />

    <div>
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold uppercase tracking-widest text-ink/60">Call to actions</span>
            <button type="button" wire:click="addItem('ctas')" class="text-xs font-semibold text-brand-600 hover:text-brand-700">+ Add CTA</button>
        </div>
        <div class="mt-2 space-y-3">
            @foreach (data_get($editingContent, 'ctas', []) as $i => $cta)
                <div wire:key="cta-{{ $i }}" class="rounded-lg border border-black/10 p-3 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-ink/50">CTA #{{ $i + 1 }}</span>
                        <button type="button" wire:click="removeItem('ctas', {{ $i }})" class="text-xs text-red-600 hover:text-red-700">Remove</button>
                    </div>
                    <input type="text" wire:model="editingContent.ctas.{{ $i }}.label" placeholder="Label" class="block w-full rounded-lg border-black/15 px-3 py-2 border text-sm" />
                    <input type="text" wire:model="editingContent.ctas.{{ $i }}.href" placeholder="URL or anchor (e.g. /about, #contact)" class="block w-full rounded-lg border-black/15 px-3 py-2 border text-sm" />
                    <select wire:model="editingContent.ctas.{{ $i }}.style" class="block w-full rounded-lg border-black/15 px-3 py-2 border text-sm">
                        <option value="primary">Primary (filled)</option>
                        <option value="secondary">Secondary (outline)</option>
                    </select>
                </div>
            @endforeach
        </div>
    </div>
</div>
