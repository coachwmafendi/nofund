<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

        @fluxAppearance
        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 dark:bg-gray-950 antialiased min-h-screen">
        <div class="max-w-2xl mx-auto py-16 px-6">
            <flux:card>
                <flux:heading size="xl" level="1" class="mb-4">Selamat Datang ke nofund!</flux:heading>
                <flux:text class="mb-6">Projek Laravel dengan Livewire 4 dan Flux UI components.</flux:text>

                <livewire:counter />
            </flux:card>
        </div>

        @fluxScripts
    </body>
</html>
