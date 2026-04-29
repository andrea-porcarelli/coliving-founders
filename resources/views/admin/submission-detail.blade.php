<x-layouts.app :title="'Submission #' . $submission->id . ' — Admin'" :description="null" :hide-header="true">
    <div class="bg-brand-900 text-paper">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-12 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.submissions.index') }}" class="px-3 py-1.5 rounded-full text-xs hover:bg-paper/10">← Submissions</a>
            </div>
            <div class="flex items-center gap-2">
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-full text-xs hover:bg-paper/10">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-center gap-3">
            <span class="inline-block px-2.5 py-1 rounded-full bg-brand-100 text-brand-700 text-xs font-mono uppercase">{{ $submission->type }}</span>
            <span class="text-sm text-ink/60">{{ $submission->created_at->format('Y-m-d H:i') }} UTC</span>
        </div>

        <h1 class="mt-4 font-display text-3xl">Submission #{{ $submission->id }}</h1>

        <dl class="mt-8 divide-y divide-black/10 rounded-2xl border border-black/10 overflow-hidden">
            @foreach (($submission->payload ?? []) as $field => $value)
                <div class="grid sm:grid-cols-3 gap-3 px-5 py-4 hover:bg-brand-50/30">
                    <dt class="text-xs font-semibold uppercase tracking-widest text-ink/50 self-start mt-0.5">
                        {{ \Illuminate\Support\Str::title(str_replace('_', ' ', $field)) }}
                    </dt>
                    <dd class="sm:col-span-2 whitespace-pre-wrap break-words">
                        @if (filter_var($value, FILTER_VALIDATE_EMAIL))
                            <a href="mailto:{{ $value }}" class="text-brand-600 hover:underline">{{ $value }}</a>
                        @elseif (filter_var($value, FILTER_VALIDATE_URL))
                            <a href="{{ $value }}" target="_blank" rel="noopener" class="text-brand-600 hover:underline">{{ $value }}</a>
                        @else
                            {{ is_array($value) ? json_encode($value) : ($value ?: '—') }}
                        @endif
                    </dd>
                </div>
            @endforeach
        </dl>

        <div class="mt-8 flex items-center justify-between text-xs text-ink/50">
            <span>IP: {{ $submission->ip ?? '—' }}</span>
            <form method="POST" action="{{ route('admin.submissions.destroy', $submission) }}" onsubmit="return confirm('Delete this submission?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-full text-xs font-semibold text-red-600 hover:bg-red-50">Delete</button>
            </form>
        </div>
    </div>
</x-layouts.app>
