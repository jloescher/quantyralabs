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

                <div class="flex items-center gap-4">
                    @foreach ($contact['social'] as $social)
                        <a
                            href="{{ $social['href'] }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="w-12 h-12 rounded-lg bg-quantyra-surface border border-quantyra-border flex items-center justify-center text-quantyra-text-secondary hover:text-white hover:border-quantyra-accent-blue transition-all duration-300"
                            aria-label="{{ $social['platform'] }}"
                        >
                            @if ($social['platform'] === 'Twitter')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            @elseif ($social['platform'] === 'LinkedIn')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            @else
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/></svg>
                            @endif
                        </a>
                    @endforeach
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
