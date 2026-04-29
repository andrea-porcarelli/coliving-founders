<div class="space-y-4">
    <x-editor-field label="Title (optional)">
        <input type="text" wire:model="editingContent.title" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600" />
    </x-editor-field>

    <x-editor-field label="Intro (optional)">
        <textarea wire:model="editingContent.intro" rows="2" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600"></textarea>
    </x-editor-field>

    <x-editor-field label="Columns">
        <select wire:model="editingContent.columns" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600">
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select>
    </x-editor-field>

    <div>
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold uppercase tracking-widest text-ink/60">Features</span>
            <button type="button" wire:click="addItem('items')" class="text-xs font-semibold text-brand-600 hover:text-brand-700">+ Add feature</button>
        </div>
        <div class="mt-2 space-y-3">
            @foreach (data_get($editingContent, 'items', []) as $i => $item)
                <div wire:key="feat-{{ $i }}" class="rounded-lg border border-black/10 p-3 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-ink/50">Item #{{ $i + 1 }}</span>
                        <div class="flex gap-1">
                            <button type="button" wire:click="moveItem('items', {{ $i }}, 'up')" class="text-xs px-2 py-1 hover:bg-black/5 rounded" {{ $loop->first ? 'disabled' : '' }}>↑</button>
                            <button type="button" wire:click="moveItem('items', {{ $i }}, 'down')" class="text-xs px-2 py-1 hover:bg-black/5 rounded" {{ $loop->last ? 'disabled' : '' }}>↓</button>
                            <button type="button" wire:click="removeItem('items', {{ $i }})" class="text-xs px-2 py-1 text-red-600 hover:bg-red-50 rounded">×</button>
                        </div>
                    </div>
                    <input type="text" wire:model="editingContent.items.{{ $i }}.title" placeholder="Title" class="block w-full rounded-lg border-black/15 px-3 py-2 border text-sm" />
                    <textarea wire:model="editingContent.items.{{ $i }}.text" placeholder="Text" rows="2" class="block w-full rounded-lg border-black/15 px-3 py-2 border text-sm"></textarea>
                </div>
            @endforeach
        </div>
    </div>
</div>
