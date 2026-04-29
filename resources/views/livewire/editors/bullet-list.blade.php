<div class="space-y-4">
    <x-editor-field label="Title (optional)">
        <input type="text" wire:model="editingContent.title" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600" />
    </x-editor-field>

    <x-editor-field label="Intro (optional)">
        <textarea wire:model="editingContent.intro" rows="2" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600"></textarea>
    </x-editor-field>

    <div>
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold uppercase tracking-widest text-ink/60">Items</span>
            <button type="button" wire:click="addItem('items')" class="text-xs font-semibold text-brand-600 hover:text-brand-700">+ Add item</button>
        </div>
        <ul class="mt-2 space-y-2">
            @foreach (data_get($editingContent, 'items', []) as $i => $item)
                <li wire:key="item-{{ $i }}" class="flex items-center gap-2">
                    <input type="text" wire:model="editingContent.items.{{ $i }}" class="flex-1 rounded-lg border-black/15 px-3 py-2 border text-sm" />
                    <button type="button" wire:click="moveItem('items', {{ $i }}, 'up')" class="p-1.5 rounded-full hover:bg-black/5 text-ink/50" {{ $loop->first ? 'disabled' : '' }}>↑</button>
                    <button type="button" wire:click="moveItem('items', {{ $i }}, 'down')" class="p-1.5 rounded-full hover:bg-black/5 text-ink/50" {{ $loop->last ? 'disabled' : '' }}>↓</button>
                    <button type="button" wire:click="removeItem('items', {{ $i }})" class="p-1.5 rounded-full hover:bg-red-50 text-red-600">×</button>
                </li>
            @endforeach
        </ul>
    </div>
</div>
