@props(['section'])

@php
    $component = 'blocks.' . str_replace('_', '-', $section->type);
@endphp

<x-dynamic-component :component="$component" :section="$section" />
