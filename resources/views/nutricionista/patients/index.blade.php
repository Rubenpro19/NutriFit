@extends('layouts.app')

@section('content')
<body class="bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 min-h-screen flex flex-col">
    @include('layouts.header')

    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="mb-6 text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('nutricionista.dashboard') }}" class="hover:text-green-600 dark:hover:text-green-400">Dashboard</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 dark:text-white">Mis Pacientes</span>
        </nav>

        <!-- Encabezado -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('nutricionista.dashboard') }}" class="text-gray-600 hover:text-green-600 transition-colors">
                    <span class="material-symbols-outlined text-2xl">arrow_back</span>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Mis Pacientes</h1>
            </div>
            <p class="text-gray-600 ml-11">Gestiona y visualiza la información de tus pacientes</p>
        </div>

        <livewire:nutricionista.patients-table />
        </div>
    </main>

    @include('layouts.footer')
</body>
@endsection