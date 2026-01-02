@extends('layouts.app')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    @include('layouts.header')

    <main class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
    <!-- Breadcrumb -->
        <nav class="mb-6 text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('nutricionista.dashboard') }}" class="hover:text-green-600 dark:hover:text-green-400">Inicio</a>
            <span class="mx-2">/</span>
            <a href="{{ route('nutricionista.patients.index') }}" class="hover:text-green-600 dark:hover:text-green-400">Mis Pacientes</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 dark:text-white">Datos Personales</span>
        </nav>
        
        <livewire:nutricionista.patient-data-form :patient="$patient" />
        </div>
    </main>

    @include('layouts.footer')
</body>
@endsection
