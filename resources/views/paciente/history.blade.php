@extends('layouts.app')

@section('title', 'Mi Historial Clínico - NutriFit')

@section('content')
<body class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
    @include('layouts.header')

    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <!-- Breadcrumb -->
            <nav class="mb-6 flex items-center gap-2 text-sm">
                <a href="{{ route('paciente.dashboard') }}" class="text-green-600 dark:text-green-400 hover:underline transition-colors">Inicio</a>
                <span class="material-symbols-outlined text-gray-400 text-sm">chevron_right</span>
                <span class="text-gray-700 dark:text-gray-300 font-medium">Mi Historial Clínico</span>
            </nav>

            <!-- Header -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full overflow-hidden bg-white flex items-center justify-center shadow-lg">
                            @if($paciente->personalData?->profile_photo)
                                <img src="{{ asset('storage/' . $paciente->personalData->profile_photo) }}" 
                                     alt="{{ $paciente->name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <span class="text-green-600 text-xl font-bold">{{ $paciente->initials() }}</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                                <span class="material-symbols-outlined">monitoring</span>
                                Mi Historial Clínico
                            </h1>
                            <p class="text-green-100">Seguimiento de tu progreso nutricional</p>
                        </div>
                        <div class="hidden sm:flex items-center gap-2 text-white bg-white/20 px-4 py-2 rounded-lg">
                            <span class="material-symbols-outlined">calendar_month</span>
                            <span class="font-semibold">{{ $attentions->count() }} consultas</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($attentions->isEmpty())
                <!-- Sin datos -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center border border-gray-200 dark:border-gray-700">
                    <span class="material-symbols-outlined text-6xl text-gray-400 dark:text-gray-500 mb-4">analytics</span>
                    <h3 class="text-xl font-bold text-gray-700 dark:text-gray-300 mb-2">Aún no tienes historial clínico</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Tu historial se creará después de tu primera consulta nutricional.</p>
                    <a href="{{ route('paciente.booking.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all">
                        <span class="material-symbols-outlined">calendar_add_on</span>
                        Agendar mi primera cita
                    </a>
                </div>
            @else
                <!-- Tarjetas de Progreso -->
                @if($progressStats)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <!-- Peso -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-gray-600 dark:text-gray-400 text-sm font-medium">Mi Peso Actual</span>
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
                            <span class="flex items-center gap-1 text-sm {{ $isPositive ? 'text-amber-500' : ($weightChange < 0 ? 'text-green-500' : 'text-gray-500') }}">
                                <span class="material-symbols-outlined text-sm">{{ $isPositive ? 'trending_up' : ($weightChange < 0 ? 'trending_down' : 'trending_flat') }}</span>
                                {{ $weightChange > 0 ? '+' : '' }}{{ $weightChange }} kg
                            </span>
                            <span class="text-gray-400 text-xs">desde el inicio</span>
                        </div>
                    </div>

                    <!-- IMC -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-gray-600 dark:text-gray-400 text-sm font-medium">Mi IMC</span>
                            <span class="material-symbols-outlined text-purple-500">monitor_weight</span>
                        </div>
                        <div class="flex items-end gap-2">
                            @php
                                $bmi = $progressStats['bmi']['current'];
                                $bmiStatus = $bmi < 18.5 ? ['Bajo peso', 'text-blue-600'] : ($bmi < 25 ? ['Normal', 'text-green-600'] : ($bmi < 30 ? ['Sobrepeso', 'text-amber-600'] : ['Obesidad', 'text-red-600']));
                            @endphp
                            <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($bmi, 1) }}</span>
                        </div>
                        <div class="mt-2">
                            <span class="text-sm font-medium {{ $bmiStatus[1] }}">{{ $bmiStatus[0] }}</span>
                        </div>
                    </div>

                    <!-- Consultas -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-gray-600 dark:text-gray-400 text-sm font-medium">Total Consultas</span>
                            <span class="material-symbols-outlined text-green-500">clinical_notes</span>
                        </div>
                        <div class="flex items-end gap-2">
                            <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $progressStats['total_attentions'] }}</span>
                        </div>
                        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Desde {{ $progressStats['first_date'] }}
                        </div>
                    </div>

                    <!-- % Grasa o Calorías -->
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
                            <span class="flex items-center gap-1 text-sm {{ $fatChange > 0 ? 'text-amber-500' : ($fatChange < 0 ? 'text-green-500' : 'text-gray-500') }}">
                                <span class="material-symbols-outlined text-sm">{{ $fatChange > 0 ? 'trending_up' : ($fatChange < 0 ? 'trending_down' : 'trending_flat') }}</span>
                                {{ $fatChange > 0 ? '+' : '' }}{{ $fatChange }}%
                            </span>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-gray-600 dark:text-gray-400 text-sm font-medium">Última Consulta</span>
                            <span class="material-symbols-outlined text-teal-500">event</span>
                        </div>
                        <div class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $progressStats['last_date'] }}
                        </div>
                        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            ¡Sigue así!
                        </div>
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
                            Mi Evolución de Peso
                        </h3>
                        <div class="h-64">
                            <canvas id="weightChart"></canvas>
                        </div>
                    </div>

                    <!-- Gráfica de IMC -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-purple-500">monitor_weight</span>
                            Mi Evolución del IMC
                        </h3>
                        <div class="h-64">
                            <canvas id="bmiChart"></canvas>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-3 justify-center text-xs">
                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-blue-400"></span> Bajo peso (&lt;18.5)</span>
                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-green-400"></span> Normal (18.5-25)</span>
                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-amber-400"></span> Sobrepeso (25-30)</span>
                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-red-400"></span> Obesidad (&gt;30)</span>
                        </div>
                    </div>

                    <!-- Gráfica de Medidas Corporales -->
                    @if(array_filter($chartData['waists']))
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-teal-500">straighten</span>
                            Mis Medidas Corporales
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
                            Mi % Grasa Corporal
                        </h3>
                        <div class="h-64">
                            <canvas id="bodyFatChart"></canvas>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Historial de Consultas -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-500">history</span>
                            Mi Historial de Consultas
                        </h3>
                    </div>
                    
                    <!-- Vista móvil: Tarjetas -->
                    <div class="block sm:hidden p-4 space-y-4">
                        @foreach($attentions->reverse() as $attention)
                            @php $data = $attention->attentionData; @endphp
                            <div class="bg-gray-50 dark:bg-gray-750 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $attention->created_at->format('d/m/Y') }}</span>
                                    @if($data->nutrition_goal)
                                        @php
                                            $goalColors = [
                                                'deficit' => 'text-red-600 bg-red-100',
                                                'maintenance' => 'text-blue-600 bg-blue-100',
                                                'surplus' => 'text-green-600 bg-green-100',
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
                                    @endif
                                </div>
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Peso:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white ml-1">{{ $data->weight }} kg</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">IMC:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white ml-1">{{ number_format($data->bmi, 1) }}</span>
                                    </div>
                                    @if($data->body_fat)
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">% Grasa:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white ml-1">{{ number_format($data->body_fat, 1) }}%</span>
                                    </div>
                                    @endif
                                    @if($data->target_calories)
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Kcal:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white ml-1">{{ number_format($data->target_calories, 0) }}</span>
                                    </div>
                                    @endif
                                </div>
                                @if($attention->nutricionista)
                                <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600 text-xs text-gray-500 dark:text-gray-400">
                                    Atendido por: <span class="font-medium">{{ $attention->nutricionista->name }}</span>
                                </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Vista desktop: Tabla -->
                    <div class="hidden sm:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Nutricionista</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Peso</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">IMC</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">% Grasa</th>
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
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            {{ $attention->nutricionista?->name ?? '-' }}
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
    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#9CA3AF' : '#6B7280';
    const gridColor = isDark ? '#374151' : '#E5E7EB';
    
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
                    color: textColor
                }
            },
            y: {
                grid: {
                    color: gridColor
                },
                ticks: {
                    color: textColor
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
                    pointRadius: 6,
                    pointBackgroundColor: '#3B82F6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
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
                    pointRadius: 6,
                    pointBackgroundColor: '#8B5CF6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: commonOptions
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
                    pointRadius: 5,
                    pointBackgroundColor: '#14B8A6'
                }, {
                    label: 'Cadera (cm)',
                    data: chartData.hips,
                    borderColor: '#F59E0B',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 5,
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
                            color: textColor
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
                    pointRadius: 6,
                    pointBackgroundColor: '#F97316',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: commonOptions
        });
    }
});
</script>
@endpush
