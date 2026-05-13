<?php

use function Livewire\Volt\{layout, title};

layout('layouts.app');

title(fn () => config('quantyra.site.title'));

?>

@php($hero = config('quantyra.hero'))
@php($products = config('quantyra.home_products'))

<div>
<section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-quantyra-bg-primary">
    <div class="absolute inset-0 bg-gradient-subtle opacity-30"></div>
    <div
        class="absolute inset-0 opacity-[0.03]"
        style="background-image: linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 60px 60px;"
    ></div>

    <div class="relative z-10 container-large px-6 lg:px-12 py-32">
        <div class="max-w-4xl mx-auto text-center">
            <div class="transition-all duration-1000 ease-out-quart opacity-100 translate-y-0">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-quantyra-surface border border-quantyra-border text-sm text-quantyra-text-secondary mb-8">
                    <span class="w-2 h-2 rounded-full bg-quantyra-accent-blue animate-pulse"></span>
                    {{ config('quantyra.site.title') }}
                </span>
            </div>

            <h1 class="text-h1 font-heading text-white mb-8 transition-all duration-1000 ease-out-quart opacity-100 translate-y-0" style="transition-delay: 150ms">
                {{ $hero['headline'] }}
            </h1>

            <p class="text-lg md:text-xl text-quantyra-text-secondary max-w-2xl mx-auto mb-12 leading-relaxed transition-all duration-1000 ease-out-quart opacity-100 translate-y-0" style="transition-delay: 300ms">
                {{ $hero['subtext'] }}
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 transition-all duration-1000 ease-out-quart opacity-100 translate-y-0" style="transition-delay: 450ms">
                <a
                    href="{{ route('contact') }}"
                    wire:navigate
                    class="group inline-flex items-center gap-2 px-8 py-4 bg-quantyra-accent-blue text-white font-medium rounded-lg hover:bg-blue-600 transition-colors duration-300"
                >
                    {{ $hero['cta_primary'] }}
                    <flux:icon name="arrow-right" class="size-4 transition-transform duration-300 group-hover:translate-x-1" />
                </a>
                <a
                    href="{{ route('about') }}"
                    wire:navigate
                    class="inline-flex items-center gap-2 px-8 py-4 border border-quantyra-border text-white font-medium rounded-lg hover:bg-quantyra-surface transition-colors duration-300"
                >
                    {{ $hero['cta_secondary'] }}
                </a>
            </div>
        </div>
    </div>

    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-quantyra-bg-primary to-transparent"></div>
</section>

<section class="relative py-24 md:py-32 bg-quantyra-bg-secondary border-t border-quantyra-border">
    <div class="container-large px-6 lg:px-12">
        <div class="max-w-3xl mb-16">
            <span class="text-sm font-medium text-quantyra-accent-blue uppercase tracking-wider mb-4 block">
                {{ $products['label'] }}
            </span>
            <h2 class="text-h3 font-heading text-white mb-6">
                {{ $products['heading'] }}
            </h2>
            <p class="text-lg text-quantyra-text-secondary leading-relaxed">
                {{ $products['description'] }}
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            @foreach ($products['items'] as $item)
                <div class="p-8 bg-quantyra-surface border border-quantyra-border rounded-xl h-full hover:border-quantyra-accent-blue/40 transition-colors duration-300">
                    <div class="w-10 h-10 rounded-lg bg-quantyra-accent-blue/10 flex items-center justify-center mb-5">
                        <span class="text-quantyra-accent-blue font-heading font-bold text-lg">{{ $loop->iteration }}</span>
                    </div>
                    <h3 class="text-lg font-heading font-semibold text-white mb-3">
                        {{ $item['title'] }}
                    </h3>
                    <p class="text-sm text-quantyra-text-secondary leading-relaxed">
                        {{ $item['description'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>
</div>
