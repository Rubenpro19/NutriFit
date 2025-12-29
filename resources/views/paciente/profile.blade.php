@extends('layouts.app')

@section('content')
<body class="bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 min-h-screen flex flex-col">
    @include('layouts.header')

    <main class="flex-grow container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">    
    <!-- Breadcrumb -->
        <nav class="mb-6 flex items-center gap-2 text-sm">
            <a href="{{ route('paciente.dashboard') }}" class="text-green-600 dark:text-green-400 hover:underline">Dashboard</a>
            <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
            <span class="text-gray-600 dark:text-gray-400">Mi Perfil</span>
        </nav>

        <livewire:settings.user-profile />
    </div>
    </main>

    @include('layouts.footer')
</body>
@endsection
