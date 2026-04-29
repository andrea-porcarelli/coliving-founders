<div class="space-y-4">
    <x-editor-field label="Email">
        <input type="email" wire:model="editingContent.email" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600" />
    </x-editor-field>

    <x-editor-field label="Phone">
        <input type="text" wire:model="editingContent.phone" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600" />
    </x-editor-field>
</div>
