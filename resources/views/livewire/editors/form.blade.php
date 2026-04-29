<div class="space-y-4">
    <x-editor-field label="Form" help="Form Livewire components arrive in Phase 5.">
        <select wire:model="editingContent.form_id" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600">
            <option value="contact">Contact</option>
            <option value="workation">Workation Plan</option>
            <option value="partner">Become a Partner</option>
        </select>
    </x-editor-field>

    <x-editor-field label="Title (optional)">
        <input type="text" wire:model="editingContent.title" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600" />
    </x-editor-field>

    <x-editor-field label="Intro (optional)">
        <textarea wire:model="editingContent.intro" rows="2" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600"></textarea>
    </x-editor-field>
</div>
