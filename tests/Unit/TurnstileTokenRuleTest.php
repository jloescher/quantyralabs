<?php

namespace Tests\Unit;

use App\Rules\TurnstileToken;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TurnstileTokenRuleTest extends TestCase
{
    public function test_passes_when_cloudflare_accepts_token(): void
    {
        config([
            'services.turnstile.secret_key' => 'test-secret',
        ]);

        Http::fake([
            'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response(['success' => true], 200),
        ]);

        $failed = false;
        (new TurnstileToken)->validate('turnstileToken', 'tok', function () use (&$failed) {
            $failed = true;
        });

        $this->assertFalse($failed);
    }

    public function test_fails_when_cloudflare_rejects_token(): void
    {
        config([
            'services.turnstile.secret_key' => 'test-secret',
        ]);

        Http::fake([
            'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response(['success' => false], 200),
        ]);

        $message = null;
        (new TurnstileToken)->validate('turnstileToken', 'bad', function (string $m) use (&$message) {
            $message = $m;
        });

        $this->assertNotNull($message);
    }
}
