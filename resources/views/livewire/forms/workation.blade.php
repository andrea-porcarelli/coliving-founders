@php $inputCls = 'block w-full rounded-lg border-black/15 px-3 py-2.5 border focus:outline-none focus:ring-2 focus:ring-brand-600 focus:border-brand-600 text-sm'; @endphp

<div>
    @if ($submitted)
        <div class="rounded-2xl bg-brand-50 border border-brand-200 p-8 text-center">
            <svg class="mx-auto w-12 h-12 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <h3 class="mt-4 font-display text-2xl text-ink">Request received.</h3>
            <p class="mt-2 text-ink/70">We will be in touch shortly with a tailored proposal.</p>
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
                <x-form-field label="Company name" name="company_name" required>
                    <input type="text" wire:model="company_name" id="company_name" class="{{ $inputCls }}" />
                </x-form-field>

                <x-form-field label="Contact person" name="contact_person" required>
                    <input type="text" wire:model="contact_person" id="contact_person" class="{{ $inputCls }}" />
                </x-form-field>

                <x-form-field label="Email" name="email" required>
                    <input type="email" wire:model="email" id="email" class="{{ $inputCls }}" />
                </x-form-field>

                <x-form-field label="Number of employees" name="employees" required>
                    <input type="number" min="1" wire:model="employees" id="employees" class="{{ $inputCls }}" />
                </x-form-field>

                <x-form-field label="Duration" name="duration" required help="e.g. 1 month, 3 months">
                    <input type="text" wire:model="duration" id="duration" class="{{ $inputCls }}" />
                </x-form-field>

                <x-form-field label="Preferred locations" name="preferred_locations" help="e.g. Palermo, Algarve">
                    <input type="text" wire:model="preferred_locations" id="preferred_locations" class="{{ $inputCls }}" />
                </x-form-field>
            </div>

            <x-form-field label="Notes" name="notes">
                <textarea wire:model="notes" id="notes" rows="4" class="{{ $inputCls }}"></textarea>
            </x-form-field>

            <button type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 rounded-full bg-brand-600 px-7 py-3 text-sm font-semibold text-paper hover:bg-brand-700 disabled:opacity-60 transition">
                <span wire:loading.remove wire:target="submit">Request a plan</span>
                <span wire:loading wire:target="submit">Sending…</span>
            </button>
        </form>
    @endif
</div>
