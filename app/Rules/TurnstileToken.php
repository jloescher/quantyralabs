<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class TurnstileToken implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = config('services.turnstile.secret_key');
        if (! is_string($secret) || $secret === '') {
            $fail('Turnstile is not configured on the server.');

            return;
        }

        if (! is_string($value) || $value === '') {
            $fail('Please complete the verification challenge.');

            return;
        }

        $response = Http::asForm()
            ->timeout(10)
            ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secret,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

        if (! $response->successful() || ! $response->json('success')) {
            $fail('The verification challenge failed or expired. Please try again.');
        }
    }
}
