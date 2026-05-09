<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Admin · MyShop' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-50 text-gray-900 antialiased">
    <div class="min-h-screen flex">
        @include('partials.admin-sidebar')

        <div class="flex-1 flex flex-col min-w-0">
            @include('partials.admin-topbar', ['pageTitle' => $pageTitle ?? 'Admin'])

            @if (session('message'))
                <div class="mx-4 md:mx-8 mt-4 p-3 bg-green-50 text-green-800 border border-green-200 rounded-md text-sm">
                    {{ session('message') }}
                </div>
            @endif

            <main class="flex-1 p-4 md:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>

</html>
