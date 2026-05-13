<?php

use function Livewire\Volt\{layout, title};

layout('layouts.app');

title(fn () => 'About — '.config('quantyra.site.title'));

?>

@php($about = config('quantyra.about'))

<div>
<section class="relative pt-32 pb-20 md:pt-40 md:pb-24 bg-quantyra-bg-primary">
    <div class="absolute inset-0 bg-gradient-subtle opacity-20"></div>
    <div class="relative z-10 container-large px-6 lg:px-12">
        <div class="max-w-3xl">
            <span class="text-sm font-medium text-quantyra-accent-blue uppercase tracking-wider mb-4 block">
                {{ $about['label'] }}
            </span>
            <h1 class="text-h1 font-heading text-white mb-6">
                {{ $about['heading'] }}
            </h1>
            <p class="text-lg md:text-xl text-quantyra-text-secondary leading-relaxed">
                {{ $about['description'] }}
            </p>
        </div>
    </div>
</section>

<section class="py-20 md:py-28 bg-quantyra-bg-secondary">
    <div class="container-large px-6 lg:px-12">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-h3 font-heading text-white mb-6">
                {{ $about['mission']['heading'] }}
            </h2>
            <p class="text-lg text-quantyra-text-secondary leading-relaxed">
                {{ $about['mission']['text'] }}
            </p>
        </div>
    </div>
</section>

<section class="py-20 md:py-28 bg-quantyra-bg-primary">
    <div class="container-large px-6 lg:px-12">
        <div class="text-center mb-16">
            <h2 class="text-h3 font-heading text-white">
                Our values
            </h2>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($about['values'] as $value)
                <div class="p-6 bg-quantyra-surface border border-quantyra-border rounded-xl h-full">
                    <div class="w-10 h-10 rounded-lg bg-quantyra-accent-blue/10 flex items-center justify-center mb-4">
                        <flux:icon name="check-circle" class="size-5 text-quantyra-accent-blue" />
                    </div>
                    <h3 class="text-lg font-heading font-semibold text-white mb-3">
                        {{ $value['title'] }}
                    </h3>
                    <p class="text-sm text-quantyra-text-secondary leading-relaxed">
                        {{ $value['description'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-20 md:py-28 bg-quantyra-bg-secondary">
    <div class="container-large px-6 lg:px-12">
        <div class="max-w-2xl">
            <div class="flex items-center gap-3 mb-4">
                <flux:icon name="map-pin" class="size-5 text-quantyra-accent-blue" />
                <span class="text-sm font-medium text-quantyra-accent-blue uppercase tracking-wider">
                    {{ $about['location']['heading'] }}
                </span>
            </div>
            <h3 class="text-2xl font-heading font-semibold text-white mb-2">
                {{ $about['location']['address'] }}
            </h3>
            <p class="text-quantyra-text-secondary">
                {{ $about['location']['detail'] }}
            </p>
        </div>
    </div>
</section>
</div>
