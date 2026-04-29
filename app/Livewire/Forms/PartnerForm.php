<?php

namespace App\Livewire\Forms;

use App\Livewire\Concerns\HandlesFormSubmission;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PartnerForm extends Component
{
    use HandlesFormSubmission;

    #[Validate('required|string|max:120')]
    public string $coliving_name = '';

    #[Validate('required|string|max:120')]
    public string $founder_name = '';

    #[Validate('required|email|max:160')]
    public string $email = '';

    #[Validate('required|string|max:160')]
    public string $location = '';

    #[Validate('nullable|url|max:240')]
    public string $website = '';

    #[Validate('nullable|integer|min:1|max:9999')]
    public ?int $rooms = null;

    #[Validate('required|string|max:1500')]
    public string $description = '';

    #[Validate('required|string|max:1500')]
    public string $why_join = '';

    protected function formType(): string
    {
        return 'partner';
    }

    public function submit(): void
    {
        $this->generalError = null;
        $validated = $this->validate();
        $this->persistSubmission($validated);
    }

    public function render()
    {
        return view('livewire.forms.partner');
    }
}
