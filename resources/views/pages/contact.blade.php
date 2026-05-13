<?php

use App\Models\ContactSubmission;
use App\Rules\TurnstileToken;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use function Livewire\Volt\{layout, state, title};

layout('layouts.app');

title(fn () => 'Contact — '.config('quantyra.site.title'));

state([
    'name' => '',
    'email' => '',
    'department' => 'general',
    'company' => '',
    'message' => '',
    'turnstileToken' => '',
    'formInstanceKey' => 0,
    'submitted' => false,
]);

$retryContactForm = function () {
    $this->submitted = false;
    $this->formInstanceKey++;
    $this->turnstileToken = '';
    $this->department = 'general';
};

$save = function () {
    $turnstileOn = filled(config('services.turnstile.site_key')) && filled(config('services.turnstile.secret_key'));

    $rules = [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255'],
        'department' => ['required', 'string', Rule::in(['general', 'sales', 'support'])],
        'company' => ['nullable', 'string', 'max:255'],
        'message' => ['required', 'string', 'max:5000'],
    ];

    if ($turnstileOn) {
        $rules['turnstileToken'] = ['required', 'string', new TurnstileToken];
    }

    try {
        $this->validate($rules);
    } catch (ValidationException $e) {
        if ($turnstileOn) {
            $this->turnstileToken = '';
            $this->js('if (window.turnstile) { window.turnstile.reset(); }');
        }

        throw $e;
    }

    $payload = [
        'name' => $this->name,
        'email' => $this->email,
        'department' => $this->department,
        'company' => $this->company ?: null,
        'message' => $this->message,
    ];

    ContactSubmission::create($payload);

    if ($to = config('quantyra.mail.contact_notification')) {
        $departmentLabel = collect(config('quantyra.contact.form.departments'))
            ->pluck('label', 'value')
            ->get($this->department, $this->department);
        $body = "Department: {$departmentLabel}\nName: {$payload['name']}\nEmail: {$payload['email']}\nCompany: ".($payload['company'] ?? '')."\n\n{$payload['message']}";
        Mail::raw($body, function ($message) use ($to, $payload, $departmentLabel) {
            $message->to($to)
                ->subject('New contact ('.$departmentLabel.'): '.$payload['name'].' — '.config('quantyra.site.title'));
        });
    }

    $this->reset(['name', 'email', 'company', 'message', 'turnstileToken']);
    $this->department = 'general';
    $this->submitted = true;
};

?>

@php($contact = config('quantyra.contact'))
@php($turnstileOn = filled(config('services.turnstile.site_key')) && filled(config('services.turnstile.secret_key')))

<div>
<section class="relative pt-32 pb-20 md:pt-40 md:pb-24 bg-quantyra-bg-primary">
    <div class="absolute inset-0 bg-gradient-subtle opacity-20"></div>
    <div class="relative z-10 container-large px-6 lg:px-12">
        <div class="max-w-3xl">
            <span class="text-sm font-medium text-quantyra-accent-blue uppercase tracking-wider mb-4 block">
                {{ $contact['label'] }}
            </span>
            <h1 class="text-h1 font-heading text-white mb-6">
                {{ $contact['heading'] }}
            </h1>
            <p class="text-lg md:text-xl text-quantyra-text-secondary leading-relaxed">
                {{ $contact['description'] }}
            </p>
        </div>
    </div>
</section>

