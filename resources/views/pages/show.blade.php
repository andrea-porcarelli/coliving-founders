@php
    $editing = auth()->check() && request()->query('preview') !== 'guest';

    $schemaBuilder = app(\App\Services\SchemaBuilder::class);
    $crumbs = $page->slug === 'home'
        ? null
        : $schemaBuilder->breadcrumbs([
            ['Home', url('/')],
            [$page->title, $schemaBuilder->canonicalForPage($page)],
        ]);

    $schemaHtml = $schemaBuilder->renderAll(array_filter([
        $schemaBuilder->webPage($page),
        $crumbs,
    ]));
@endphp

<x-layouts.app
    :title="$page->seoTitle()"
    :description="$page->seoDescription()"
    :canonical="$schemaBuilder->canonicalForPage($page)"
    :og-image="data_get($page->seo, 'og_image')"
    :robots="data_get($page->seo, 'robots')"
    :schema="$schemaHtml"
    :hide-header="$editing"
>
    @if ($editing)
        <livewire:page-editor :page="$page" />
    @else
        @foreach ($page->publishedSections as $section)
            <x-blocks.render :section="$section" />
        @endforeach
    @endif

    <x-become-a-partner-cta />
</x-layouts.app>
