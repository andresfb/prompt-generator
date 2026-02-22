@props(['title' => config('app.name', 'Laravel')])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col">
        <header class="bg-white border-b border-gray-200">
            <nav class="mx-auto max-w-7xl flex items-center justify-between px-6 py-4">
                <a href="{{ url('/') }}" class="text-lg font-semibold">
                    {{ config('app.name', 'Laravel') }}
                </a>

                <div class="flex items-center gap-4 text-sm">
                    <span class="text-gray-600">{{ Auth::user()->name }}</span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-gray-900">
                            Log out
                        </button>
                    </form>
                </div>
            </nav>
        </header>

        <main class="flex-1">
            <div class="mx-auto max-w-7xl px-6 py-8 md:py-12">
                {{ $slot }}
            </div>
        </main>

        <footer class="border-t border-gray-200 py-6 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}
        </footer>
    </body>
</html>
