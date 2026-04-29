<?php

namespace App\Livewire\Forms;

use App\Livewire\Concerns\HandlesFormSubmission;
use Livewire\Attributes\Validate;
use Livewire\Component;

class WorkationForm extends Component
{
    use HandlesFormSubmission;

    #[Validate('required|string|max:120')]
    public string $company_name = '';

    #[Validate('required|string|max:120')]
    public string $contact_person = '';

    #[Validate('required|email|max:160')]
    public string $email = '';

    #[Validate('required|integer|min:1|max:5000')]
    public ?int $employees = null;

    #[Validate('required|string|max:80')]
    public string $duration = '';

    #[Validate('nullable|string|max:240')]
    public string $preferred_locations = '';

    #[Validate('nullable|string|max:1500')]
    public string $notes = '';

    protected function formType(): string
    {
        return 'workation';
    }

    public function submit(): void
    {
        $this->generalError = null;
        $validated = $this->validate();
        $this->persistSubmission($validated);
    }

    public function render()
    {
        return view('livewire.forms.workation');
    }
}
