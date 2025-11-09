<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
    {{-- Íconos de Google Material Symbols --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    
    <!-- Dark Mode Script -->
    <script>
        // Check for saved theme preference or default to light mode
        if (localStorage.theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else if (localStorage.theme === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            // Default to light mode (no class)
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body class="min-h-screen flex flex-col bg-gradient-to-br from-[#e9fcd9] to-[#d4f4dd] text-gray-900 dark:from-gray-900 dark:to-gray-800 dark:text-white">

@include('layouts.header')

    {{-- CONTENIDO PRINCIPAL (Login o Register) --}}
    <main class="flex-1 flex justify-center items-center p-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-sm p-6 border border-gray-200 dark:border-gray-700">
            {{ $slot }}
        </div>
    </main>

@include('layouts.footer')

    @fluxScripts
</body>
</html>
