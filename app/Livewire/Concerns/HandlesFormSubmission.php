<?php

namespace App\Livewire\Concerns;

use App\Mail\SubmissionReceived;
use App\Models\FormSubmission;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Throwable;

trait HandlesFormSubmission
{
    public string $hp_website = '';

    public bool $submitted = false;

    public ?string $generalError = null;

    abstract protected function formType(): string;

    protected function payloadFromValidated(array $data): array
    {
        return collect($data)
            ->except(['hp_website'])
            ->all();
    }

    protected function persistSubmission(array $validated): void
    {
        if ($this->hp_website !== '') {
            $this->submitted = true;
            return;
        }

        $key = 'form:' . $this->formType() . ':' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $this->generalError = "Too many submissions. Please try again in {$seconds} seconds.";
            return;
        }
        RateLimiter::hit($key, 600);

        $submission = FormSubmission::create([
            'type' => $this->formType(),
            'payload' => $this->payloadFromValidated($validated),
            'ip' => request()->ip(),
            'user_agent' => substr(request()->userAgent() ?? '', 0, 512),
        ]);

        try {
            Mail::to(config('mail.notifications_to', 'info@colivingfounders.com'))
                ->send(new SubmissionReceived($submission));
        } catch (Throwable $e) {
            report($e);
        }

        $this->submitted = true;
    }
}
