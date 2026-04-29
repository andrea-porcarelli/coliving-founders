<div class="space-y-4">
    <x-editor-field label="Title">
        <input type="text" wire:model="editingContent.title" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600" />
    </x-editor-field>

    <x-editor-field label="Text (optional)">
        <textarea wire:model="editingContent.text" rows="2" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600"></textarea>
    </x-editor-field>

    <div class="rounded-lg border border-black/10 p-3 space-y-2">
        <span class="text-xs font-semibold uppercase tracking-widest text-ink/50">Button</span>
        <input type="text" wire:model="editingContent.button.label" placeholder="Label" class="block w-full rounded-lg border-black/15 px-3 py-2 border text-sm" />
        <input type="text" wire:model="editingContent.button.href" placeholder="URL or anchor" class="block w-full rounded-lg border-black/15 px-3 py-2 border text-sm" />
    </div>
</div>
