@php
    $section = \App\Models\Section::find($this->editingSectionId);
    $currentUrl = $section?->imageUrl('image', 'md');
@endphp

<div class="space-y-4">
    <x-image-uploader collection="image" :current-url="$currentUrl" label="Image" />

    <x-editor-field label="Caption (optional)">
        <input type="text" wire:model="editingContent.caption" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600" />
    </x-editor-field>

    <x-editor-field label="Alt text" help="Important for accessibility and SEO. Describe what's in the image.">
        <input type="text" wire:model="editingContent.alt" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600" />
    </x-editor-field>
</div>
