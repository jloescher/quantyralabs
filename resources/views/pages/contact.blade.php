<?php

use App\Models\ContactSubmission;
use Illuminate\Support\Facades\Mail;
use function Livewire\Volt\{layout, rules, state, title};

layout('layouts.app');

title(fn () => 'Contact — '.config('quantyra.site.title'));

state([
    'name' => '',
    'email' => '',
    'company' => '',
    'message' => '',
    'submitted' => false,
]);

rules([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'email', 'max:255'],
    'company' => ['nullable', 'string', 'max:255'],
    'message' => ['required', 'string', 'max:5000'],
]);

$save = function () {
    $this->validate();

    $payload = [
        'name' => $this->name,
        'email' => $this->email,
        'company' => $this->company ?: null,
        'message' => $this->message,
    ];

    ContactSubmission::create($payload);

    if ($to = config('quantyra.mail.contact_notification')) {
        $body = "Name: {$payload['name']}\nEmail: {$payload['email']}\nCompany: ".($payload['company'] ?? '')."\n\n{$payload['message']}";
        Mail::raw($body, function ($message) use ($to, $payload) {
            $message->to($to)
                ->subject('New contact: '.$payload['name'].' — '.config('quantyra.site.title'));
        });
    }

    $this->reset(['name', 'email', 'company', 'message']);
    $this->submitted = true;
};

?>

@php($contact = config('quantyra.contact'))

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
        <div class="grid lg:grid-cols-5 gap-12">
            <div class="lg:col-span-2 space-y-8">
                <div class="p-6 bg-quantyra-surface border border-quantyra-border rounded-xl">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-quantyra-accent-blue/10 flex items-center justify-center">
                            <flux:icon name="envelope" class="size-5 text-quantyra-accent-blue" />
                        </div>
                        <span class="text-sm font-medium text-quantyra-text-secondary uppercase tracking-wider">
                            General inquiries
                        </span>
                    </div>
                    <a href="mailto:{{ $contact['email'] }}" class="text-lg text-white hover:text-quantyra-accent-blue transition-colors">
                        {{ $contact['email'] }}
                    </a>
                </div>

                <div class="p-6 bg-quantyra-surface border border-quantyra-border rounded-xl">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-quantyra-accent-cyan/10 flex items-center justify-center">
                            <flux:icon name="envelope" class="size-5 text-quantyra-accent-cyan" />
                        </div>
                        <span class="text-sm font-medium text-quantyra-text-secondary uppercase tracking-wider">
                            Sales
                        </span>
                    </div>
                    <a href="mailto:{{ $contact['sales_email'] }}" class="text-lg text-white hover:text-quantyra-accent-cyan transition-colors">
                        {{ $contact['sales_email'] }}
                    </a>
                </div>

                <div class="p-6 bg-quantyra-surface border border-quantyra-border rounded-xl">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-green-500/10 flex items-center justify-center">
                            <flux:icon name="envelope" class="size-5 text-green-400" />
                        </div>
                        <span class="text-sm font-medium text-quantyra-text-secondary uppercase tracking-wider">
                            Support
                        </span>
                    </div>
                    <a href="mailto:{{ $contact['support_email'] }}" class="text-lg text-white hover:text-green-400 transition-colors">
                        {{ $contact['support_email'] }}
                    </a>
                </div>

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
            </div>

            <div class="lg:col-span-3">
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
                            <flux:button variant="ghost" wire:click="$set('submitted', false)">
                                Send another message
                            </flux:button>
                        </div>
                    @else
                        <h2 class="text-xl font-heading font-semibold text-white mb-6">
                            {{ $contact['form']['form_heading'] }}
                        </h2>

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
                                <flux:label>{{ $contact['form']['company_label'] }}</flux:label>
                                <flux:input wire:model="company" type="text" autocomplete="organization" />
                                <flux:error name="company" />
                            </flux:field>

                            <flux:field>
                                <flux:label>{{ $contact['form']['message_label'] }}</flux:label>
                                <flux:textarea wire:model="message" rows="5" required class="min-h-32" />
                                <flux:error name="message" />
                            </flux:field>

                            <flux:button variant="primary" type="submit" class="w-full" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="save">{{ $contact['form']['submit_label'] }}</span>
                                <span wire:loading wire:target="save">Sending…</span>
                            </flux:button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
</div>
