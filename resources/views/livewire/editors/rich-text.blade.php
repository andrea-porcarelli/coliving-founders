<div class="space-y-4">
    <x-editor-field label="Title (optional)">
        <input type="text" wire:model="editingContent.title" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600" />
    </x-editor-field>

    <x-editor-field label="Body (HTML allowed)" help="You can use <p>, <strong>, <em>, <a> tags.">
        <textarea wire:model="editingContent.body_html" rows="8" class="block w-full rounded-lg border-black/15 px-3 py-2 border font-mono text-sm focus:outline-none focus:ring-2 focus:ring-brand-600"></textarea>
    </x-editor-field>

    <x-editor-field label="Alignment">
        <select wire:model="editingContent.align" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600">
            <option value="left">Left</option>
            <option value="center">Center</option>
        </select>
    </x-editor-field>
</div>
