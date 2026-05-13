<?php

use Illuminate\Support\Str;
use function Livewire\Volt\{layout, title};

layout('layouts.app');

title(fn () => 'Legal — '.config('quantyra.site.title'));

?>

@php($legal = config('quantyra.legal'))
@php($activeSection = in_array(request()->query('tab'), ['terms', 'privacy'], true) ? request()->query('tab') : 'terms')

<div>
<section class="relative pt-32 pb-20 md:pt-40 md:pb-24 bg-quantyra-bg-primary">
    <div class="absolute inset-0 bg-gradient-subtle opacity-20"></div>
    <div class="relative z-10 container-large px-6 lg:px-12">
        <div class="max-w-3xl">
            <span class="text-sm font-medium text-quantyra-accent-blue uppercase tracking-wider mb-4 block">
                {{ $legal['label'] }}
            </span>
            <h1 class="text-h1 font-heading text-white mb-6">
                {{ $legal['heading'] }}
            </h1>
            <p class="text-quantyra-text-secondary">
                Last updated: {{ $legal['last_updated'] }}
            </p>
        </div>
    </div>
</section>

<section class="py-20 md:py-28 bg-quantyra-bg-secondary">
    <div class="container-large px-6 lg:px-12">
        <div class="grid lg:grid-cols-4 gap-12">
            <div class="lg:col-span-1">
                <div class="sticky top-32 space-y-2">
                    @foreach ($legal['sections'] as $section)
                        <a
                            href="{{ route('legal', ['tab' => $section['id']]) }}"
                            wire:navigate
                            @class([
                                'w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left transition-all duration-300',
                                'bg-quantyra-accent-blue/10 text-quantyra-accent-blue border border-quantyra-accent-blue/30' => $activeSection === $section['id'],
                                'text-quantyra-text-secondary hover:bg-quantyra-surface hover:text-white border border-transparent' => $activeSection !== $section['id'],
                            ])
                        >
                            @if ($section['id'] === 'terms')
                                <flux:icon name="document-text" class="size-4 shrink-0" />
                            @else
                                <flux:icon name="shield-check" class="size-4 shrink-0" />
                            @endif
                            <span class="font-medium">{{ $section['title'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="lg:col-span-3">
                <div class="bg-quantyra-surface border border-quantyra-border rounded-xl p-8 md:p-12">
                    @foreach ($legal['sections'] as $section)
                        <div @class(['block' => $activeSection === $section['id'], 'hidden' => $activeSection !== $section['id']])>
                            <h2 class="text-2xl font-heading font-semibold text-white mb-8">
                                {{ $section['title'] }}
                            </h2>
                            <div class="prose prose-invert max-w-none text-quantyra-text-secondary [&_h2]:text-white [&_h2]:text-xl [&_h2]:font-heading [&_h2]:font-semibold [&_h2]:mt-10 [&_h2]:mb-4 [&_p]:leading-relaxed [&_p]:mb-4">
                                {!! Str::markdown($section['content']) !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
</div>
