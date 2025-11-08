<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
    {{-- Íconos de Google Material Symbols --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>

<body class="min-h-screen flex flex-col bg-[#e9fcd9] text-gray-900">

@include('layouts.header')

    {{-- CONTENIDO PRINCIPAL (Login o Register) --}}
    <main class="flex-1 flex justify-center items-center p-6">
        <div class="bg-white rounded-lg shadow-md w-full max-w-sm p-6">
            {{ $slot }}
        </div>
    </main>

@include('layouts.footer')

    @fluxScripts
</body>
</html>