<section class="py-20 md:py-28 bg-quantyra-bg-secondary">
    <div class="container-large px-6 lg:px-12">
        <div class="max-w-2xl mx-auto space-y-8">
            <div class="p-6 bg-quantyra-surface border border-quantyra-border rounded-xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-quantyra-accent-blue/10 flex items-center justify-center">
                        <flux:icon name="map-pin" class="size-5 text-quantyra-accent-blue" />
                    </div>
                    <span class="text-sm font-medium text-quantyra-text-secondary uppercase tracking-wider">
                        Location
                    </span>
                </div>
                <div class="text-white">
                    <div>{{ $contact['address']['line1'] }}</div>
                    <div>{{ $contact['address']['line2'] }}</div>
                    <div class="text-quantyra-text-secondary">{{ $contact['address']['line3'] }}</div>
                </div>
            </div>

            <div class="bg-quantyra-surface border border-quantyra-border rounded-xl p-8 md:p-10">
                @if ($submitted)
                        <div class="text-center py-8">
                            <div class="w-16 h-16 rounded-full bg-green-500/10 flex items-center justify-center mx-auto mb-6">
                                <flux:icon name="check-circle" class="size-8 text-green-400" />
                            </div>
                            <h3 class="text-xl font-heading font-semibold text-white mb-3">
                                Message sent
                            </h3>
                            <p class="text-quantyra-text-secondary mb-8">
                                {{ $contact['form']['success_message'] }}
                            </p>
                            <flux:button variant="ghost" wire:click="retryContactForm">
                                Send another message
                            </flux:button>
                        </div>
                @else
                    <h2 class="text-xl font-heading font-semibold text-white mb-6">
                        {{ $contact['form']['form_heading'] }}
                    </h2>

                    <div wire:key="contact-form-{{ $formInstanceKey }}">
                        <form wire:submit="save" class="space-y-6">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <flux:field>
                                        <flux:label>{{ $contact['form']['name_label'] }}</flux:label>
                                        <flux:input wire:model="name" type="text" autocomplete="name" required />
                                        <flux:error name="name" />
                                    </flux:field>
                                    <flux:field>
                                        <flux:label>{{ $contact['form']['email_label'] }}</flux:label>
                                        <flux:input wire:model="email" type="email" autocomplete="email" required />
                                        <flux:error name="email" />
                                    </flux:field>
                                </div>

                                <flux:field>
                                    <flux:label>{{ $contact['form']['department_label'] }}</flux:label>
                                    <select
                                        wire:model="department"
                                        required
                                        class="block w-full rounded-lg border border-quantyra-border bg-quantyra-bg-primary px-3 py-2.5 text-sm text-white shadow-sm focus:border-quantyra-accent-blue focus:outline-none focus:ring-1 focus:ring-quantyra-accent-blue"
                                    >
                                        @foreach ($contact['form']['departments'] as $dept)
                                            <option value="{{ $dept['value'] }}">{{ $dept['label'] }}</option>
                                        @endforeach
                                    </select>
                                    <flux:error name="department" />
                                </flux:field>

                                <flux:field>
                                    <flux:label>{{ $contact['form']['company_label'] }}</flux:label>
                                    <flux:input wire:model="company" type="text" autocomplete="organization" />
                                    <flux:error name="company" />
                                </flux:field>

                                <flux:field>
                                    <flux:label>{{ $contact['form']['message_label'] }}</flux:label>
                                    <flux:textarea wire:model="message" rows="5" required class="min-h-32" />
                                    <flux:error name="message" />
                                </flux:field>

                                @if ($turnstileOn)
                                    <div>
                                        <div
                                            data-quantyra-turnstile-livewire-id="{{ $this->getId() }}"
                                            class="flex flex-col items-center gap-2"
                                        >
                                            <div
                                                wire:ignore
                                                class="cf-turnstile"
                                                data-sitekey="{{ config('services.turnstile.site_key') }}"
                                                data-theme="dark"
                                                data-size="flexible"
                                                data-callback="quantyraTurnstileSuccess"
                                                data-expired-callback="quantyraTurnstileExpired"
                                                data-error-callback="quantyraTurnstileError"
                                            ></div>
                                        </div>
                                        <flux:error name="turnstileToken" />
                                        <p class="text-xs text-quantyra-text-secondary text-center">
                                            Protected by
                                            <a href="https://www.cloudflare.com/privacypolicy/" class="underline hover:text-white" target="_blank" rel="noopener noreferrer">Cloudflare</a>
                                            Turnstile.
                                        </p>
                                    </div>
                                @endif

                                <flux:button variant="primary" type="submit" class="w-full" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="save">{{ $contact['form']['submit_label'] }}</span>
                                    <span wire:loading wire:target="save">Sending…</span>
                                </flux:button>
                            </form>
                        </div>
                    @endif
                </div>
        </div>
    </div>
</section>
</div>
