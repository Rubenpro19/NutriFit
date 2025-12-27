@extends('layouts.app')

@section('content')
<body class="bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 min-h-screen flex flex-col">
    @include('layouts.header')

    <main class="flex-grow container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="mb-6 text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('paciente.dashboard') }}" class="hover:text-green-600 dark:hover:text-green-400">Dashboard</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 dark:text-white">Mi Perfil</span>
        </nav>

        <livewire:settings.user-profile />
    </main>

    @include('layouts.footer')
</body>
@endsection
