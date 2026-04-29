<x-layouts.app title="Submissions — Admin" :description="null" :hide-header="true">
    <div class="bg-brand-900 text-paper">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-12 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="font-display tracking-wide text-base">ADMIN · SUBMISSIONS</span>
            </div>
            <div class="flex items-center gap-2">
                <a href="/" class="px-3 py-1.5 rounded-full text-xs hover:bg-paper/10">← Back to site</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-full text-xs hover:bg-paper/10">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if (session('flash'))
            <p class="mb-6 rounded-lg bg-brand-50 border border-brand-200 px-4 py-3 text-sm text-brand-800">{{ session('flash') }}</p>
        @endif

        <div class="flex flex-wrap items-center gap-2">
            @php
                $tabs = [
                    null => ['label' => 'All', 'count' => $counts->sum()],
                    'partner' => ['label' => 'Become a Partner', 'count' => $counts['partner'] ?? 0],
                    'workation' => ['label' => 'Workation', 'count' => $counts['workation'] ?? 0],
                    'contact' => ['label' => 'Contact', 'count' => $counts['contact'] ?? 0],
                ];
            @endphp
            @foreach ($tabs as $key => $tab)
                @php $isActive = $activeType === $key; @endphp
                <a href="{{ route('admin.submissions.index', $key ? ['type' => $key] : []) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm transition {{ $isActive ? 'bg-brand-600 text-paper' : 'bg-brand-50 text-ink/80 hover:bg-brand-100' }}">
                    {{ $tab['label'] }}
                    <span class="text-xs {{ $isActive ? 'text-paper/80' : 'text-ink/50' }}">{{ $tab['count'] }}</span>
                </a>
            @endforeach
        </div>

        <div class="mt-8 overflow-hidden rounded-2xl border border-black/10">
            <table class="w-full text-sm">
                <thead class="bg-brand-50/50 text-ink/60">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold uppercase tracking-widest text-xs">Received</th>
                        <th class="text-left px-4 py-3 font-semibold uppercase tracking-widest text-xs">Type</th>
                        <th class="text-left px-4 py-3 font-semibold uppercase tracking-widest text-xs">Summary</th>
                        <th class="text-right px-4 py-3 font-semibold uppercase tracking-widest text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5">
                    @forelse ($submissions as $sub)
                        @php
                            $payload = $sub->payload ?? [];
                            $name = $payload['founder_name']
                                ?? $payload['contact_person']
                                ?? $payload['name']
                                ?? '—';
                            $email = $payload['email'] ?? '—';
                        @endphp
                        <tr class="hover:bg-brand-50/30">
                            <td class="px-4 py-3 text-ink/70">{{ $sub->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-0.5 rounded-full bg-brand-100 text-brand-700 text-xs font-mono uppercase">{{ $sub->type }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $name }}</div>
                                <div class="text-xs text-ink/50">{{ $email }}</div>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.submissions.show', $sub) }}" class="inline-block px-3 py-1.5 rounded-full text-xs font-semibold bg-brand-600 text-paper hover:bg-brand-700">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center text-ink/50">No submissions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $submissions->links() }}
        </div>
    </div>
</x-layouts.app>
