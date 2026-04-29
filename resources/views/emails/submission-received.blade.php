@php
    $labels = [
        'partner' => 'Become a Partner',
        'workation' => 'Workation Plan',
        'contact' => 'Contact',
    ];
    $label = $labels[$submission->type] ?? $submission->type;
@endphp

<x-mail::message>
# New {{ $label }} submission

Received: {{ $submission->created_at->format('Y-m-d H:i') }} UTC
IP: {{ $submission->ip ?? '—' }}

@foreach (($submission->payload ?? []) as $field => $value)
**{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $field)) }}:**
{{ is_array($value) ? json_encode($value) : ($value ?: '—') }}

@endforeach

<x-mail::button :url="url('/admin/submissions')">
View all submissions
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
