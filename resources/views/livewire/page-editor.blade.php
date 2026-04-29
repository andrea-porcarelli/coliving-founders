@php
    $page = $this->page;
    $sections = $page->sections;
    $blockTypes = $this->availableBlockTypes();
    $editingSection = $this->editingSectionId
        ? $sections->firstWhere('id', $this->editingSectionId)
        : null;
@endphp

<div x-data="{ adderOpen: null }" class="cf-editor-root">
    <div class="fixed top-0 inset-x-0 z-[60] bg-brand-900 text-paper text-sm shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-12 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="font-display tracking-wide text-base">EDIT MODE</span>
                <span class="text-paper/60 hidden sm:inline">·</span>
                <span class="text-paper/85 hidden sm:inline">{{ $page->title }} <span class="text-paper/40">/{{ $page->slug === 'home' ? '' : $page->slug }}</span></span>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" wire:click="startEditingNav"
                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs hover:bg-paper/10">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    Menu
                </button>
                <button type="button" wire:click="startEditingPartners"
                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs hover:bg-paper/10">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Partners
                </button>
                <button type="button" wire:click="startEditingSeo"
                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs hover:bg-paper/10">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    SEO
                </button>
                <a href="{{ url($page->slug === 'home' ? '/' : $page->slug) }}?preview=guest"
                   class="hidden sm:inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs hover:bg-paper/10">
                    Preview as guest →
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-full text-xs hover:bg-paper/10">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <div class="h-12"></div>

    <div>
        <div class="relative">
            <button type="button"
                    @click="adderOpen = adderOpen === 'top' ? null : 'top'"
                    class="block w-full py-3 text-center text-xs font-semibold uppercase tracking-widest text-brand-600 bg-brand-50/50 hover:bg-brand-50 border-y border-dashed border-brand-300/40 transition">
                + Add section at top
            </button>
            <div x-show="adderOpen === 'top'" x-cloak x-transition.opacity
                 @click.outside="adderOpen = null"
                 class="absolute left-1/2 -translate-x-1/2 top-full mt-2 z-30 w-72 rounded-xl bg-paper shadow-xl border border-black/10 p-2 grid grid-cols-2 gap-1">
                @foreach ($blockTypes as $key => $meta)
                    <button type="button"
                            wire:click="addSection('{{ $key }}')"
                            @click="adderOpen = null"
                            class="px-3 py-2 text-left text-sm rounded-lg hover:bg-brand-50 text-ink/80 hover:text-brand-700">
                        {{ $meta['label'] }}
                    </button>
                @endforeach
            </div>
        </div>

        <div x-sortable="$wire.reorder($ids)">
            @foreach ($sections as $section)
                <div class="relative group/block" wire:key="block-{{ $section->id }}" data-section-id="{{ $section->id }}">
                    <x-blocks.render :section="$section" />

                    <div class="absolute inset-0 pointer-events-none ring-2 ring-brand-500/0 group-hover/block:ring-brand-500/40 transition"></div>

                    <button type="button" data-drag-handle
                            title="Drag to reorder"
                            class="absolute top-3 left-3 z-20 p-2 rounded-full bg-paper/95 shadow-lg border border-black/10 opacity-0 group-hover/block:opacity-100 transition cursor-grab active:cursor-grabbing">
                        <svg class="w-4 h-4 text-ink/70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                    </button>

                    <div class="absolute top-3 right-3 z-20 opacity-0 group-hover/block:opacity-100 transition">
                        <div class="flex items-center gap-1 rounded-full bg-paper shadow-lg border border-black/10 p-1">
                            <button type="button"
                                    wire:click="startEditing({{ $section->id }})"
                                    title="Edit"
                                    class="px-2.5 py-1.5 rounded-full text-xs font-semibold bg-brand-600 text-paper hover:bg-brand-700">
                                Edit
                            </button>
                            <button type="button" wire:click="moveSection({{ $section->id }}, 'up')" title="Move up"
                                    class="p-1.5 rounded-full hover:bg-black/5 text-ink/70 disabled:opacity-30" {{ $loop->first ? 'disabled' : '' }}>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                            </button>
                            <button type="button" wire:click="moveSection({{ $section->id }}, 'down')" title="Move down"
                                    class="p-1.5 rounded-full hover:bg-black/5 text-ink/70 disabled:opacity-30" {{ $loop->last ? 'disabled' : '' }}>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <button type="button" wire:click="duplicateSection({{ $section->id }})" title="Duplicate"
                                    class="p-1.5 rounded-full hover:bg-black/5 text-ink/70">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </button>
                            <button type="button"
                                    wire:click="deleteSection({{ $section->id }})"
                                    wire:confirm="Delete this section?"
                                    title="Delete"
                                    class="p-1.5 rounded-full hover:bg-red-50 text-red-600">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/></svg>
                            </button>
                        </div>
                        <div class="mt-1 text-right">
                            <span class="inline-block px-2 py-0.5 rounded-full bg-brand-900/85 text-paper text-[10px] font-mono uppercase tracking-wider">{{ $section->type }}</span>
                        </div>
                    </div>
                </div>

                <div class="relative" wire:key="adder-{{ $section->id }}">
                    <button type="button"
                            @click="adderOpen = adderOpen === {{ $section->id }} ? null : {{ $section->id }}"
                            class="block w-full py-2 text-center text-xs font-semibold uppercase tracking-widest text-brand-600/70 hover:text-brand-700 hover:bg-brand-50/40 transition">
                        + Add section here
                    </button>
                    <div x-show="adderOpen === {{ $section->id }}" x-cloak x-transition.opacity
                         @click.outside="adderOpen = null"
                         class="absolute left-1/2 -translate-x-1/2 top-full mt-1 z-30 w-72 rounded-xl bg-paper shadow-xl border border-black/10 p-2 grid grid-cols-2 gap-1">
                        @foreach ($blockTypes as $key => $meta)
                            <button type="button"
                                    wire:click="addSection('{{ $key }}', {{ $section->id }})"
                                    @click="adderOpen = null"
                                    class="px-3 py-2 text-left text-sm rounded-lg hover:bg-brand-50 text-ink/80 hover:text-brand-700">
                                {{ $meta['label'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        @if ($sections->isEmpty())
            <div class="py-32 text-center text-ink/50">
                <p class="text-lg">This page has no sections yet.</p>
                <p class="mt-2 text-sm">Use "Add section at top" to start.</p>
            </div>
        @endif
    </div>

    @if ($editingSection)
        <div class="fixed inset-0 z-[70] flex justify-end">
            <div class="absolute inset-0 bg-ink/40" wire:click="cancelEditing"></div>

            <aside class="relative z-10 w-full sm:max-w-lg bg-paper h-full flex flex-col shadow-2xl">
                <header class="flex items-center justify-between px-6 py-4 border-b border-black/10">
                    <div>
                        <p class="text-xs font-mono uppercase tracking-wider text-brand-600">{{ $editingSection->type }}</p>
                        <h2 class="font-display text-xl tracking-tight">{{ $blockTypes[$editingSection->type]['label'] ?? 'Edit' }}</h2>
                    </div>
                    <button type="button" wire:click="cancelEditing" class="p-2 rounded-full hover:bg-black/5">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </header>

                <div class="flex-1 overflow-y-auto px-6 py-6 space-y-6">
                    @include('livewire.editors.' . str_replace('_', '-', $editingSection->type))
                    @include('livewire.editors.style')
                </div>

                <footer class="px-6 py-4 border-t border-black/10 flex items-center justify-end gap-2 bg-brand-50/30">
                    <button type="button" wire:click="cancelEditing" class="px-4 py-2 rounded-full text-sm font-medium text-ink/70 hover:bg-black/5">
                        Cancel
                    </button>
                    <button type="button" wire:click="saveEditing" class="px-5 py-2 rounded-full text-sm font-semibold bg-brand-600 text-paper hover:bg-brand-700">
                        Save changes
                    </button>
                </footer>
            </aside>
        </div>
    @endif

    @if ($editingPartnersList)
        @php
            $partners = \App\Models\Partner::orderBy('sort_order')->get();
            $editingPartner = $this->editingPartnerId
                ? $partners->firstWhere('id', $this->editingPartnerId)
                : null;
        @endphp

        <div class="fixed inset-0 z-[70] flex justify-end">
            <div class="absolute inset-0 bg-ink/40" wire:click="cancelEditingPartners"></div>

            <aside class="relative z-10 w-full sm:max-w-2xl bg-paper h-full flex flex-col shadow-2xl">
                @if ($editingPartner || $partnerIsNew)
                    <header class="flex items-center justify-between px-6 py-4 border-b border-black/10">
                        <div class="flex items-center gap-3">
                            <button type="button" wire:click="cancelPartnerForm" class="p-1.5 rounded-full hover:bg-black/5" title="Back to list">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <div>
                                <p class="text-xs font-mono uppercase tracking-wider text-brand-600">{{ $partnerIsNew ? 'new partner' : 'edit partner' }}</p>
                                <h2 class="font-display text-xl tracking-tight">{{ $partnerForm['name'] ?: 'New coliving' }}</h2>
                            </div>
                        </div>
                        <button type="button" wire:click="cancelEditingPartners" class="p-2 rounded-full hover:bg-black/5">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </header>

                    <div class="flex-1 overflow-y-auto px-6 py-6 space-y-5">
                        @if ($editingPartner)
                            <x-image-uploader collection="logo" :current-url="$editingPartner->logoUrl('thumb')" label="Logo" />
                        @else
                            <p class="rounded-lg bg-brand-50 border border-brand-200 px-4 py-3 text-xs text-brand-800">
                                Save the partner first, then come back to upload a logo.
                            </p>
                        @endif

                        <x-editor-field label="Name" name="partnerForm.name">
                            <input type="text" wire:model="partnerForm.name" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600" />
                        </x-editor-field>

                        <x-editor-field label="URL slug" name="partnerForm.slug" help="Leave blank to auto-generate from name. Used in /partners/{slug}.">
                            <input type="text" wire:model="partnerForm.slug" placeholder="auto" class="block w-full rounded-lg border-black/15 px-3 py-2 border font-mono text-sm focus:outline-none focus:ring-2 focus:ring-brand-600" />
                        </x-editor-field>

                        <x-editor-field label="Location" name="partnerForm.location" help="City, country">
                            <input type="text" wire:model="partnerForm.location" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600" />
                        </x-editor-field>

                        <x-editor-field label="Description" name="partnerForm.description">
                            <textarea wire:model="partnerForm.description" rows="4" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600"></textarea>
                        </x-editor-field>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <x-editor-field label="Website" name="partnerForm.website">
                                <input type="url" wire:model="partnerForm.website" placeholder="https://" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600" />
                            </x-editor-field>

                            <x-editor-field label="Number of rooms" name="partnerForm.rooms">
                                <input type="number" min="1" wire:model="partnerForm.rooms" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600" />
                            </x-editor-field>
                        </div>

                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" wire:model="partnerForm.published" class="rounded border-black/20 text-brand-600 focus:ring-brand-600" />
                            Published (visible on the site)
                        </label>
                    </div>

                    <footer class="px-6 py-4 border-t border-black/10 flex items-center justify-end gap-2 bg-brand-50/30">
                        <button type="button" wire:click="cancelPartnerForm" class="px-4 py-2 rounded-full text-sm font-medium text-ink/70 hover:bg-black/5">Back</button>
                        <button type="button" wire:click="savePartner" class="px-5 py-2 rounded-full text-sm font-semibold bg-brand-600 text-paper hover:bg-brand-700">Save partner</button>
                    </footer>
                @else
                    <header class="flex items-center justify-between px-6 py-4 border-b border-black/10">
                        <div>
                            <p class="text-xs font-mono uppercase tracking-wider text-brand-600">partners</p>
                            <h2 class="font-display text-xl tracking-tight">Coliving network</h2>
                        </div>
                        <button type="button" wire:click="cancelEditingPartners" class="p-2 rounded-full hover:bg-black/5">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </header>

                    <div class="flex-1 overflow-y-auto px-6 py-6">
                        <ul class="space-y-3">
                            @foreach ($partners as $p)
                                <li wire:key="partner-{{ $p->id }}" class="rounded-xl border border-black/10 p-4 hover:border-brand-300 transition flex items-center gap-4">
                                    <div class="flex-none w-16 h-16 rounded-lg bg-brand-50/50 flex items-center justify-center overflow-hidden">
                                        @if ($p->logoUrl('thumb'))
                                            <img src="{{ $p->logoUrl('thumb') }}" alt="{{ $p->name }} logo" class="max-h-14 w-auto" />
                                        @else
                                            <span class="font-display text-xl text-brand-600/40">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($p->name, 0, 1)) }}</span>
                                        @endif
                                    </div>

                                    <button type="button" wire:click="editPartner({{ $p->id }})" class="flex-1 text-left">
                                        <div class="flex items-center gap-2">
                                            <span class="font-display text-base">{{ $p->name }}</span>
                                            @unless ($p->published)
                                                <span class="text-[10px] uppercase tracking-widest text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded">draft</span>
                                            @endunless
                                        </div>
                                        <div class="text-sm text-ink/60 mt-0.5">{{ $p->location }}</div>
                                    </button>

                                    <div class="flex items-center gap-1">
                                        <button type="button" wire:click="movePartner({{ $p->id }}, 'up')" title="Move up"
                                                class="p-1.5 rounded-full hover:bg-black/5 text-ink/70 disabled:opacity-30" {{ $loop->first ? 'disabled' : '' }}>
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                        </button>
                                        <button type="button" wire:click="movePartner({{ $p->id }}, 'down')" title="Move down"
                                                class="p-1.5 rounded-full hover:bg-black/5 text-ink/70 disabled:opacity-30" {{ $loop->last ? 'disabled' : '' }}>
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                        <button type="button" wire:click="deletePartner({{ $p->id }})"
                                                wire:confirm="Delete this partner permanently?"
                                                title="Delete"
                                                class="p-1.5 rounded-full hover:bg-red-50 text-red-600">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/></svg>
                                        </button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <button type="button" wire:click="addNewPartner"
                                class="mt-4 w-full py-3 rounded-xl border-2 border-dashed border-brand-300/50 text-sm font-semibold text-brand-600 hover:bg-brand-50/40 transition">
                            + Add new partner
                        </button>

                        @if ($partners->isEmpty())
                            <p class="mt-6 text-center text-sm text-ink/40">No partners yet. Click "Add new partner" to start.</p>
                        @endif
                    </div>
                @endif
            </aside>
        </div>
    @endif

    @if ($editingNav)
        <div class="fixed inset-0 z-[70] flex justify-end">
            <div class="absolute inset-0 bg-ink/40" wire:click="cancelEditingNav"></div>

            <aside class="relative z-10 w-full sm:max-w-2xl bg-paper h-full flex flex-col shadow-2xl">
                <header class="flex items-center justify-between px-6 py-4 border-b border-black/10">
                    <div>
                        <p class="text-xs font-mono uppercase tracking-wider text-brand-600">navigation</p>
                        <h2 class="font-display text-xl tracking-tight">Menu items</h2>
                    </div>
                    <button type="button" wire:click="cancelEditingNav" class="p-2 rounded-full hover:bg-black/5">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </header>

                <div class="flex-1 overflow-y-auto px-6 py-6">
                    <p class="text-sm text-ink/60">Edit the public site header. The "Become a Partner" CTA stays fixed.</p>

                    <ul class="mt-4 space-y-3">
                        @foreach ($navForm as $i => $row)
                            <li wire:key="nav-{{ $i }}-{{ $row['id'] ?? 'new' }}" class="rounded-xl border border-black/10 p-4 bg-paper">
                                <div class="grid sm:grid-cols-2 gap-3">
                                    <label class="block">
                                        <span class="text-xs font-semibold uppercase tracking-widest text-ink/60">Label</span>
                                        <input type="text" wire:model="navForm.{{ $i }}.label"
                                               class="mt-1 block w-full rounded-lg border-black/15 px-3 py-2 border text-sm focus:outline-none focus:ring-2 focus:ring-brand-600" />
                                    </label>
                                    <label class="block">
                                        <span class="text-xs font-semibold uppercase tracking-widest text-ink/60">URL or anchor</span>
                                        <input type="text" wire:model="navForm.{{ $i }}.href" placeholder="/about or https://… or #section"
                                               class="mt-1 block w-full rounded-lg border-black/15 px-3 py-2 border text-sm focus:outline-none focus:ring-2 focus:ring-brand-600" />
                                    </label>
                                </div>

                                <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                                    <div class="flex items-center gap-4 text-sm">
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" wire:model="navForm.{{ $i }}.published" class="rounded border-black/20 text-brand-600 focus:ring-brand-600" />
                                            Published
                                        </label>
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" wire:model="navForm.{{ $i }}.open_in_new_tab" class="rounded border-black/20 text-brand-600 focus:ring-brand-600" />
                                            New tab
                                        </label>
                                    </div>

                                    <div class="flex items-center gap-1">
                                        <button type="button" wire:click="moveNavItemRow({{ $i }}, 'up')" title="Move up"
                                                class="p-1.5 rounded-full hover:bg-black/5 text-ink/70 disabled:opacity-30" {{ $loop->first ? 'disabled' : '' }}>
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                        </button>
                                        <button type="button" wire:click="moveNavItemRow({{ $i }}, 'down')" title="Move down"
                                                class="p-1.5 rounded-full hover:bg-black/5 text-ink/70 disabled:opacity-30" {{ $loop->last ? 'disabled' : '' }}>
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                        <button type="button" wire:click="removeNavItemRow({{ $i }})"
                                                wire:confirm="Remove this menu item?"
                                                title="Remove"
                                                class="p-1.5 rounded-full hover:bg-red-50 text-red-600">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <button type="button" wire:click="addNavItemRow"
                            class="mt-4 w-full py-3 rounded-xl border-2 border-dashed border-brand-300/50 text-sm font-semibold text-brand-600 hover:bg-brand-50/40 transition">
                        + Add menu item
                    </button>

                    @if (empty($navForm))
                        <p class="mt-6 text-center text-sm text-ink/40">No menu items. Click "Add menu item" to start.</p>
                    @endif
                </div>

                <footer class="px-6 py-4 border-t border-black/10 flex items-center justify-end gap-2 bg-brand-50/30">
                    <button type="button" wire:click="cancelEditingNav" class="px-4 py-2 rounded-full text-sm font-medium text-ink/70 hover:bg-black/5">Cancel</button>
                    <button type="button" wire:click="saveNav" class="px-5 py-2 rounded-full text-sm font-semibold bg-brand-600 text-paper hover:bg-brand-700">Save menu</button>
                </footer>
            </aside>
        </div>
    @endif

    @if ($editingSeo)
        <div class="fixed inset-0 z-[70] flex justify-end">
            <div class="absolute inset-0 bg-ink/40" wire:click="cancelEditingSeo"></div>

            <aside class="relative z-10 w-full sm:max-w-lg bg-paper h-full flex flex-col shadow-2xl">
                <header class="flex items-center justify-between px-6 py-4 border-b border-black/10">
                    <div>
                        <p class="text-xs font-mono uppercase tracking-wider text-brand-600">page seo</p>
                        <h2 class="font-display text-xl tracking-tight">SEO &amp; metadata</h2>
                    </div>
                    <button type="button" wire:click="cancelEditingSeo" class="p-2 rounded-full hover:bg-black/5">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </header>

                <div class="flex-1 overflow-y-auto px-6 py-6 space-y-5">
                    <x-editor-field label="Meta title" help="Shown in browser tabs and search results. ~60 chars max.">
                        <input type="text" wire:model="seoForm.title" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600" />
                    </x-editor-field>

                    <x-editor-field label="Meta description" help="Shown in search engine results. ~155 chars max.">
                        <textarea wire:model="seoForm.description" rows="3" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600"></textarea>
                    </x-editor-field>

                    <x-editor-field label="Open Graph image URL" help="Social-share preview image (1200×630 ideal).">
                        <input type="url" wire:model="seoForm.og_image" placeholder="https://…" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600" />
                    </x-editor-field>

                    <x-editor-field label="Canonical URL" help="Leave blank to auto-generate.">
                        <input type="url" wire:model="seoForm.canonical" placeholder="https://colivingfounders.com/…" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600" />
                    </x-editor-field>

                    <x-editor-field label="Robots">
                        <select wire:model="seoForm.robots" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600">
                            <option value="">index, follow (default)</option>
                            <option value="noindex,nofollow">noindex, nofollow</option>
                            <option value="noindex,follow">noindex, follow</option>
                            <option value="index,nofollow">index, nofollow</option>
                        </select>
                    </x-editor-field>

                    <x-editor-field label="Schema.org type" help="Used for JSON-LD structured data.">
                        <select wire:model="seoForm.schema_type" class="block w-full rounded-lg border-black/15 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-brand-600">
                            <option value="">WebPage (default)</option>
                            <option value="WebSite">WebSite (use for home)</option>
                            <option value="AboutPage">AboutPage</option>
                            <option value="ContactPage">ContactPage</option>
                            <option value="CollectionPage">CollectionPage</option>
                        </select>
                    </x-editor-field>
                </div>

                <footer class="px-6 py-4 border-t border-black/10 flex items-center justify-end gap-2 bg-brand-50/30">
                    <button type="button" wire:click="cancelEditingSeo" class="px-4 py-2 rounded-full text-sm font-medium text-ink/70 hover:bg-black/5">Cancel</button>
                    <button type="button" wire:click="saveSeo" class="px-5 py-2 rounded-full text-sm font-semibold bg-brand-600 text-paper hover:bg-brand-700">Save SEO</button>
                </footer>
            </aside>
        </div>
    @endif
</div>
