@extends('layouts.app')

@section('content')
<body class="bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 min-h-screen flex flex-col">
    @include('layouts.header')

    <main class="flex-grow container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="mb-6 text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('nutricionista.dashboard') }}" class="hover:text-green-600 dark:hover:text-green-400">Dashboard</a>
            <span class="mx-2">/</span>
            <a href="{{ route('nutricionista.patients.index') }}" class="hover:text-green-600 dark:hover:text-green-400">Mis Pacientes</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 dark:text-white">Datos Personales</span>
        </nav>

        <livewire:nutricionista.patient-data-form :patient="$patient" />
    </main>

    @include('layouts.footer')
</body>
@endsection
