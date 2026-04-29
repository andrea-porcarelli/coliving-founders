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
