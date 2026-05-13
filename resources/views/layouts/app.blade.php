<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $description ?? config('quantyra.site.description') }}">

    <title>{{ $title ?? config('quantyra.site.title') }}</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    @fluxAppearance
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-full bg-quantyra-bg-primary text-white overflow-x-hidden">
    @php($nav = config('quantyra.navigation'))
    @php($footer = config('quantyra.footer'))

    <nav id="site-nav" class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 ease-out-circ">
        <div class="w-full px-6 lg:px-12 py-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center">
                    <span class="text-xl font-heading font-semibold tracking-tight text-white">
                        {{ $nav['logo'] }}
                    </span>
                </a>

                <div class="hidden lg:flex items-center gap-8">
                    @foreach ($nav['links'] as $link)
                        <a
                            href="{{ route($link['route']) }}"
                            wire:navigate
                            @class([
                                'text-sm font-medium transition-colors duration-300 relative group',
                                'text-white' => request()->routeIs($link['route']),
                                'text-quantyra-text-secondary hover:text-white' => ! request()->routeIs($link['route']),
                            ])
                        >
                            {{ $link['label'] }}
                            <span @class([
                                'absolute -bottom-1 left-0 h-px bg-quantyra-accent-blue transition-all duration-300',
                                'w-full' => request()->routeIs($link['route']),
                                'w-0 group-hover:w-full' => ! request()->routeIs($link['route']),
                            ])></span>
                        </a>
                    @endforeach
                </div>

                <div class="hidden lg:block">
                    <a
                        href="{{ route($nav['contact_route']) }}"
                        wire:navigate
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium bg-quantyra-accent-blue text-white hover:bg-blue-600 transition-colors duration-300"
                    >
                        {{ $nav['contact_label'] }}
                    </a>
                </div>

                <button
                    type="button"
                    class="lg:hidden relative w-8 h-6 flex flex-col justify-between"
                    aria-label="Toggle menu"
                    data-mobile-menu-toggle
                >
                    <span class="w-full h-0.5 bg-white transition-all duration-500 origin-center mobile-menu-line-1"></span>
                    <span class="w-full h-0.5 bg-white transition-all duration-300 mobile-menu-line-2"></span>
                    <span class="w-full h-0.5 bg-white transition-all duration-500 origin-center mobile-menu-line-3"></span>
                </button>
            </div>
        </div>
    </nav>

    <div
        id="mobile-menu"
        class="fixed inset-0 z-40 bg-quantyra-bg-primary transition-all duration-500 ease-out-cubic lg:hidden opacity-0 invisible pointer-events-none"
        aria-hidden="true"
    >
        <div class="flex flex-col items-center justify-center h-full gap-8 pt-16">
            @foreach ($nav['links'] as $link)
                <a
                    href="{{ route($link['route']) }}"
                    wire:navigate
                    class="text-2xl font-heading font-semibold transition-colors {{ request()->routeIs($link['route']) ? 'text-quantyra-accent-blue' : 'text-white' }}"
                >
                    {{ $link['label'] }}
                </a>
            @endforeach
            <a
                href="{{ route($nav['contact_route']) }}"
                wire:navigate
                class="mt-4 inline-flex items-center justify-center px-8 py-4 bg-quantyra-accent-blue text-white font-medium rounded-lg transition-colors"
            >
                {{ $nav['contact_label'] }}
            </a>
        </div>
    </div>

    <main class="min-h-screen">
        {{ $slot }}
    </main>

    <footer class="w-full bg-quantyra-bg-secondary border-t border-quantyra-border py-16 lg:py-24">
        <div class="container-large px-6 lg:px-12">
            <div class="grid lg:grid-cols-12 gap-12 lg:gap-8">
                <div class="lg:col-span-5 space-y-6">
                    <a href="{{ route('home') }}" class="inline-block" wire:navigate>
                        <span class="text-xl font-heading font-semibold tracking-tight text-white">
                            {{ $footer['logo'] }}
                        </span>
                    </a>
                    <p class="text-sm text-quantyra-text-secondary max-w-sm leading-relaxed">
                        {{ $footer['description'] }}
                    </p>
                </div>

                @foreach ($footer['columns'] as $column)
                    <div class="lg:col-span-2">
                        <h4 class="text-xs font-medium uppercase tracking-widest text-quantyra-text-secondary mb-4">
                            {{ $column['title'] }}
                        </h4>
                        <ul class="space-y-3">
                            @foreach ($column['links'] as $link)
                                <li>
                                    <a
                                        href="{{ isset($link['tab']) ? route($link['route'], ['tab' => $link['tab']]) : route($link['route']) }}"
                                        wire:navigate
                                        class="text-sm text-quantyra-text-secondary hover:text-white transition-colors inline-flex items-center gap-1 group"
                                    >
                                        {{ $link['label'] }}
                                        <flux:icon name="arrow-up-right" class="size-3 opacity-0 -translate-x-1 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-200" />
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>

            <div class="mt-16 pt-8 border-t border-quantyra-border flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs text-quantyra-text-secondary">
                    © {{ now()->year }} {{ $footer['copyright'] }}
                </p>
            </div>
        </div>
    </footer>

    @fluxScripts
    @livewireScripts
    <script>
        (function () {
            const readScrollY = () =>
                window.scrollY ??
                window.pageYOffset ??
                document.documentElement.scrollTop ??
                document.body.scrollTop ??
                0;

            const setScrolled = () => {
                const nav = document.getElementById('site-nav');
                if (!nav) {
                    return;
                }
                nav.classList.toggle('is-scrolled', readScrollY() > 8);
            };

            window.addEventListener('scroll', setScrolled, { passive: true });
            window.addEventListener('resize', setScrolled, { passive: true });
            setScrolled();

            const menu = document.getElementById('mobile-menu');
            const toggle = document.querySelector('[data-mobile-menu-toggle]');
            let open = false;

            const setOpen = (value) => {
                open = value;
                if (!menu || !toggle) {
                    return;
                }
                const l1 = toggle.querySelector('.mobile-menu-line-1');
                const l2 = toggle.querySelector('.mobile-menu-line-2');
                const l3 = toggle.querySelector('.mobile-menu-line-3');
                menu.classList.toggle('opacity-0', !open);
                menu.classList.toggle('invisible', !open);
                menu.classList.toggle('pointer-events-none', !open);
                menu.setAttribute('aria-hidden', open ? 'false' : 'true');
                l1?.classList.toggle('translate-y-[10px]', open);
                l1?.classList.toggle('rotate-[-45deg]', open);
                l2?.classList.toggle('scale-0', open);
                l2?.classList.toggle('opacity-0', open);
                l3?.classList.toggle('-translate-y-[10px]', open);
                l3?.classList.toggle('rotate-[45deg]', open);
            };

            toggle?.addEventListener('click', () => setOpen(!open));

            document.addEventListener('livewire:navigated', () => {
                setOpen(false);
                setScrolled();
            });
        })();
    </script>
</body>
</html>
