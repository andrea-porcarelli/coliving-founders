@php
    $partners = \App\Models\Partner::published()->get();
@endphp

<div class="space-y-4">
    <x-editor-field label="Title (optional)">
        <input type="text" wire:model="editingContent.title" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600" />
    </x-editor-field>

    <div>
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold uppercase tracking-widest text-ink/60">Founders</span>
            <button type="button" wire:click="addItem('items')" class="text-xs font-semibold text-brand-600 hover:text-brand-700">+ Add founder</button>
        </div>
        <div class="mt-2 space-y-3">
            @foreach (data_get($editingContent, 'items', []) as $i => $item)
                <div wire:key="founder-{{ $i }}" class="rounded-lg border border-black/10 p-3 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-ink/50">Founder #{{ $i + 1 }}</span>
                        <div class="flex gap-1">
                            <button type="button" wire:click="moveItem('items', {{ $i }}, 'up')" class="text-xs px-2 py-1 hover:bg-black/5 rounded" {{ $loop->first ? 'disabled' : '' }}>↑</button>
                            <button type="button" wire:click="moveItem('items', {{ $i }}, 'down')" class="text-xs px-2 py-1 hover:bg-black/5 rounded" {{ $loop->last ? 'disabled' : '' }}>↓</button>
                            <button type="button" wire:click="removeItem('items', {{ $i }})" class="text-xs px-2 py-1 text-red-600 hover:bg-red-50 rounded">×</button>
                        </div>
                    </div>
                    <input type="text" wire:model="editingContent.items.{{ $i }}.name" placeholder="Name" class="block w-full rounded-lg border-black/15 px-3 py-2 border text-sm" />
                    <input type="text" wire:model="editingContent.items.{{ $i }}.role" placeholder="Role" class="block w-full rounded-lg border-black/15 px-3 py-2 border text-sm" />
                    <select wire:model="editingContent.items.{{ $i }}.partner_slug" class="block w-full rounded-lg border-black/15 px-3 py-2 border text-sm">
                        <option value="">— No partner linked —</option>
                        @foreach ($partners as $p)
                            <option value="{{ $p->slug }}">{{ $p->name }} ({{ $p->location }})</option>
                        @endforeach
                    </select>
                </div>
            @endforeach
        </div>
    </div>
</div>
