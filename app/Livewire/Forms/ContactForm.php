<?php

namespace App\Livewire\Forms;

use App\Livewire\Concerns\HandlesFormSubmission;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ContactForm extends Component
{
    use HandlesFormSubmission;

    #[Validate('required|string|max:120')]
    public string $name = '';

    #[Validate('required|email|max:160')]
    public string $email = '';

    #[Validate('nullable|string|max:160')]
    public string $subject = '';

    #[Validate('required|string|max:2000')]
    public string $message = '';

    protected function formType(): string
    {
        return 'contact';
    }

    public function submit(): void
    {
        $this->generalError = null;
        $validated = $this->validate();
        $this->persistSubmission($validated);
    }

    public function render()
    {
        return view('livewire.forms.contact');
    }
}
