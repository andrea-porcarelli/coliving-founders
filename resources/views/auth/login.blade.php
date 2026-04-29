<x-layouts.app title="Sign in — Coliving Founders" :description="null">
    <section class="min-h-[70vh] flex items-center">
        <div class="w-full max-w-md mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="font-display text-4xl tracking-tight">Sign in</h1>
                <p class="mt-2 text-sm text-ink/60">Admin access only.</p>
            </div>

            <form method="POST" action="{{ route('login.store') }}" class="mt-10 rounded-2xl border border-black/10 p-8 shadow-sm bg-paper space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-ink/80">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        autocomplete="email"
                        required
                        autofocus
                        value="{{ old('email') }}"
                        class="mt-1.5 block w-full rounded-lg border-black/15 focus:border-brand-600 focus:ring-brand-600 px-3 py-2.5 border focus:outline-none focus:ring-2 focus:ring-offset-0"
                    />
                    @error('email')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-ink/80">Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="current-password"
                        required
                        class="mt-1.5 block w-full rounded-lg border-black/15 focus:border-brand-600 focus:ring-brand-600 px-3 py-2.5 border focus:outline-none focus:ring-2 focus:ring-offset-0"
                    />
                </div>

                <label class="flex items-center gap-2 text-sm text-ink/70">
                    <input type="checkbox" name="remember" class="rounded border-black/20 text-brand-600 focus:ring-brand-600" />
                    Remember me
                </label>

                <button type="submit" class="w-full rounded-full bg-brand-600 px-5 py-3 text-sm font-semibold text-paper hover:bg-brand-700 transition">
                    Sign in
                </button>
            </form>

            <p class="mt-6 text-center text-xs text-ink/40">
                <a href="/" class="hover:text-brand-600">← Back to site</a>
            </p>
        </div>
    </section>
</x-layouts.app>
