@php $inputCls = 'block w-full rounded-lg border-black/15 px-3 py-2.5 border focus:outline-none focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm'; @endphp

<div>
    @if ($submitted)
        <div class="rounded-2xl bg-brand-50 border border-brand-200 p-8 text-center">
            <svg class="mx-auto w-12 h-12 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <h3 class="mt-4 font-display text-2xl text-ink">Thank you for your application.</h3>
            <p class="mt-2 text-ink/70">We will review your submission and get back to you shortly.</p>
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
                <x-form-field label="Coliving name" name="coliving_name" required>
                    <input type="text" wire:model="coliving_name" id="coliving_name" class="{{ $inputCls }}" />
                </x-form-field>

                <x-form-field label="Founder name" name="founder_name" required>
                    <input type="text" wire:model="founder_name" id="founder_name" class="{{ $inputCls }}" />
                </x-form-field>

                <x-form-field label="Email" name="email" required>
                    <input type="email" wire:model="email" id="email" class="{{ $inputCls }}" />
                </x-form-field>

                <x-form-field label="Location" name="location" required help="City, country">
                    <input type="text" wire:model="location" id="location" class="{{ $inputCls }}" />
                </x-form-field>

                <x-form-field label="Website" name="website">
                    <input type="url" wire:model="website" id="website" placeholder="https://" class="{{ $inputCls }}" />
                </x-form-field>

                <x-form-field label="Number of rooms" name="rooms">
                    <input type="number" min="1" wire:model="rooms" id="rooms" class="{{ $inputCls }}" />
                </x-form-field>
            </div>

            <x-form-field label="Description" name="description" required help="Tell us about your space, community, vibe.">
                <textarea wire:model="description" id="description" rows="4" class="{{ $inputCls }}"></textarea>
            </x-form-field>

            <x-form-field label="Why do you want to join COFO?" name="why_join" required>
                <textarea wire:model="why_join" id="why_join" rows="4" class="{{ $inputCls }}"></textarea>
            </x-form-field>

            <button type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 rounded-full bg-brand-600 px-7 py-3 text-sm font-semibold text-paper hover:bg-brand-700 disabled:opacity-60 transition">
                <span wire:loading.remove wire:target="submit">Send application</span>
                <span wire:loading wire:target="submit">Sending…</span>
            </button>
        </form>
    @endif
</div>
