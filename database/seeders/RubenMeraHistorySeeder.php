<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\AppointmentState;
use App\Models\Attention;
use App\Models\AttentionData;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RubenMeraHistorySeeder extends Seeder
{
    /**
     * Crea un historial clínico realista para Ruben Mera
     * mostrando progreso de pérdida de peso a lo largo de 5 consultas.
     */
    public function run(): void
    {
        $ruben        = User::where('email', 'dariomera05@gmail.com')->firstOrFail();
        $nutricionista = User::where('email', 'dariomera911@gmail.com')->firstOrFail();
        $completada   = AppointmentState::where('name', 'completada')->firstOrFail();

        // Datos de cada visita: fecha, medidas y notas clínicas
        // Paciente masculino, 28 años, altura 1.72 m
        // Objetivo: pérdida de peso (déficit calórico)
        $visitas = [
            /* -------------------------------------------------------
             * Visita 1 – 5 Sep 2025 – Primera consulta
             * Diagnóstico: Obesidad grado I (IMC 32.11)
             * ------------------------------------------------------- */
            [
                'date'              => '2025-09-05',
                'appointment_type'  => 'primera_vez',
                'reason'            => 'Primera consulta nutricional – evaluación inicial',
                'price'             => 35.00,
                'diagnosis'         => 'Paciente masculino de 28 años con obesidad grado I (IMC 32.11). Presenta distribución de grasa abdominal central (RCC 0.944). Se decide iniciar plan de pérdida de peso progresiva con déficit calórico moderado y actividad física regular.',
                'recommendations'   => 'Seguir plan alimentario con déficit de 500 kcal/día. Iniciar caminata 30 min/día, 5 días a la semana. Aumentar consumo de agua a 2.5 L/día. Limitar ultraprocesados, harinas refinadas y bebidas azucaradas. Siguiente control en 5 semanas.',
                // Medidas antropométricas
                'weight'            => 95.00,
                'height'            => 172.00,
                'waist'             => 102.00,
                'hip'               => 108.00,
                'neck'              => 40.00,
                'wrist'             => 18.00,
                'arm_contracted'    => 36.00,
                'arm_relaxed'       => 33.00,
                'thigh'             => 62.00,
                'calf'              => 38.00,
                // Nivel e índices
                'activity_level'    => 'light',
                'nutrition_goal'    => 'deficit',
                'bmi'               => 32.11,
                'body_fat'          => 32.00,
                'tmb'               => 2027.55,
                'tdee'              => 3142.70,
                'whr'               => 0.944,
                'wht'               => 0.593,
                'frame_index'       => 10.50,
                // Macros
                'target_calories'   => 2643.00,
                'protein_grams'     => 198.25,
                'fat_grams'         =>  88.10,
                'carbs_grams'       => 264.30,
                'protein_percentage'=> 30.00,
                'fat_percentage'    => 30.00,
                'carbs_percentage'  => 40.00,
                // Equivalentes alimentarios
                'eq_cereales'       => 10,
                'eq_verduras'       =>  5,
                'eq_frutas'         =>  3,
                'eq_lacteo'         =>  2,
                'eq_animal'         =>  5,
                'eq_aceites'        =>  4,
                'eq_grasas_prot'    =>  1,
                'eq_leguminosas'    =>  1,
                'total_calories_equivalents' => 2640.00,
            ],

            /* -------------------------------------------------------
             * Visita 2 – 10 Oct 2025 – Primer seguimiento
             * Diagnóstico: Obesidad grado I, pérdida de 4 kg en 5 semanas
             * ------------------------------------------------------- */
            [
                'date'              => '2025-10-10',
                'appointment_type'  => 'seguimiento',
                'reason'            => 'Control mensual – evolución del plan nutricional',
                'price'             => 30.00,
                'diagnosis'         => 'Excelente adherencia al plan. Pérdida de 4 kg en 5 semanas (-0.8 kg/sem). IMC 30.76 – mantiene categoría obesidad grado I pero con tendencia favorable. Reducción de perímetro abdominal de 4 cm. Se mantiene el plan con ajuste de macros.',
                'recommendations'   => 'Mantener déficit calórico. Incrementar actividad aeróbica a 45 min/día. Incorporar 2 sesiones de fuerza/semana. Continuar hidratación adecuada. Reforzar consumo de proteína magra en cada comida.',
                'weight'            => 91.00,
                'height'            => 172.00,
                'waist'             =>  98.00,
                'hip'               => 104.00,
                'neck'              =>  39.00,
                'wrist'             =>  17.80,
                'arm_contracted'    =>  35.50,
                'arm_relaxed'       =>  32.50,
                'thigh'             =>  60.00,
                'calf'              =>  37.50,
                'activity_level'    => 'moderate',
                'nutrition_goal'    => 'deficit',
                'bmi'               => 30.76,
                'body_fat'          => 29.50,
                'tmb'               => 1973.96,
                'tdee'              => 3059.64,
                'whr'               =>  0.942,
                'wht'               =>  0.570,
                'frame_index'       => 10.50,
                'target_calories'   => 2560.00,
                'protein_grams'     => 192.00,
                'fat_grams'         =>  85.33,
                'carbs_grams'       => 256.00,
                'protein_percentage'=> 30.00,
                'fat_percentage'    => 30.00,
                'carbs_percentage'  => 40.00,
                'eq_cereales'       =>  9,
                'eq_verduras'       =>  5,
                'eq_frutas'         =>  3,
                'eq_lacteo'         =>  2,
                'eq_animal'         =>  5,
                'eq_aceites'        =>  3,
                'eq_grasas_prot'    =>  1,
                'eq_leguminosas'    =>  1,
                'total_calories_equivalents' => 2555.00,
            ],

            /* -------------------------------------------------------
             * Visita 3 – 14 Nov 2025 – Segundo seguimiento
             * Diagnóstico: Sobrepeso (IMC 29.57) – abandona categoría obesidad
             * ------------------------------------------------------- */
            [
                'date'              => '2025-11-14',
                'appointment_type'  => 'seguimiento',
                'reason'            => 'Control mensual – segundo seguimiento',
                'price'             => 30.00,
                'diagnosis'         => 'El paciente logra abandonar la categoría de obesidad. IMC 29.57 – sobrepeso. Reducción total de 7.5 kg desde la consulta inicial. Grasa corporal disminuyó del 32% al 27%. Muy buena adherencia al ejercicio. Se ajusta el plan.',
                'recommendations'   => 'Ajustar plan nutricional según nuevo TDEE. Aumentar ingesta proteica a 35% para preservar masa muscular durante el déficit. Mantener entrenamiento de fuerza 2-3 veces/semana. Evitar saltarse comidas.',
                'weight'            => 87.50,
                'height'            => 172.00,
                'waist'             =>  94.00,
                'hip'               => 100.00,
                'neck'              =>  38.50,
                'wrist'             =>  17.50,
                'arm_contracted'    =>  35.00,
                'arm_relaxed'       =>  32.00,
                'thigh'             =>  58.00,
                'calf'              =>  37.00,
                'activity_level'    => 'moderate',
                'nutrition_goal'    => 'deficit',
                'bmi'               => 29.57,
                'body_fat'          => 27.00,
                'tmb'               => 1927.07,
                'tdee'              => 2986.96,
                'whr'               =>  0.940,
                'wht'               =>  0.547,
                'frame_index'       => 10.50,
                'target_calories'   => 2487.00,
                'protein_grams'     => 217.62,
                'fat_grams'         =>  82.90,
                'carbs_grams'       => 217.62,
                'protein_percentage'=> 35.00,
                'fat_percentage'    => 30.00,
                'carbs_percentage'  => 35.00,
                'eq_cereales'       =>  8,
                'eq_verduras'       =>  5,
                'eq_frutas'         =>  3,
                'eq_lacteo'         =>  2,
                'eq_animal'         =>  6,
                'eq_aceites'        =>  3,
                'eq_grasas_prot'    =>  1,
                'eq_leguminosas'    =>  1,
                'total_calories_equivalents' => 2480.00,
            ],

            /* -------------------------------------------------------
             * Visita 4 – 19 Dic 2025 – Tercer seguimiento
             * Diagnóstico: Sobrepeso (IMC 28.39) – progreso constante
             * ------------------------------------------------------- */
            [
                'date'              => '2025-12-19',
                'appointment_type'  => 'seguimiento',
                'reason'            => 'Control mensual – tercer seguimiento',
                'price'             => 30.00,
                'diagnosis'         => 'Progreso sostenido. Pérdida total de 11 kg desde el inicio. IMC 28.39 – sobrepeso. Grasa corporal en 24.5%. Perímetro de cintura reducido 12 cm. Paciente refiere mayor energía y mejor calidad del sueño. Se mantiene estrategia.',
                'recommendations'   => 'Continuar con el plan actual. Incorporar entrenamiento de intervalos (HIIT) 1 vez/semana. Realizar análisis de laboratorio (hemograma, glucosa, perfil lipídico). Revisar porción de carbohidratos en la cena.',
                'weight'            => 84.00,
                'height'            => 172.00,
                'waist'             =>  90.00,
                'hip'               =>  96.00,
                'neck'              =>  38.00,
                'wrist'             =>  17.50,
                'arm_contracted'    =>  34.50,
                'arm_relaxed'       =>  31.50,
                'thigh'             =>  56.00,
                'calf'              =>  36.50,
                'activity_level'    => 'active',
                'nutrition_goal'    => 'deficit',
                'bmi'               => 28.39,
                'body_fat'          => 24.50,
                'tmb'               => 1880.18,
                'tdee'              => 2914.28,
                'whr'               =>  0.938,
                'wht'               =>  0.523,
                'frame_index'       => 10.50,
                'target_calories'   => 2414.00,
                'protein_grams'     => 211.23,
                'fat_grams'         =>  80.47,
                'carbs_grams'       => 211.22,
                'protein_percentage'=> 35.00,
                'fat_percentage'    => 30.00,
                'carbs_percentage'  => 35.00,
                'eq_cereales'       =>  7,
                'eq_verduras'       =>  5,
                'eq_frutas'         =>  3,
                'eq_lacteo'         =>  2,
                'eq_animal'         =>  6,
                'eq_aceites'        =>  3,
                'eq_grasas_prot'    =>  1,
                'eq_leguminosas'    =>  1,
                'total_calories_equivalents' => 2410.00,
            ],

            /* -------------------------------------------------------
             * Visita 5 – 23 Ene 2026 – Cuarto seguimiento
             * Diagnóstico: Sobrepeso (IMC 27.04) – cerca del peso normal
             * ------------------------------------------------------- */
            [
                'date'              => '2026-01-23',
                'appointment_type'  => 'seguimiento',
                'reason'            => 'Control mensual – cuarto seguimiento',
                'price'             => 30.00,
                'diagnosis'         => 'Excelente evolución. Pérdida total de 15 kg en 4.5 meses (-3.3 kg/mes). IMC 27.04 – sobrepeso leve, muy cerca del rango normal (<25). Grasa corporal en 21.5%. Cintura en 86 cm (riesgo reducido). Se plantea transición gradual a mantenimiento.',
                'recommendations'   => 'Reducir gradualmente el déficit calórico hacia mantenimiento en las próximas 4 semanas. Establecer "refeed day" semanal. Mantener entrenamiento de fuerza 3 veces/semana + cardio moderado. Próxima consulta en 6 semanas para evaluar transición a mantenimiento.',
                'weight'            => 80.00,
                'height'            => 172.00,
                'waist'             =>  86.00,
                'hip'               =>  92.00,
                'neck'              =>  37.50,
                'wrist'             =>  17.20,
                'arm_contracted'    =>  34.00,
                'arm_relaxed'       =>  31.00,
                'thigh'             =>  54.00,
                'calf'              =>  36.00,
                'activity_level'    => 'active',
                'nutrition_goal'    => 'deficit',
                'bmi'               => 27.04,
                'body_fat'          => 21.50,
                'tmb'               => 1826.59,
                'tdee'              => 2831.21,
                'whr'               =>  0.935,
                'wht'               =>  0.500,
                'frame_index'       => 10.50,
                'target_calories'   => 2331.00,
                'protein_grams'     => 203.96,
                'fat_grams'         =>  77.70,
                'carbs_grams'       => 203.96,
                'protein_percentage'=> 35.00,
                'fat_percentage'    => 30.00,
                'carbs_percentage'  => 35.00,
                'eq_cereales'       =>  6,
                'eq_verduras'       =>  5,
                'eq_frutas'         =>  3,
                'eq_lacteo'         =>  2,
                'eq_animal'         =>  6,
                'eq_aceites'        =>  3,
                'eq_grasas_prot'    =>  1,
                'eq_leguminosas'    =>  1,
                'total_calories_equivalents' => 2328.00,
            ],
        ];

        foreach ($visitas as $visita) {
            $date = Carbon::parse($visita['date']);

            // 1. Crear la cita
            $appointment = Appointment::create([
                'paciente_id'          => $ruben->id,
                'nutricionista_id'     => $nutricionista->id,
                'appointment_state_id' => $completada->id,
                'start_time'           => $date->copy()->setTime(9, 0),
                'end_time'             => $date->copy()->setTime(9, 45),
                'reason'               => $visita['reason'],
                'appointment_type'     => $visita['appointment_type'],
                'price'                => $visita['price'],
                'notes'                => null,
            ]);

            // 2. Crear la atención
            $attention = Attention::create([
                'appointment_id'  => $appointment->id,
                'paciente_id'     => $ruben->id,
                'nutricionista_id'=> $nutricionista->id,
                'diagnosis'       => $visita['diagnosis'],
                'recommendations' => $visita['recommendations'],
            ]);

            // 3. Crear los datos antropométricos y nutricionales
            AttentionData::create([
                'attention_id'               => $attention->id,
                'weight'                     => $visita['weight'],
                'height'                     => $visita['height'],
                'waist'                      => $visita['waist'],
                'hip'                        => $visita['hip'],
                'neck'                       => $visita['neck'],
                'wrist'                      => $visita['wrist'],
                'arm_contracted'             => $visita['arm_contracted'],
                'arm_relaxed'                => $visita['arm_relaxed'],
                'thigh'                      => $visita['thigh'],
                'calf'                       => $visita['calf'],
                'activity_level'             => $visita['activity_level'],
                'nutrition_goal'             => $visita['nutrition_goal'],
                'bmi'                        => $visita['bmi'],
                'body_fat'                   => $visita['body_fat'],
                'tmb'                        => $visita['tmb'],
                'tdee'                       => $visita['tdee'],
                'whr'                        => $visita['whr'],
                'wht'                        => $visita['wht'],
                'frame_index'                => $visita['frame_index'],
                'target_calories'            => $visita['target_calories'],
                'protein_grams'              => $visita['protein_grams'],
                'fat_grams'                  => $visita['fat_grams'],
                'carbs_grams'                => $visita['carbs_grams'],
                'protein_percentage'         => $visita['protein_percentage'],
                'fat_percentage'             => $visita['fat_percentage'],
                'carbs_percentage'           => $visita['carbs_percentage'],
                'eq_cereales'                => $visita['eq_cereales'],
                'eq_verduras'                => $visita['eq_verduras'],
                'eq_frutas'                  => $visita['eq_frutas'],
                'eq_lacteo'                  => $visita['eq_lacteo'],
                'eq_animal'                  => $visita['eq_animal'],
                'eq_aceites'                 => $visita['eq_aceites'],
                'eq_grasas_prot'             => $visita['eq_grasas_prot'],
                'eq_leguminosas'             => $visita['eq_leguminosas'],
                'total_calories_equivalents' => $visita['total_calories_equivalents'],
            ]);
        }
    }
}
