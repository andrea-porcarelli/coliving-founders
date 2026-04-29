@php
    $editing = auth()->check() && request()->query('preview') !== 'guest';
@endphp

<x-layouts.app :title="$page->seoTitle()" :description="$page->seoDescription()">
    @if ($editing)
        <livewire:page-editor :page="$page" />
    @else
        @foreach ($page->publishedSections as $section)
            <x-blocks.render :section="$section" />
        @endforeach
    @endif

    <x-become-a-partner-cta />
</x-layouts.app>
