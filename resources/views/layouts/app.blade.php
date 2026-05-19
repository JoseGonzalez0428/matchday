<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-800 bg-slate-50">
        <div class="min-h-screen">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white border-b border-gray-100 shadow-xs">
                    <div class="max-w-7xl mx-auto py-5 px-6 sm:px-6 lg:px-8">
                        <div class="text-gray-800 font-bold tracking-tight">
                            {{ $header }}
                        </div>
                    </div>
                </header>
            @endisset

            <main class="max-w-7xl mx-auto px-6 py-6">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>