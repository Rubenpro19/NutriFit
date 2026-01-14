<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atención Nutricional - {{ $paciente->name }}</title>
    <style>
        /* Reset y estilos base */
        @page {
            margin: 8mm 10mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #1f2937;
            background: #ffffff;
        }

        /* Header del documento */
        .header {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            padding: 12px 15px;
            margin-bottom: 12px;
            border-radius: 0 0 8px 8px;
        }

        .header-content {
            display: table;
            width: 100%;
        }

        .header-left {
            display: table-cell;
            vertical-align: middle;
            width: 70%;
        }

        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 30%;
        }

        .logo-text {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .subtitle {
            font-size: 11px;
            opacity: 0.9;
        }

        .attention-number {
            font-size: 12px;
            background: rgba(255,255,255,0.2);
            padding: 6px 12px;
            border-radius: 15px;
            display: inline-block;
        }

        /* Secciones */
        .section {
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .section-title {
            background: #f3f4f6;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: bold;
            color: #059669;
            border-left: 3px solid #059669;
            margin-bottom: 8px;
        }

        .section-content {
            padding: 0 8px;
        }

        /* Cards */
        .card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 8px;
        }

        /* Info del paciente */
        .patient-info {
            display: table;
            width: 100%;
        }

        .patient-photo {
            display: table-cell;
            width: 60px;
            vertical-align: top;
        }

        .patient-photo-circle {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #059669, #10b981);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            color: white;
            font-size: 16px;
            font-weight: bold;
        }

        .patient-photo-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .patient-details {
            display: table-cell;
            vertical-align: top;
            padding-left: 12px;
        }

        .patient-name {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 3px;
        }

        .info-grid {
            display: table;
            width: 100%;
        }

        .info-row {
            display: table-row;
        }

        .info-cell {
            display: table-cell;
            padding: 2px 8px 2px 0;
            width: 50%;
        }

        .info-label {
            color: #6b7280;
            font-size: 9px;
        }

        .info-value {
            font-weight: bold;
            color: #1f2937;
            font-size: 10px;
        }

        /* Tablas */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        th, td {
            padding: 5px 8px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background: #f3f4f6;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            color: #6b7280;
        }

        td {
            font-size: 10px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* Resultados */
        .results-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .result-box {
            display: table-cell;
            width: 33.33%;
            padding: 3px;
            vertical-align: top;
        }

        .result-card {
            background: #f0fdf4;
            border: 1px solid #86efac;
            border-radius: 6px;
            padding: 8px;
            text-align: center;
        }

        .result-card.blue {
            background: #eff6ff;
            border-color: #93c5fd;
        }

        .result-card.purple {
            background: #faf5ff;
            border-color: #d8b4fe;
        }

        .result-card-title {
            font-size: 9px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .result-card.blue .result-card-title {
            color: #2563eb;
        }

        .result-card.purple .result-card-title {
            color: #7c3aed;
        }

        .result-item {
            display: table;
            width: 100%;
            margin-bottom: 3px;
        }

        .result-label {
            display: table-cell;
            text-align: left;
            font-size: 9px;
            color: #6b7280;
        }

        .result-value {
            display: table-cell;
            text-align: right;
            font-weight: bold;
            font-size: 10px;
            color: #059669;
        }

        .result-card.blue .result-value {
            color: #2563eb;
        }

        .result-card.purple .result-value {
            color: #7c3aed;
        }

        .result-highlight {
            background: #dcfce7;
            padding: 5px;
            border-radius: 4px;
            margin-top: 5px;
        }

        .result-card.blue .result-highlight {
            background: #dbeafe;
        }

        /* Equivalentes */
        .equivalents-table th {
            background: #059669;
            color: white;
        }

        .equivalents-table tr:nth-child(even) {
            background: #f9fafb;
        }

        .total-row {
            background: #dcfce7 !important;
            font-weight: bold;
        }

        /* Diagnóstico y recomendaciones */
        .diagnosis-box {
            background: #fef3c7;
            border: 1px solid #fcd34d;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 8px;
        }

        .recommendations-box {
            background: #f0fdf4;
            border: 1px solid #86efac;
            border-radius: 6px;
            padding: 10px;
        }

        .box-title {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 5px;
            color: #1f2937;
        }

        .box-content {
            font-size: 10px;
            line-height: 1.5;
            white-space: pre-wrap;
        }

        /* Footer fijo en la parte inferior */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 8px 10px;
            background: #f9fafb;
            border-top: 2px solid #059669;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
        }

        .nutricionista-info {
            margin-top: 5px;
            font-size: 10px;
            color: #1f2937;
        }
        
        /* Espacio para el footer fijo */
        .content-wrapper {
            margin-bottom: 60px;
            padding: 10px 15px 0 15px;
        }

        /* Progress bars (para PDF usamos divs) */
        .progress-container {
            background: #e5e7eb;
            border-radius: 8px;
            height: 6px;
            margin-top: 2px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            border-radius: 8px;
        }

        .progress-blue { background: #3b82f6; }
        .progress-orange { background: #f97316; }
        .progress-purple { background: #8b5cf6; }

        /* Page break */
        .page-break {
            page-break-before: always;
        }

        /* Fecha de atención */
        .attention-date {
            font-size: 10px;
            color: #6b7280;
            margin-top: 3px;
        }

        /* Macros table */
        .macros-breakdown th {
            background: #374151;
            color: white;
        }

        .macros-breakdown .protein { color: #8b5cf6; }
        .macros-breakdown .fat { color: #f59e0b; }
        .macros-breakdown .carbs { color: #3b82f6; }
    </style>
</head>
<body>
    <div class="content-wrapper">
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <div class="logo-text">🥗 NutriFit</div>
                <div class="subtitle">Informe de Atención Nutricional</div>
                <div class="attention-date">Fecha: {{ $attentionDate }}</div>
            </div>
            <div class="header-right">
                <div class="attention-number">
                    Atención #{{ $attentionNumber }}
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Paciente -->
    <div class="section">
        <div class="section-title">📋 Información del Paciente</div>
        <div class="section-content">
            <div class="card">
                <div class="patient-info">
                    <div class="patient-photo">
                        @if($paciente->personalData?->profile_photo)
                            <img src="{{ public_path('storage/' . $paciente->personalData->profile_photo) }}" 
                                 alt="{{ $paciente->name }}" 
                                 class="patient-photo-img">
                        @else
                            <div class="patient-photo-circle">
                                {{ strtoupper(substr($paciente->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="patient-details">
                        <div class="patient-name">{{ $paciente->name }}</div>
                        <div class="info-grid">
                            <div class="info-row">
                                <div class="info-cell">
                                    <div class="info-label">Correo electrónico</div>
                                    <div class="info-value">{{ $paciente->email }}</div>
                                </div>
                                <div class="info-cell">
                                    <div class="info-label">Teléfono</div>
                                    <div class="info-value">{{ $paciente->personalData?->phone ?? 'No registrado' }}</div>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-cell">
                                    <div class="info-label">Fecha de Nacimiento</div>
                                    <div class="info-value">
                                        @if($paciente->personalData?->birth_date)
                                            {{ \Carbon\Carbon::parse($paciente->personalData->birth_date)->format('d/m/Y') }}
                                            ({{ \Carbon\Carbon::parse($paciente->personalData->birth_date)->age }} años)
                                        @else
                                            No registrado
                                        @endif
                                    </div>
                                </div>
                                <div class="info-cell">
                                    <div class="info-label">Género</div>
                                    <div class="info-value">
                                        @if($paciente->personalData?->gender)
                                            {{ $paciente->personalData->gender === 'male' ? 'Masculino' : 'Femenino' }}
                                        @else
                                            No registrado
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Datos Antropométricos -->
    <div class="section">
        <div class="section-title">📏 Datos Antropométricos</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th>Medida</th>
                        <th class="text-center">Valor</th>
                        <th>Medida</th>
                        <th class="text-center">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Peso</strong></td>
                        <td class="text-center">{{ number_format($attentionData->weight, 1) }} kg</td>
                        <td><strong>Talla</strong></td>
                        <td class="text-center">{{ number_format($attentionData->height, 1) }} cm</td>
                    </tr>
                    <tr>
                        <td><strong>Circ. Cintura</strong></td>
                        <td class="text-center">{{ $attentionData->waist ? number_format($attentionData->waist, 1) . ' cm' : '-' }}</td>
                        <td><strong>Circ. Cadera</strong></td>
                        <td class="text-center">{{ $attentionData->hip ? number_format($attentionData->hip, 1) . ' cm' : '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Circ. Cuello</strong></td>
                        <td class="text-center">{{ $attentionData->neck ? number_format($attentionData->neck, 1) . ' cm' : '-' }}</td>
                        <td><strong>Circ. Muñeca</strong></td>
                        <td class="text-center">{{ $attentionData->wrist ? number_format($attentionData->wrist, 1) . ' cm' : '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Circ. Brazo Contraído</strong></td>
                        <td class="text-center">{{ $attentionData->arm_contracted ? number_format($attentionData->arm_contracted, 1) . ' cm' : '-' }}</td>
                        <td><strong>Circ. Brazo Relajado</strong></td>
                        <td class="text-center">{{ $attentionData->arm_relaxed ? number_format($attentionData->arm_relaxed, 1) . ' cm' : '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Circ. Pierna</strong></td>
                        <td class="text-center">{{ $attentionData->thigh ? number_format($attentionData->thigh, 1) . ' cm' : '-' }}</td>
                        <td><strong>Circ. Pantorrilla</strong></td>
                        <td class="text-center">{{ $attentionData->calf ? number_format($attentionData->calf, 1) . ' cm' : '-' }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Índices calculados -->
            <table style="margin-top: 10px;">
                <thead>
                    <tr>
                        <th>Índice</th>
                        <th class="text-center">Valor</th>
                        <th>Índice</th>
                        <th class="text-center">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>IMC</strong></td>
                        <td class="text-center">
                            {{ number_format($attentionData->bmi, 2) }}
                            @php
                                $bmiCategory = '';
                                if ($attentionData->bmi < 18.5) $bmiCategory = 'Bajo peso';
                                elseif ($attentionData->bmi < 25) $bmiCategory = 'Normal';
                                elseif ($attentionData->bmi < 30) $bmiCategory = 'Sobrepeso';
                                else $bmiCategory = 'Obesidad';
                            @endphp
                            <span style="font-size: 9px; color: #6b7280;">({{ $bmiCategory }})</span>
                        </td>
                        <td><strong>% Grasa Corporal</strong></td>
                        <td class="text-center">{{ $attentionData->body_fat ? number_format($attentionData->body_fat, 1) . '%' : '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>ICC (Cintura/Cadera)</strong></td>
                        <td class="text-center">{{ $attentionData->whr ? number_format($attentionData->whr, 3) : '-' }}</td>
                        <td><strong>ICT (Cintura/Talla)</strong></td>
                        <td class="text-center">{{ $attentionData->wht ? number_format($attentionData->wht, 3) : '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Índice de Complexión</strong></td>
                        <td class="text-center">{{ $attentionData->frame_index ? number_format($attentionData->frame_index, 2) : '-' }}</td>
                        <td><strong>Nivel de Actividad</strong></td>
                        <td class="text-center">
                            @php
                                $activityLevels = [
                                    'sedentary' => 'Sedentario',
                                    'light' => 'Ligera',
                                    'moderate' => 'Moderada',
                                    'active' => 'Activa',
                                    'very_active' => 'Muy Activa'
                                ];
                            @endphp
                            {{ $activityLevels[$attentionData->activity_level] ?? $attentionData->activity_level }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Resultados Nutricionales -->
    <div class="section">
        <div class="section-title">📊 Resultados Nutricionales</div>
        <div class="section-content">
            <div class="results-grid">
                <!-- Datos Metabólicos -->
                <div class="result-box">
                    <div class="result-card blue">
                        <div class="result-card-title">📈 Datos Metabólicos</div>
                        <div class="result-item">
                            <span class="result-label">BMR:</span>
                            <span class="result-value">{{ number_format($attentionData->tmb, 0) }} cal</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">TDEE:</span>
                            <span class="result-value">{{ number_format($attentionData->tdee, 0) }} cal</span>
                        </div>
                        <div class="result-highlight">
                            <div class="result-item">
                                <span class="result-label">Calorías objetivo:</span>
                                <span class="result-value" style="font-size: 14px;">{{ number_format($attentionData->target_calories, 0) }} cal</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Macros Diarias -->
                <div class="result-box">
                    <div class="result-card purple">
                        <div class="result-card-title">🍽️ Macros Diarias</div>
                        <div class="result-item">
                            <span class="result-label">Proteína:</span>
                            <span class="result-value">{{ number_format($attentionData->protein_grams, 0) }}g</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Grasas:</span>
                            <span class="result-value">{{ number_format($attentionData->fat_grams, 0) }}g</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Carbohidratos:</span>
                            <span class="result-value">{{ number_format($attentionData->carbs_grams, 0) }}g</span>
                        </div>
                    </div>
                </div>

                <!-- Desglose de Macros -->
                <div class="result-box">
                    <div class="result-card">
                        <div class="result-card-title">🥧 Desglose de Macros</div>
                        @php
                            $proteinKcal = $attentionData->protein_grams * 4;
                            $fatKcal = $attentionData->fat_grams * 9;
                            $carbsKcal = $attentionData->carbs_grams * 4;
                            $totalKcal = $proteinKcal + $fatKcal + $carbsKcal;
                            $proteinPct = $totalKcal > 0 ? round(($proteinKcal / $totalKcal) * 100) : 0;
                            $fatPct = $totalKcal > 0 ? round(($fatKcal / $totalKcal) * 100) : 0;
                            $carbsPct = $totalKcal > 0 ? round(($carbsKcal / $totalKcal) * 100) : 0;
                        @endphp
                        <div class="result-item">
                            <span class="result-label">Proteína ({{ $proteinPct }}%):</span>
                            <span class="result-value">{{ number_format($proteinKcal, 0) }} cal</span>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar progress-blue" style="width: {{ $proteinPct }}%"></div>
                        </div>
                        
                        <div class="result-item" style="margin-top: 8px;">
                            <span class="result-label">Grasas ({{ $fatPct }}%):</span>
                            <span class="result-value">{{ number_format($fatKcal, 0) }} cal</span>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar progress-orange" style="width: {{ $fatPct }}%"></div>
                        </div>
                        
                        <div class="result-item" style="margin-top: 8px;">
                            <span class="result-label">Carbohidratos ({{ $carbsPct }}%):</span>
                            <span class="result-value">{{ number_format($carbsKcal, 0) }} cal</span>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar progress-purple" style="width: {{ $carbsPct }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Distribución de Equivalentes -->
    @if($attentionData->eq_cereales !== null || $attentionData->eq_verduras !== null || $attentionData->eq_frutas !== null)
    <div class="section">
        <div class="section-title">🍽️ Distribución de Equivalentes</div>
        <div class="section-content">
            <table class="equivalents-table">
                <thead>
                    <tr>
                        <th>Grupo de Alimentos</th>
                        <th class="text-center">Equivalentes</th>
                        <th class="text-center">Kcal/Eq</th>
                        <th class="text-center">Total Kcal</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $equivalentValues = [
                            ['name' => 'Cereales', 'value' => $attentionData->eq_cereales, 'kcal' => 73],
                            ['name' => 'Verduras', 'value' => $attentionData->eq_verduras, 'kcal' => 24],
                            ['name' => 'Frutas', 'value' => $attentionData->eq_frutas, 'kcal' => 60],
                            ['name' => 'Lácteo Semidescremado', 'value' => $attentionData->eq_lacteo, 'kcal' => 111],
                            ['name' => 'Origen Animal', 'value' => $attentionData->eq_animal, 'kcal' => 46],
                            ['name' => 'Aceites y Grasas', 'value' => $attentionData->eq_aceites, 'kcal' => 45],
                            ['name' => 'Grasas con Proteína', 'value' => $attentionData->eq_grasas_prot, 'kcal' => 69],
                            ['name' => 'Leguminosas', 'value' => $attentionData->eq_leguminosas, 'kcal' => 121],
                        ];
                        $totalEquivalentKcal = 0;
                    @endphp
                    @foreach($equivalentValues as $eq)
                        @if($eq['value'] !== null && $eq['value'] > 0)
                            @php
                                $eqTotal = $eq['value'] * $eq['kcal'];
                                $totalEquivalentKcal += $eqTotal;
                            @endphp
                            <tr>
                                <td>{{ $eq['name'] }}</td>
                                <td class="text-center">{{ number_format($eq['value'], 1) }}</td>
                                <td class="text-center">{{ $eq['kcal'] }}</td>
                                <td class="text-center"><strong>{{ number_format($eqTotal, 0) }}</strong></td>
                            </tr>
                        @endif
                    @endforeach
                    <tr class="total-row">
                        <td colspan="3"><strong>TOTAL CALORÍAS EQUIVALENTES</strong></td>
                        <td class="text-center"><strong>{{ number_format($attentionData->total_calories_equivalents ?? $totalEquivalentKcal, 0) }} kcal</strong></td>
                    </tr>
                    <tr style="background: #fef3c7;">
                        <td colspan="3"><strong>CALORÍAS OBJETIVO</strong></td>
                        <td class="text-center"><strong>{{ number_format($attentionData->target_calories, 0) }} kcal</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Desglose de Macronutrientes por Grupo -->
    @if($attentionData->eq_cereales !== null || $attentionData->eq_verduras !== null)
    <div class="section">
        <div class="section-title">📋 Desglose de Macronutrientes por Grupo</div>
        <div class="section-content">
            <table class="macros-breakdown">
                <thead>
                    <tr>
                        <th>Grupo de Alimentos</th>
                        <th class="text-center">Proteínas (g)</th>
                        <th class="text-center">Grasas (g)</th>
                        <th class="text-center">Carbohidratos (g)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Valores de macros por equivalente
                        $macroValues = [
                            'cereales' => ['prot' => 2, 'fat' => 0.5, 'carbs' => 15, 'value' => $attentionData->eq_cereales ?? 0],
                            'verduras' => ['prot' => 2, 'fat' => 0, 'carbs' => 4, 'value' => $attentionData->eq_verduras ?? 0],
                            'frutas' => ['prot' => 0, 'fat' => 0, 'carbs' => 15, 'value' => $attentionData->eq_frutas ?? 0],
                            'lacteo' => ['prot' => 9, 'fat' => 4, 'carbs' => 12, 'value' => $attentionData->eq_lacteo ?? 0],
                            'animal' => ['prot' => 7, 'fat' => 1.5, 'carbs' => 0, 'value' => $attentionData->eq_animal ?? 0],
                            'aceites' => ['prot' => 0, 'fat' => 5, 'carbs' => 0, 'value' => $attentionData->eq_aceites ?? 0],
                            'grasas_prot' => ['prot' => 3, 'fat' => 5, 'carbs' => 3, 'value' => $attentionData->eq_grasas_prot ?? 0],
                            'leguminosas' => ['prot' => 8, 'fat' => 1, 'carbs' => 20, 'value' => $attentionData->eq_leguminosas ?? 0],
                        ];
                        
                        $totalProt = 0;
                        $totalFat = 0;
                        $totalCarbs = 0;
                        
                        $groups = [
                            ['name' => 'Cereales', 'key' => 'cereales'],
                            ['name' => 'Verduras', 'key' => 'verduras'],
                            ['name' => 'Frutas', 'key' => 'frutas'],
                            ['name' => 'Lácteo Semidescremado', 'key' => 'lacteo'],
                            ['name' => 'Origen Animal', 'key' => 'animal'],
                            ['name' => 'Aceites y Grasas', 'key' => 'aceites'],
                            ['name' => 'Grasas con Proteína', 'key' => 'grasas_prot'],
                            ['name' => 'Leguminosas', 'key' => 'leguminosas'],
                        ];
                    @endphp
                    @foreach($groups as $group)
                        @php
                            $mv = $macroValues[$group['key']];
                            $prot = $mv['prot'] * $mv['value'];
                            $fat = $mv['fat'] * $mv['value'];
                            $carbs = $mv['carbs'] * $mv['value'];
                            $totalProt += $prot;
                            $totalFat += $fat;
                            $totalCarbs += $carbs;
                        @endphp
                        @if($mv['value'] > 0)
                        <tr>
                            <td>{{ $group['name'] }}</td>
                            <td class="text-center protein">{{ number_format($prot, 1) }}</td>
                            <td class="text-center fat">{{ number_format($fat, 1) }}</td>
                            <td class="text-center carbs">{{ number_format($carbs, 1) }}</td>
                        </tr>
                        @endif
                    @endforeach
                    <tr class="total-row">
                        <td><strong>TOTAL EQUIVALENTES</strong></td>
                        <td class="text-center"><strong>{{ number_format($totalProt, 1) }}g</strong></td>
                        <td class="text-center"><strong>{{ number_format($totalFat, 1) }}g</strong></td>
                        <td class="text-center"><strong>{{ number_format($totalCarbs, 1) }}g</strong></td>
                    </tr>
                    <tr style="background: #fef3c7;">
                        <td><strong>OBJETIVO</strong></td>
                        <td class="text-center"><strong>{{ number_format($attentionData->protein_grams, 1) }}g</strong></td>
                        <td class="text-center"><strong>{{ number_format($attentionData->fat_grams, 1) }}g</strong></td>
                        <td class="text-center"><strong>{{ number_format($attentionData->carbs_grams, 1) }}g</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Diagnóstico y Recomendaciones -->
    <div class="section">
        <div class="section-title">💊 Diagnóstico y Recomendaciones</div>
        <div class="section-content">
            @if($attention->diagnosis)
            <div class="diagnosis-box">
                <div class="box-title">📋 Diagnóstico:</div>
                <div class="box-content">{{ $attention->diagnosis }}</div>
            </div>
            @endif

            @if($attention->recommendations)
            <div class="recommendations-box">
                <div class="box-title">✅ Recomendaciones:</div>
                <div class="box-content">{{ $attention->recommendations }}</div>
            </div>
            @endif
        </div>
    </div>
    </div><!-- Fin content-wrapper -->

    <!-- Footer -->
    <div class="footer">
        <div class="nutricionista-info">
            <strong>Atendido por:</strong> {{ $nutricionista->name }}<br>
            @if($nutricionista->email)
                <span style="color: #059669;">✉</span> {{ $nutricionista->email }}
            @endif
            @if($nutricionista->personalData?->phone)
                &nbsp;&nbsp;|&nbsp;&nbsp;<span style="color: #059669;">📞</span> {{ $nutricionista->personalData->phone }}
            @endif
        </div>
        <div style="margin-top: 10px; font-size: 9px; color: #9ca3af;">
            Documento generado automáticamente por NutriFit el {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
</body>
</html>
