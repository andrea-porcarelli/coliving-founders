@php $inputCls = 'block w-full rounded-lg border-black/15 px-3 py-2.5 border focus:outline-none focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm'; @endphp

<div>
    @if ($submitted)
        <div class="rounded-2xl bg-brand-50 border border-brand-200 p-8 text-center">
            <svg class="mx-auto w-12 h-12 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <h3 class="mt-4 font-display text-2xl text-ink">Message sent.</h3>
            <p class="mt-2 text-ink/70">Thanks for reaching out — we will reply soon.</p>
        </div>
    @else
        <form wire:submit="submit" class="space-y-5">
            @if ($generalError)
                <p class="rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">{{ $generalError }}</p>
            @endif

            <div style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden" aria-hidden="true">
                <label for="hp_website">Website</label>
                <input type="text" id="hp_website" wire:model="hp_website" tabindex="-1" autocomplete="off" />
            </div>

            <div class="grid sm:grid-cols-2 gap-5">
                <x-form-field label="Your name" name="name" required>
                    <input type="text" wire:model="name" id="name" class="{{ $inputCls }}" />
                </x-form-field>

                <x-form-field label="Email" name="email" required>
                    <input type="email" wire:model="email" id="email" class="{{ $inputCls }}" />
                </x-form-field>
            </div>

            <x-form-field label="Subject" name="subject">
                <input type="text" wire:model="subject" id="subject" class="{{ $inputCls }}" />
            </x-form-field>

            <x-form-field label="Message" name="message" required>
                <textarea wire:model="message" id="message" rows="5" class="{{ $inputCls }}"></textarea>
            </x-form-field>

            <button type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 rounded-full bg-brand-600 px-7 py-3 text-sm font-semibold text-paper hover:bg-brand-700 disabled:opacity-60 transition">
                <span wire:loading.remove wire:target="submit">Send message</span>
                <span wire:loading wire:target="submit">Sending…</span>
            </button>
        </form>
    @endif
</div>
