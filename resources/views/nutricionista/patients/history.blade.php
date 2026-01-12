@extends('layouts.app')

@section('title', 'Historial Clínico - ' . $patient->name . ' - NutriFit')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
    @include('layouts.header')

    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <!-- Breadcrumb -->
            <nav class="mb-6 flex items-center gap-2 text-sm">
                <a href="{{ route('nutricionista.dashboard') }}" class="text-green-600 dark:text-green-400 hover:underline transition-colors">Inicio</a>
                <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
                <a href="{{ route('nutricionista.patients.index') }}" class="text-green-600 dark:text-green-400 hover:underline transition-colors">Mis Pacientes</a>
                <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
                <a href="{{ route('nutricionista.patients.show', $patient) }}" class="text-green-600 dark:text-green-400 hover:underline transition-colors">Detalle del Paciente</a>
                <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
                <span class="text-gray-700 dark:text-gray-300 font-medium">Historial Clínico</span>
            </nav>

            <!-- Header -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-6">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('nutricionista.patients.show', $patient) }}" class="text-white hover:text-green-100 transition-colors">
                            <span class="material-symbols-outlined text-2xl">arrow_back</span>
                        </a>
                        <div class="flex items-center gap-4 flex-1">
                            <div class="w-16 h-16 rounded-full overflow-hidden bg-white flex items-center justify-center shadow-lg">
                                @if($patient->personalData?->profile_photo)
                                    <img src="{{ asset('storage/' . $patient->personalData->profile_photo) }}" 
                                         alt="{{ $patient->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <span class="text-green-600 text-xl font-bold">{{ $patient->initials() }}</span>
                                @endif
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">Historial Clínico</h1>
                                <p class="text-green-100">{{ $patient->name }}</p>
                            </div>
                        </div>
                        <div class="hidden sm:flex items-center gap-2 text-white">
                            <span class="material-symbols-outlined">monitoring</span>
                            <span class="font-semibold">{{ $attentions->count() }} atenciones registradas</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($attentions->isEmpty())
                <!-- Sin datos -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center border border-gray-200 dark:border-gray-700">
                    <span class="material-symbols-outlined text-6xl text-gray-400 dark:text-gray-500 mb-4">analytics</span>
                    <h3 class="text-xl font-bold text-gray-700 dark:text-gray-300 mb-2">Sin historial clínico</h3>
                    <p class="text-gray-500 dark:text-gray-400">Este paciente aún no tiene atenciones registradas con medidas antropométricas.</p>
                </div>
            @else
                <!-- Tarjetas de Progreso -->
                @if($progressStats)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <!-- Peso -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-gray-600 dark:text-gray-400 text-sm font-medium">Peso</span>
                            <span class="material-symbols-outlined text-blue-500">scale</span>
                        </div>
                        <div class="flex items-end gap-2">
                            <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $progressStats['weight']['current'] }}</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm mb-1">kg</span>
                        </div>
                        <div class="mt-2 flex items-center gap-2">
                            @php
                                $weightChange = $progressStats['weight']['change'];
                                $isPositive = $weightChange > 0;
                            @endphp
                            <span class="flex items-center gap-1 text-sm {{ $isPositive ? 'text-red-500' : ($weightChange < 0 ? 'text-green-500' : 'text-gray-500') }}">
                                <span class="material-symbols-outlined text-sm">{{ $isPositive ? 'trending_up' : ($weightChange < 0 ? 'trending_down' : 'trending_flat') }}</span>
                                {{ abs($weightChange) }} kg ({{ $progressStats['weight']['percentage'] }}%)
                            </span>
                            <span class="text-gray-400 text-xs">vs inicial</span>
                        </div>
                    </div>

                    <!-- IMC -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-gray-600 dark:text-gray-400 text-sm font-medium">IMC</span>
                            <span class="material-symbols-outlined text-purple-500">monitor_weight</span>
                        </div>
                        <div class="flex items-end gap-2">
                            <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($progressStats['bmi']['current'], 1) }}</span>
                        </div>
                        <div class="mt-2 flex items-center gap-2">
                            @php
                                $bmiChange = $progressStats['bmi']['change'];
                            @endphp
                            <span class="flex items-center gap-1 text-sm {{ $bmiChange > 0 ? 'text-amber-500' : ($bmiChange < 0 ? 'text-green-500' : 'text-gray-500') }}">
                                <span class="material-symbols-outlined text-sm">{{ $bmiChange > 0 ? 'trending_up' : ($bmiChange < 0 ? 'trending_down' : 'trending_flat') }}</span>
                                {{ abs($bmiChange) }}
                            </span>
                            <span class="text-gray-400 text-xs">vs inicial ({{ $progressStats['bmi']['initial'] }})</span>
                        </div>
                    </div>

                    <!-- % Grasa -->
                    @if($progressStats['body_fat']['current'])
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-gray-600 dark:text-gray-400 text-sm font-medium">% Grasa Corporal</span>
                            <span class="material-symbols-outlined text-orange-500">water_drop</span>
                        </div>
                        <div class="flex items-end gap-2">
                            <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($progressStats['body_fat']['current'], 1) }}</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm mb-1">%</span>
                        </div>
                        @if($progressStats['body_fat']['change'] !== null)
                        <div class="mt-2 flex items-center gap-2">
                            @php
                                $fatChange = $progressStats['body_fat']['change'];
                            @endphp
                            <span class="flex items-center gap-1 text-sm {{ $fatChange > 0 ? 'text-red-500' : ($fatChange < 0 ? 'text-green-500' : 'text-gray-500') }}">
                                <span class="material-symbols-outlined text-sm">{{ $fatChange > 0 ? 'trending_up' : ($fatChange < 0 ? 'trending_down' : 'trending_flat') }}</span>
                                {{ abs($fatChange) }}%
                            </span>
                            <span class="text-gray-400 text-xs">vs inicial</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Cintura -->
                    @if($progressStats['waist']['current'])
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-gray-600 dark:text-gray-400 text-sm font-medium">Cintura</span>
                            <span class="material-symbols-outlined text-teal-500">straighten</span>
                        </div>
                        <div class="flex items-end gap-2">
                            <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $progressStats['waist']['current'] }}</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm mb-1">cm</span>
                        </div>
                        @if($progressStats['waist']['change'] !== null)
                        <div class="mt-2 flex items-center gap-2">
                            @php
                                $waistChange = $progressStats['waist']['change'];
                            @endphp
                            <span class="flex items-center gap-1 text-sm {{ $waistChange > 0 ? 'text-red-500' : ($waistChange < 0 ? 'text-green-500' : 'text-gray-500') }}">
                                <span class="material-symbols-outlined text-sm">{{ $waistChange > 0 ? 'trending_up' : ($waistChange < 0 ? 'trending_down' : 'trending_flat') }}</span>
                                {{ abs($waistChange) }} cm
                            </span>
                            <span class="text-gray-400 text-xs">vs inicial</span>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
                @endif

                <!-- Gráficas -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Gráfica de Peso -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-500">scale</span>
                            Evolución del Peso
                        </h3>
                        <div class="h-64">
                            <canvas id="weightChart"></canvas>
                        </div>
                    </div>

                    <!-- Gráfica de IMC -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-purple-500">monitor_weight</span>
                            Evolución del IMC
                        </h3>
                        <div class="h-64">
                            <canvas id="bmiChart"></canvas>
                        </div>
                    </div>

                    <!-- Gráfica de Medidas Corporales -->
                    @if(array_filter($chartData['waists']))
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-teal-500">straighten</span>
                            Medidas Corporales
                        </h3>
                        <div class="h-64">
                            <canvas id="measurementsChart"></canvas>
                        </div>
                    </div>
                    @endif

                    <!-- Gráfica de % Grasa Corporal -->
                    @if(array_filter($chartData['bodyFats']))
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-orange-500">water_drop</span>
                            % Grasa Corporal
                        </h3>
                        <div class="h-64">
                            <canvas id="bodyFatChart"></canvas>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Tabla de Historial -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-500">history</span>
                            Historial de Atenciones
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Peso</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">IMC</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">% Grasa</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Cintura</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Cadera</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Kcal/día</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Objetivo</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($attentions->reverse() as $attention)
                                    @php $data = $attention->attentionData; @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $attention->created_at->format('d/m/Y') }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $attention->created_at->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-semibold">
                                            {{ $data->weight }} kg
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $bmi = $data->bmi;
                                                $bmiClass = $bmi < 18.5 ? 'text-blue-600 bg-blue-100' : ($bmi < 25 ? 'text-green-600 bg-green-100' : ($bmi < 30 ? 'text-amber-600 bg-amber-100' : 'text-red-600 bg-red-100'));
                                            @endphp
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $bmiClass }}">
                                                {{ number_format($bmi, 1) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            {{ $data->body_fat ? number_format($data->body_fat, 1) . '%' : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            {{ $data->waist ? $data->waist . ' cm' : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            {{ $data->hip ? $data->hip . ' cm' : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-semibold">
                                            {{ $data->target_calories ? number_format($data->target_calories, 0) : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($data->nutrition_goal)
                                                @php
                                                    $goalColors = [
                                                        'deficit' => 'text-red-600 bg-red-100 dark:bg-red-900/30',
                                                        'maintenance' => 'text-blue-600 bg-blue-100 dark:bg-blue-900/30',
                                                        'surplus' => 'text-green-600 bg-green-100 dark:bg-green-900/30',
                                                    ];
                                                    $goalLabels = [
                                                        'deficit' => 'Déficit',
                                                        'maintenance' => 'Mantenimiento',
                                                        'surplus' => 'Superávit',
                                                    ];
                                                @endphp
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $goalColors[$data->nutrition_goal] ?? 'bg-gray-100' }}">
                                                    {{ $goalLabels[$data->nutrition_goal] ?? $data->nutrition_goal }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </main>

    @include('layouts.footer')
</body>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartData = @json($chartData);
    
    // Configuración común
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280'
                }
            },
            y: {
                grid: {
                    color: document.documentElement.classList.contains('dark') ? '#374151' : '#E5E7EB'
                },
                ticks: {
                    color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280'
                }
            }
        }
    };

    // Gráfica de Peso
    if (document.getElementById('weightChart')) {
        new Chart(document.getElementById('weightChart'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Peso (kg)',
                    data: chartData.weights,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#3B82F6'
                }]
            },
            options: commonOptions
        });
    }

    // Gráfica de IMC
    if (document.getElementById('bmiChart')) {
        new Chart(document.getElementById('bmiChart'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'IMC',
                    data: chartData.bmis,
                    borderColor: '#8B5CF6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#8B5CF6'
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    annotation: {
                        annotations: {
                            normalRange: {
                                type: 'box',
                                yMin: 18.5,
                                yMax: 25,
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                borderWidth: 0
                            }
                        }
                    }
                }
            }
        });
    }

    // Gráfica de Medidas Corporales
    if (document.getElementById('measurementsChart')) {
        new Chart(document.getElementById('measurementsChart'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Cintura (cm)',
                    data: chartData.waists,
                    borderColor: '#14B8A6',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#14B8A6'
                }, {
                    label: 'Cadera (cm)',
                    data: chartData.hips,
                    borderColor: '#F59E0B',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#F59E0B'
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280'
                        }
                    }
                }
            }
        });
    }

    // Gráfica de % Grasa Corporal
    if (document.getElementById('bodyFatChart')) {
        new Chart(document.getElementById('bodyFatChart'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: '% Grasa',
                    data: chartData.bodyFats,
                    borderColor: '#F97316',
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#F97316'
                }]
            },
            options: commonOptions
        });
    }
});
</script>
@endpush
