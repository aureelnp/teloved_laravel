<!DOCTYPE html>
<html lang="id" class="h-full bg-stone-50/50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Teloved — Curated Preloved Fashion')</title>

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Tailwind CSS Script Fallback for flawless standalone preview -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Vite Assets -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script type="module" src="{{ asset('js/app.js') }}"></script>
    @endif

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #FAFAF9;
            color: #18181B;
        }
        .glass-nav {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
    </style>
</head>
<body class="min-h-full flex flex-col antialiased selection:bg-stone-900 selection:text-white">

    <!-- Top Announcement Bar -->
    <div class="bg-stone-900 text-stone-300 text-xs py-2 px-4 text-center font-medium tracking-wide">
        <span>✨ Kurasi Preloved Fashion Berkelanjutan & Terverifikasi • <strong>Teloved Official</strong></span>
    </div>

    <!-- Navigation Header -->
    <header class="sticky top-0 z-40 border-b border-stone-200/80 glass-nav transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                
                <!-- Brand Monogram & Title -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('categories.view') }}" class="group flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-stone-900 text-white flex items-center justify-center font-bold text-lg tracking-wider shadow-sm group-hover:bg-stone-800 transition-colors">
                            T
                        </div>
                        <div>
                            <span class="text-xl font-bold tracking-tight text-stone-900 block leading-tight font-serif sm:font-sans">Teloved</span>
                            <span class="text-[10px] tracking-widest uppercase text-stone-400 font-semibold block">Preloved Fashion</span>
                        </div>
                    </a>

                    <!-- Desktop Nav Links -->
                    <nav class="hidden md:flex items-center gap-1.5">
                        <a href="{{ route('categories.view') }}" 
                           class="px-3.5 py-2 rounded-xl text-xs font-medium transition-colors {{ request()->routeIs('categories.view') ? 'bg-stone-100 text-stone-900 font-semibold' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-50' }}">
                            Kategori Fashion
                        </a>
                        <a href="{{ route('products.view') }}" 
                           class="px-3.5 py-2 rounded-xl text-xs font-medium transition-colors {{ request()->routeIs('products.view') ? 'bg-stone-100 text-stone-900 font-semibold' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-50' }}">
                            Katalog Produk
                        </a>
                        <a href="{{ route('users.view') }}" 
                           class="px-3.5 py-2 rounded-xl text-xs font-medium transition-colors {{ request()->routeIs('users.view') ? 'bg-stone-100 text-stone-900 font-semibold' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-50' }}">
                            Manajemen User
                        </a>
                    </nav>
                </div>

                <!-- Right Header Actions -->
                <div class="flex items-center gap-3">
                    <!-- Mobile Menu Hamburger Button -->
                    <button type="button" id="btn-mobile-menu" class="md:hidden p-2 rounded-xl text-stone-600 hover:text-stone-900 hover:bg-stone-100 transition-colors" aria-label="Menu">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Drawer -->
        <div id="mobile-menu-drawer" class="hidden md:hidden border-t border-stone-200 bg-white/95 px-4 pt-3 pb-5 space-y-1 shadow-lg">
            <a href="{{ route('categories.view') }}" class="block px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('categories.view') ? 'bg-stone-100 text-stone-900 font-semibold' : 'text-stone-600' }}">
                Kategori Fashion
            </a>
            <a href="{{ route('products.view') }}" class="block px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('products.view') ? 'bg-stone-100 text-stone-900 font-semibold' : 'text-stone-600' }}">
                Katalog Produk
            </a>
            <a href="{{ route('users.view') }}" class="block px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('users.view') ? 'bg-stone-100 text-stone-900 font-semibold' : 'text-stone-600' }}">
                Manajemen User
            </a>
        </div>
    </header>

    <!-- Main Content Slot -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Global Confirmation Modal -->
    <div id="global-confirm-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="confirm-modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-stone-900/40 backdrop-blur-xs transition-opacity"></div>
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-stone-200 animate-fade-in-scale">
                <div class="bg-white px-5 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start gap-4">
                        <div class="mx-auto flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-rose-50 text-rose-600 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 9v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:text-left flex-1">
                            <h3 class="text-base font-semibold leading-6 text-stone-900" id="confirm-modal-title">Konfirmasi Hapus</h3>
                            <div class="mt-2">
                                <p class="text-xs text-stone-500 leading-relaxed" id="confirm-modal-message">Apakah Anda yakin ingin melakukan tindakan ini?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-stone-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <button type="button" id="confirm-modal-submit" class="w-full sm:w-auto inline-flex justify-center rounded-xl bg-rose-600 px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-rose-500 transition-colors">
                        Ya, Lanjutkan
                    </button>
                    <button type="button" id="confirm-modal-cancel" class="mt-2 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-xl bg-white px-4 py-2 text-xs font-semibold text-stone-700 shadow-xs ring-1 ring-inset ring-stone-300 hover:bg-stone-100 transition-colors">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none px-4"></div>

    @stack('scripts')
</body>
</html>
