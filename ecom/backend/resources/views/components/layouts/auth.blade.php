<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Admin · MyShop' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-900 text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col">
        <header class="px-6 py-5">
            <span class="text-lg font-bold tracking-tight text-white">MYSHOP</span>
            <span class="ml-2 text-xs text-gray-400">/ Admin</span>
        </header>

        <main class="flex-1 flex items-center justify-center px-4 pb-12">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>

</html>
