<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Ubuntu:ital,wght@0,300;0,400;0,500;1,300;1,400;1,500&display=swap" rel="stylesheet">
        {{-- <script src="https://cdn.tiny.cloud/1/dlj7yw80km1t1tdlelq279fdp4c51q5org7bs18sbhk7vgha/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />

        <!-- Scripts -->
        @vite([
            'resources/css/app.css',
            'resources/js/app.js',
            'resources/js/tinymce.min.js',
        ])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased min-h-screen bg-white dark:bg-zinc-800 relative">
        <x-nav sticky class="lg:hidden">
            <x-slot:brand>
                <div class="ml-5 pt-5"><x-application-logo /></div>
            </x-slot:brand>
            <x-slot:actions>
                <label for="main-drawer" class="lg:hidden mr-3">
                    <x-icon name="o-bars-3" class="cursor-pointer" />
                </label>
            </x-slot:actions>
        </x-nav>
        <x-main full-width>
            @livewire('sidebar')

            <x-slot:content class="mb-10">
                @if (session('success'))
                    <x-alert title="{{ session('success') }}" icon="o-exclamation-triangle" class="alert-success" dismissible />
                @endif
                @if (session('error'))
                    <x-alert title="{{ session('error') }}" icon="o-exclamation-triangle" class="alert-warning" dismissible />
                @endif

                {{ $slot }}
            </x-slot:content>

            <x-slot:footer class="absolute right-0 bottom-0 text-right px-16 py-5">&copy; 2025 - Sofiullah | Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }}) - Jetstream x Livewire</x-slot:footer>
        </x-main>

        @stack('modals')
        <x-toast />

        @livewireScripts
    </body>
</html>
