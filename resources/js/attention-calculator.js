/**
 * Calculadora Antropométrica para Registro de Atenciones
 * NutriFit - Sistema de gestión nutricional
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inputs
    const weightInputDisplay = document.getElementById('weight-input');
    const weightUnit = document.getElementById('weight-unit');
    const weightInput = document.getElementById('weight');
    const heightInput = document.getElementById('height');
    const waistInput = document.getElementById('waist');
    const hipInput = document.getElementById('hip');
    const neckInput = document.getElementById('neck');
    const wristInput = document.getElementById('wrist');
    const activityLevel = document.getElementById('activity_level');
    const nutritionGoal = document.getElementById('nutrition_goal');
    const bmiInput = document.getElementById('bmi');
    const bodyFatInput = document.getElementById('body_fat');

    // Datos del paciente
    const gender = document.getElementById('patient-gender').value;
    const age = parseInt(document.getElementById('patient-age').value);

    // Displays
    const displayBmi = document.getElementById('display-bmi');
    const bmiIndicator = document.getElementById('bmi-indicator');
    const bmiCategory = document.getElementById('bmi-category');
    const displayTmb = document.getElementById('display-tmb');
    const displayTdee = document.getElementById('display-tdee');
    const tdeeDescription = document.getElementById('tdee-description');
    const displayWhr = document.getElementById('display-whr');
    const whrCategory = document.getElementById('whr-category');
    const displayWht = document.getElementById('display-wht');
    const whtCategory = document.getElementById('wht-category');
    const displayFrame = document.getElementById('display-frame');
    const frameCategory = document.getElementById('frame-category');
    const displayBodyfat = document.getElementById('display-bodyfat');
    const bodyfatCategory = document.getElementById('bodyfat-category');

    /**
     * Convierte peso de libras a kilogramos si es necesario
     */
    function convertWeight(weightDisplay, unit) {
        let weight = weightDisplay;
        if (unit === 'lb') {
            weight = weightDisplay * 0.453592;
        }
        return weight;
    }

    /**
     * Calcula el IMC (Índice de Masa Corporal)
     */
    function calculateBMI(weight, height) {
        const heightM = height / 100;
        return weight / (heightM * heightM);
    }

    /**
     * Obtiene la categoría y color del IMC
     */
    function getBMICategory(bmi) {
        let category, color, percentage;
        
        if (bmi < 18.5) {
            category = 'Bajo peso - Riesgo de desnutrición';
            color = 'text-blue-600';
            percentage = (bmi / 18.5) * 25;
        } else if (bmi < 25) {
            category = 'Peso normal - Saludable';
            color = 'text-green-600';
            percentage = 25 + ((bmi - 18.5) / 6.5) * 25;
        } else if (bmi < 30) {
            category = 'Sobrepeso - Riesgo aumentado';
            color = 'text-yellow-600';
            percentage = 50 + ((bmi - 25) / 5) * 25;
        } else if (bmi < 35) {
            category = 'Obesidad I - Riesgo moderado';
            color = 'text-orange-600';
            percentage = 75 + ((bmi - 30) / 5) * 12.5;
        } else {
            category = 'Obesidad II/III - Riesgo alto';
            color = 'text-red-600';
            percentage = Math.min(87.5 + ((bmi - 35) / 10) * 12.5, 100);
        }

        return { category, color, percentage };
    }

    /**
     * Actualiza la visualización del IMC
     */
    function updateBMIDisplay(bmi) {
        const bmiValue = bmi.toFixed(2);
        bmiInput.value = bmiValue;
        displayBmi.textContent = bmiValue;
        
        const { category, color, percentage } = getBMICategory(bmi);
        
        // Construir texto de categoría
        let categoryText = category;
        
        // Advertencia para menores de 18 años
        if (age < 18) {
            categoryText += ' | ⚠️ Nota: En menores de 18 años, este resultado es aproximado y no sustituye una evaluación pediátrica.';
        }
        
        bmiCategory.textContent = categoryText;
        bmiCategory.className = `text-xs font-medium ${color}`;
        bmiIndicator.style.width = percentage + '%';

        updateBMIAvatar(bmi);
    }

    /**
     * Estima el porcentaje de grasa corporal usando método mejorado
     * Combina Deurenberg con validaciones para evitar valores negativos
     */
    function estimateBodyFat(bmi, age, gender) {
        let bodyFat;
        
        if (gender === 'male') {
            // Para hombres: Fórmula de Deurenberg
            bodyFat = (1.20 * bmi) + (0.23 * age) - (10.8 * 1) - 5.4;
            
            // Validación: los hombres no pueden tener menos de 3-5% de grasa
            if (bodyFat < 3) {
                bodyFat = 3 + ((bmi - 15) * 0.5); // Estimación mínima basada en IMC
            }
        } else {
            // Para mujeres: Fórmula de Deurenberg adaptada
            bodyFat = (1.20 * bmi) + (0.23 * age) - 5.4;
            
            // Validación: las mujeres no pueden tener menos de 10-12% de grasa
            if (bodyFat < 10) {
                bodyFat = 10 + ((bmi - 15) * 0.5); // Estimación mínima basada en IMC
            }
        }
        
        // Validación general: porcentaje máximo razonable es 50%
        if (bodyFat > 50) {
            bodyFat = 50;
        }
        
        // Asegurar que nunca sea negativo
        return Math.max(bodyFat, gender === 'male' ? 3 : 10);
    }

    /**
     * Calcula la Tasa Metabólica Basal usando la fórmula Katch-McArdle
     */
    function calculateTMB(weight, bodyFatPercentage) {
        // Calcular Masa Libre de Grasa (Lean Body Mass - LBM)
        let leanBodyMass = weight * (1 - (bodyFatPercentage / 100));
        
        // Fórmula Katch-McArdle
        return 370 + (21.6 * leanBodyMass);
    }

    /**
     * Actualiza la visualización de TMB
     */
    function updateTMBDisplay(tmb) {
        const tmbValue = Math.round(tmb);
        document.getElementById('tmb').value = tmbValue;
        displayTmb.textContent = tmbValue + ' kcal';
        return tmbValue;
    }

    /**
     * Calcula el Gasto Energético Total Diario (TDEE)
     * Multiplicadores calibrados según Ripped Body
     */
    function calculateTDEE(tmb, activityLevelValue) {
        const activityMultipliers = {
            'sedentary': 1.25,        // Sedentario (poco o ningún ejercicio)
            'light': 1.45,            // Mayormente sedentario (ejercicio 1-3 días/semana)
            'moderate': 1.65,         // Ligeramente activo (ejercicio 3-5 días/semana)
            'active': 1.85,           // Activo (ejercicio 6-7 días/semana)
            'very_active': 2.05       // Muy activo (ejercicio intenso diario)
        };
        return tmb * activityMultipliers[activityLevelValue];
    }

    /**
     * Actualiza la visualización de TDEE
     */
    function updateTDEEDisplay(tdee, activityLevelValue) {
        const tdeeValue = Math.round(tdee);
        document.getElementById('tdee').value = tdeeValue;
        displayTdee.textContent = tdeeValue + ' kcal';
        
        const activityLabels = {
            'sedentary': 'Sedentario',
            'light': 'Mayormente sedentario',
            'moderate': 'Ligeramente activo',
            'active': 'Activo',
            'very_active': 'Muy activo'
        };
        tdeeDescription.textContent = activityLabels[activityLevelValue];
        
        return tdeeValue;
    }

    /**
     * Calcula el Índice Cintura-Cadera (WHR)
     */
    function calculateWaistHipRatio(waist, hip, gender) {
        if (waist <= 0 || hip <= 0) {
            return null;
        }

        const whr = waist / hip;
        let category, color;
        
        if (gender === 'male') {
            if (whr < 0.95) {
                category = 'Bajo riesgo cardiovascular';
                color = 'text-green-600';
            } else if (whr < 1.0) {
                category = 'Riesgo cardiovascular moderado';
                color = 'text-yellow-600';
            } else {
                category = 'Riesgo cardiovascular alto';
                color = 'text-red-600';
            }
        } else {
            if (whr < 0.80) {
                category = 'Bajo riesgo cardiovascular';
                color = 'text-green-600';
            } else if (whr < 0.85) {
                category = 'Riesgo cardiovascular moderado';
                color = 'text-yellow-600';
            } else {
                category = 'Riesgo cardiovascular alto';
                color = 'text-red-600';
            }
        }

        return { value: whr, category, color };
    }

    /**
     * Actualiza la visualización del WHR
     */
    function updateWHRDisplay(whrData) {
        if (whrData === null) {
            document.getElementById('whr').value = '';
            displayWhr.textContent = '--';
            whrCategory.textContent = 'Ingresa cintura y cadera';
            return;
        }

        const whrValue = whrData.value.toFixed(3);
        document.getElementById('whr').value = whrValue;
        displayWhr.textContent = whrValue;
        whrCategory.textContent = whrData.category;
        whrCategory.className = `text-xs font-medium ${whrData.color}`;
    }

    /**
     * Calcula el Índice Cintura-Altura (WHtR)
     */
    function calculateWaistHeightRatio(waist, height) {
        if (waist <= 0) {
            return null;
        }

        const wht = waist / height;
        let category, color;
        
        if (wht < 0.40) {
            category = 'Extremadamente delgado';
            color = 'text-blue-600';
        } else if (wht < 0.50) {
            category = 'Saludable - Bajo riesgo';
            color = 'text-green-600';
        } else if (wht < 0.60) {
            category = 'Sobrepeso - Riesgo aumentado';
            color = 'text-yellow-600';
        } else {
            category = 'Obesidad - Riesgo alto';
            color = 'text-red-600';
        }

        return { value: wht, category, color };
    }

    /**
     * Actualiza la visualización del WHtR
     */
    function updateWHtRDisplay(whtData) {
        if (whtData === null) {
            document.getElementById('wht').value = '';
            displayWht.textContent = '--';
            whtCategory.textContent = 'Ingresa cintura';
            return;
        }

        const whtValue = whtData.value.toFixed(3);
        document.getElementById('wht').value = whtValue;
        displayWht.textContent = whtValue;
        whtCategory.textContent = whtData.category;
        whtCategory.className = `text-xs font-medium ${whtData.color}`;
    }

    /**
     * Calcula la Complexión Ósea (Índice de Frisancho)
     */
    function calculateFrameIndex(height, wrist, gender) {
        if (wrist <= 0) {
            return null;
        }

        const frameIndex = height / wrist;
        let category, color;
        
        if (gender === 'male') {
            if (frameIndex > 10.4) {
                category = 'Complexión pequeña';
                color = 'text-blue-600';
            } else if (frameIndex > 9.6) {
                category = 'Complexión mediana';
                color = 'text-green-600';
            } else {
                category = 'Complexión grande';
                color = 'text-indigo-600';
            }
        } else {
            if (frameIndex > 11.0) {
                category = 'Complexión pequeña';
                color = 'text-blue-600';
            } else if (frameIndex > 10.1) {
                category = 'Complexión mediana';
                color = 'text-green-600';
            } else {
                category = 'Complexión grande';
                color = 'text-indigo-600';
            }
        }

        return { value: frameIndex, category, color };
    }

    /**
     * Actualiza la visualización de la complexión ósea
     */
    function updateFrameDisplay(frameData) {
        if (frameData === null) {
            document.getElementById('frame_index').value = '';
            displayFrame.textContent = '--';
            frameCategory.textContent = 'Ingresa muñeca';
            return;
        }

        const frameValue = frameData.value.toFixed(2);
        document.getElementById('frame_index').value = frameValue;
        displayFrame.textContent = frameValue;
        frameCategory.textContent = frameData.category;
        frameCategory.className = `text-xs font-medium ${frameData.color}`;
    }

    /**
     * Calcula el porcentaje de grasa corporal usando el método U.S. Navy
     */
    function calculateBodyFatNavy(waist, neck, hip, height, gender) {
        if (waist <= 0 || neck <= 0) {
            return null;
        }

        let bodyFatPercentage;
        
        if (gender === 'male') {
            bodyFatPercentage = 495 / (1.0324 - 0.19077 * Math.log10(waist - neck) + 0.15456 * Math.log10(height)) - 450;
        } else {
            if (hip <= 0) {
                return null; // Para mujeres, la cadera es requerida
            }
            bodyFatPercentage = 495 / (1.29579 - 0.35004 * Math.log10(waist + hip - neck) + 0.22100 * Math.log10(height)) - 450;
        }

        return bodyFatPercentage;
    }

    /**
     * Obtiene la categoría del porcentaje de grasa corporal
     */
    function getBodyFatCategory(bodyFatPercentage, age, gender) {
        let category, color;
        
        if (gender === 'male') {
            if (age <= 39) {
                if (bodyFatPercentage < 8) {
                    category = 'Atleta de élite';
                    color = 'text-blue-600';
                } else if (bodyFatPercentage < 20) {
                    category = 'Saludable';
                    color = 'text-green-600';
                } else if (bodyFatPercentage < 25) {
                    category = 'Aceptable';
                    color = 'text-yellow-600';
                } else {
                    category = 'Alto - Riesgo para la salud';
                    color = 'text-red-600';
                }
            } else {
                if (bodyFatPercentage < 11) {
                    category = 'Atleta de élite';
                    color = 'text-blue-600';
                } else if (bodyFatPercentage < 22) {
                    category = 'Saludable';
                    color = 'text-green-600';
                } else if (bodyFatPercentage < 28) {
                    category = 'Aceptable';
                    color = 'text-yellow-600';
                } else {
                    category = 'Alto - Riesgo para la salud';
                    color = 'text-red-600';
                }
            }
        } else {
            if (age <= 39) {
                if (bodyFatPercentage < 21) {
                    category = 'Atleta de élite';
                    color = 'text-blue-600';
                } else if (bodyFatPercentage < 33) {
                    category = 'Saludable';
                    color = 'text-green-600';
                } else if (bodyFatPercentage < 39) {
                    category = 'Aceptable';
                    color = 'text-yellow-600';
                } else {
                    category = 'Alto - Riesgo para la salud';
                    color = 'text-red-600';
                }
            } else {
                if (bodyFatPercentage < 23) {
                    category = 'Atleta de élite';
                    color = 'text-blue-600';
                } else if (bodyFatPercentage < 34) {
                    category = 'Saludable';
                    color = 'text-green-600';
                } else if (bodyFatPercentage < 40) {
                    category = 'Aceptable';
                    color = 'text-yellow-600';
                } else {
                    category = 'Alto - Riesgo para la salud';
                    color = 'text-red-600';
                }
            }
        }

        return { category, color };
    }

    /**
     * Actualiza la visualización del porcentaje de grasa corporal
     * NOTA: Este es solo informativo (US Navy). NO se usa para cálculos de TMB/TDEE
     */
    function updateBodyFatDisplay(bodyFatPercentage, age, gender) {
        if (bodyFatPercentage === null || bodyFatPercentage === undefined) {
            displayBodyfat.textContent = '--';
            bodyfatCategory.textContent = 'Ingresa cintura, cuello' + (gender === 'female' ? ' y cadera' : '');
            return false;
        }

        // Asegurar que el porcentaje nunca sea negativo
        const validBodyFat = Math.max(bodyFatPercentage, gender === 'male' ? 3 : 10);
        const bfValue = validBodyFat.toFixed(2);
        displayBodyfat.textContent = bfValue + '%';

        const { category, color } = getBodyFatCategory(validBodyFat, age, gender);
        
        bodyfatCategory.textContent = category + ' (Fórmula Deurenberg: ' + (gender === 'male' ? 'Hombre' : 'Mujer') + ', ' + age + ' años)';
        bodyfatCategory.className = `text-xs font-medium ${color}`;
        
        return true;
    }

    /**
     * Resetea todas las visualizaciones
     */
    function resetAllDisplays() {
        bmiInput.value = '';
        document.getElementById('tmb').value = '';
        document.getElementById('tdee').value = '';
        document.getElementById('whr').value = '';
        document.getElementById('wht').value = '';
        document.getElementById('frame_index').value = '';
        bodyFatInput.value = '';
        displayBmi.textContent = '--';
        bmiCategory.textContent = 'Ingresa peso y altura';
        bmiIndicator.style.width = '0%';
        displayTmb.textContent = '--';
        displayTdee.textContent = '--';
        tdeeDescription.textContent = 'Calorías diarias requeridas';
        displayWhr.textContent = '--';
        whrCategory.textContent = '-';
        displayWht.textContent = '--';
        whtCategory.textContent = '-';
        displayFrame.textContent = '--';
        frameCategory.textContent = '-';
        displayBodyfat.textContent = '--';
        bodyfatCategory.textContent = '-';
    }

    /**
     * Función principal que orquesta todos los cálculos
     */
    /**
     * Función principal que orquesta todos los cálculos
     */
    function calculateAll() {
        const weightDisplay = parseFloat(weightInputDisplay.value);
        const unit = weightUnit.value;
        const height = parseFloat(heightInput.value);
        const waist = parseFloat(waistInput.value);
        const hip = parseFloat(hipInput.value);
        const neck = parseFloat(neckInput.value);
        const wrist = parseFloat(wristInput.value);

        // Convertir peso a kg
        const weight = convertWeight(weightDisplay, unit);
        weightInput.value = weight.toFixed(2);

        if (weight > 0 && height > 0) {
            // 1. Calcular y actualizar IMC
            const bmi = calculateBMI(weight, height);
            updateBMIDisplay(bmi);

            // 2. Calcular TMB usando SIEMPRE la estimación calibrada (Ripped Body)
            // NOTA: Esto ignora el método US Navy para mantener consistencia con Ripped Body
            const estimatedBodyFatPercentage = estimateBodyFat(bmi, age, gender);
            bodyFatInput.value = estimatedBodyFatPercentage.toFixed(2);
            
            const tmb = calculateTMB(weight, estimatedBodyFatPercentage);
            const tmbValue = updateTMBDisplay(tmb);

            // 3. Calcular y actualizar TDEE
            const tdee = calculateTDEE(tmb, activityLevel.value);
            const tdeeValue = updateTDEEDisplay(tdee, activityLevel.value);

            // 4. Calcular y actualizar Índice Cintura-Cadera
            const whrData = calculateWaistHipRatio(waist, hip, gender);
            updateWHRDisplay(whrData);

            // 5. Calcular y actualizar Índice Cintura-Altura
            const whtData = calculateWaistHeightRatio(waist, height);
            updateWHtRDisplay(whtData);

            // 6. Calcular y actualizar Complexión Ósea
            const frameData = calculateFrameIndex(height, wrist, gender);
            updateFrameDisplay(frameData);

            // 7. Calcular y actualizar Porcentaje de Grasa Corporal (Navy Method)
            const bodyFatPercentage = calculateBodyFatNavy(waist, neck, hip, height, gender);
            const bodyFatCalculated = updateBodyFatDisplay(bodyFatPercentage, age, gender);

            // Si no se pudo calcular grasa corporal para mujeres, salir
            if (bodyFatPercentage === null && gender === 'female') {
                return;
            }

            // 8. Calcular macros y calorías objetivo
            calculateNutritionPlan(weight, tdeeValue);
        } else {
            resetAllDisplays();
        }
    }

    /**
     * Calcula el plan nutricional basado en el objetivo
     */
    function calculateNutritionPlan(weight, tdee) {
        const goal = nutritionGoal.value;
        
        // Calcular calorías objetivo según el objetivo nutricional
        const targetCalories = calculateTargetCalories(tdee, goal);
        document.getElementById('target_calories').value = targetCalories;
        
        // Actualizar displays de calorías
        updateCaloriesDisplay(targetCalories, tdee);

        // Calcular macronutrientes
        const macros = calculateMacros(weight, targetCalories, goal);
        
        // Guardar valores en inputs ocultos
        saveMacrosToInputs(macros);
        
        // Actualizar visualización de macros
        updateMacrosDisplay(macros, targetCalories);

        // Recalcular comparaciones con equivalentes actuales
        calculateEquivalentCalories();
    }

    /**
     * Calcula las calorías objetivo según el objetivo nutricional
     */
    function calculateTargetCalories(tdee, goal) {
        if (goal === 'deficit') {
            return Math.round(tdee * 0.80); // 20% deficit
        } else if (goal === 'surplus') {
            return Math.round(tdee * 1.10); // 10% surplus
        } else {
            return tdee; // Mantenimiento
        }
    }

    /**
     * Actualiza los displays de calorías
     */
    function updateCaloriesDisplay(targetCalories, tdee) {
        document.getElementById('display-bmr').textContent = document.getElementById('tmb').value + ' cal';
        document.getElementById('display-tdee-nutrition').textContent = tdee + ' cal';
        document.getElementById('display-target-calories-main').textContent = targetCalories + ' cal';
    }

    /**
     * Constantes de calorías por equivalente (Definidas por el nutricionista)
     */
    const GROUP_CALORIES = {
        cereales: 73,
        verduras: 24,
        frutas: 60,
        lacteo: 111,    // lacteo_semidescremado
        animal: 46,     // origen_animal
        aceites: 45,
        grasas_prot: 69, // grasas_con_proteina
        leguminosas: 121
    };

    /**
     * Gramos de macronutrientes por equivalente de cada grupo
     * Basado en la tabla del Sistema de Equivalentes
     */
    const GROUP_MACROS = {
        cereales: { protein: 2, carbs: 15, fat: 0.5 },
        verduras: { protein: 2, carbs: 4, fat: 0 },
        frutas: { protein: 0, carbs: 15, fat: 0 },
        lacteo: { protein: 9, carbs: 12, fat: 3 },
        animal: { protein: 7, carbs: 0, fat: 2 },
        aceites: { protein: 0, carbs: 0, fat: 5 },
        grasas_prot: { protein: 3, carbs: 3, fat: 5 },
        leguminosas: { protein: 8, carbs: 20, fat: 1 }
    };

    /**
     * Calcula los macronutrientes según el protocolo Ripped Body
     */
    function calculateMacros(weight, targetCalories, goal) {
        // Proteína: 1.6g/kg para mantenimiento/volumen, 2.2g/kg para definición
        const proteinMultiplier = (goal === 'deficit') ? 2.2 : 1.6;
        const proteinGrams = Math.round(weight * proteinMultiplier);
        const proteinKcal = proteinGrams * 4;

        // Grasas: 25% de calorías totales
        const fatPercentTarget = 0.25;
        const fatKcal = Math.round(targetCalories * fatPercentTarget);
        const fatGrams = Math.round(fatKcal / 9);

        // Carbohidratos: Resto de calorías
        const carbsKcal = targetCalories - proteinKcal - fatKcal;
        const carbsGrams = Math.round(carbsKcal / 4);

        return {
            protein: { grams: proteinGrams, kcal: proteinKcal },
            fat: { grams: fatGrams, kcal: fatKcal },
            carbs: { grams: carbsGrams, kcal: carbsKcal }
        };
    }

    /**
     * Calcula las calorías totales y macronutrientes basados en los equivalentes ingresados
     */
    function calculateEquivalentCalories() {
        let totalEquivalentKcal = 0;
        let totalProtein = 0;
        let totalCarbs = 0;
        let totalFat = 0;

        // Iterar sobre cada grupo
        for (const [key, kcalPerEq] of Object.entries(GROUP_CALORIES)) {
            // Mapear claves del objeto a IDs de inputs
            // cereales -> eq_cereales
            // lacteo -> eq_lacteo (simplificado en el objeto, mapeado aquí)
            // animal -> eq_animal
            // grasas_prot -> eq_grasas_prot
            
            const inputId = `eq_${key}`;
            const displayId = `kcal_${key}`;
            
            const input = document.getElementById(inputId);
            const display = document.getElementById(displayId);
            
            if (input && display) {
                const equivalents = parseFloat(input.value) || 0;
                const totalKcal = Math.round(equivalents * kcalPerEq);
                
                display.textContent = totalKcal;
                totalEquivalentKcal += totalKcal;

                // Sumar macronutrientes
                const macros = GROUP_MACROS[key];
                if (macros) {
                    const groupProtein = equivalents * macros.protein;
                    const groupCarbs = equivalents * macros.carbs;
                    const groupFat = equivalents * macros.fat;
                    
                    totalProtein += groupProtein;
                    totalCarbs += groupCarbs;
                    totalFat += groupFat;

                    // Actualizar tabla de macros por grupo
                    updateGroupMacrosDisplay(key, groupProtein, groupCarbs, groupFat);
                }
            }
        }

        // Actualizar total de calorías
        const totalDisplay = document.getElementById('total_kcal_equivalents');
        if (totalDisplay) {
            totalDisplay.textContent = Math.round(totalEquivalentKcal) + ' kcal';
        }

        // Actualizar comparación de calorías con objetivo
        updateCaloriesComparison(totalEquivalentKcal);

        // Actualizar totales de macronutrientes
        updateEquivalentMacrosDisplay(totalProtein, totalCarbs, totalFat);
    }

    /**
     * Actualiza la comparación de calorías con el objetivo
     */
    function updateCaloriesComparison(currentCalories) {
        const targetCalories = parseFloat(document.getElementById('target_calories').value) || 0;
        const targetDisplay = document.getElementById('target_calories_display');
        const percentDisplay = document.getElementById('calories_percent');

        if (targetDisplay && targetCalories > 0) {
            targetDisplay.textContent = targetCalories + ' kcal';
            
            const percent = (currentCalories / targetCalories) * 100;
            const percentText = Math.round(percent) + '%';
            
            if (percentDisplay) {
                percentDisplay.textContent = percentText;
                
                // Cambiar color según el porcentaje
                percentDisplay.className = 'text-sm font-bold px-3 py-1 rounded-full ';
                if (Math.abs(percent - 100) <= 10) {
                    percentDisplay.className += 'bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100';
                } else if (percent > 100) {
                    percentDisplay.className += 'bg-orange-100 dark:bg-orange-800 text-orange-800 dark:text-orange-100';
                } else {
                    percentDisplay.className += 'bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-100';
                }
            }
        }
    }

    /**
     * Actualiza la visualización de macros por grupo en la tabla
     */
    function updateGroupMacrosDisplay(group, protein, carbs, fat) {
        const proteinCell = document.getElementById(`protein_${group}`);
        const carbsCell = document.getElementById(`carbs_${group}`);
        const fatCell = document.getElementById(`fat_${group}`);

        if (proteinCell) proteinCell.textContent = protein.toFixed(1);
        if (carbsCell) carbsCell.textContent = carbs.toFixed(1);
        if (fatCell) fatCell.textContent = fat.toFixed(1);
    }

    /**
     * Actualiza la visualización de los totales de macronutrientes de los equivalentes
     */
    function updateEquivalentMacrosDisplay(protein, carbs, fat) {
        // Actualizar displays de totales
        const proteinDisplay = document.getElementById('total_protein_equivalents');
        const carbsDisplay = document.getElementById('total_carbs_equivalents');
        const fatDisplay = document.getElementById('total_fat_equivalents');
        
        if (proteinDisplay) proteinDisplay.textContent = protein.toFixed(1) + 'g';
        if (carbsDisplay) carbsDisplay.textContent = carbs.toFixed(1) + 'g';
        if (fatDisplay) fatDisplay.textContent = fat.toFixed(1) + 'g';

        // Obtener objetivos calculados
        const targetProtein = parseFloat(document.getElementById('protein_grams').value) || 0;
        const targetCarbs = parseFloat(document.getElementById('carbs_grams').value) || 0;
        const targetFat = parseFloat(document.getElementById('fat_grams').value) || 0;

        // Actualizar displays de objetivos
        const targetProteinDisplay = document.getElementById('target_protein_display');
        const targetCarbsDisplay = document.getElementById('target_carbs_display');
        const targetFatDisplay = document.getElementById('target_fat_display');
        
        if (targetProteinDisplay) targetProteinDisplay.textContent = targetProtein + 'g';
        if (targetCarbsDisplay) targetCarbsDisplay.textContent = targetCarbs + 'g';
        if (targetFatDisplay) targetFatDisplay.textContent = targetFat + 'g';

        // Actualizar comparación con objetivos
        updateMacroComparison('protein', protein, targetProtein);
        updateMacroComparison('carbs', carbs, targetCarbs);
        updateMacroComparison('fat', fat, targetFat);
    }

    /**
     * Actualiza la comparación entre macros ingresados y objetivos
     */
    function updateMacroComparison(macroType, current, target) {
        const diffDisplay = document.getElementById(`diff_${macroType}`);
        const percentDisplay = document.getElementById(`percent_${macroType}`);
        
        if (!diffDisplay || !percentDisplay || target === 0) return;

        const diff = current - target;
        const percent = (current / target) * 100;

        // Actualizar diferencia
        const diffText = diff >= 0 ? `+${diff.toFixed(1)}g` : `${diff.toFixed(1)}g`;
        const diffColor = Math.abs(diff) <= target * 0.1 
            ? 'text-green-600 dark:text-green-400' 
            : (diff > 0 ? 'text-orange-600 dark:text-orange-400' : 'text-red-600 dark:text-red-400');
        
        diffDisplay.textContent = diffText;
        diffDisplay.className = `text-sm font-semibold ${diffColor}`;

        // Actualizar porcentaje
        percentDisplay.textContent = Math.round(percent) + '%';
        percentDisplay.className = `text-xs ${diffColor}`;
    }

    // Event listeners para los inputs de equivalentes
    const equivalentInputs = [
        'eq_cereales', 'eq_verduras', 'eq_frutas', 'eq_lacteo', 
        'eq_animal', 'eq_aceites', 'eq_grasas_prot', 'eq_leguminosas'
    ];

    equivalentInputs.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', calculateEquivalentCalories);
        }
    });

    /**
     * Guarda los valores de macros en inputs ocultos
     */
    function saveMacrosToInputs(macros) {
        document.getElementById('protein_grams').value = macros.protein.grams;
        document.getElementById('fat_grams').value = macros.fat.grams;
        document.getElementById('carbs_grams').value = macros.carbs.grams;
    }

    /**
     * Actualiza la visualización de macronutrientes
     */
    function updateMacrosDisplay(macros, targetCalories) {
        // Actualizar gramos
        document.getElementById('display-protein-main').textContent = macros.protein.grams + 'g';
        document.getElementById('display-fat-main').textContent = macros.fat.grams + 'g';
        document.getElementById('display-carbs-main').textContent = macros.carbs.grams + 'g';

        // Calcular porcentajes
        const proteinPercent = Math.round((macros.protein.kcal / targetCalories) * 100);
        const fatPercent = Math.round((macros.fat.kcal / targetCalories) * 100);
        const carbsPercent = 100 - proteinPercent - fatPercent;

        // Actualizar textos de porcentajes y calorías
        document.getElementById('protein-percent-display').textContent = '(' + proteinPercent + '%)';
        document.getElementById('protein-kcal-display').textContent = macros.protein.kcal + ' cal';
        document.getElementById('fat-percent-display').textContent = '(' + fatPercent + '%)';
        document.getElementById('fat-kcal-display').textContent = macros.fat.kcal + ' cal';
        document.getElementById('carbs-percent-display').textContent = '(' + carbsPercent + '%)';
        document.getElementById('carbs-kcal-display').textContent = macros.carbs.kcal + ' cal';

        // Actualizar barras de progreso
        document.getElementById('protein-bar').style.width = proteinPercent + '%';
        document.getElementById('fat-bar').style.width = fatPercent + '%';
        document.getElementById('carbs-bar').style.width = carbsPercent + '%';
    }

    /**
     * Actualiza el muñeco según el IMC
     */
    function updateBMIAvatar(bmi) {
        const torso = document.getElementById('torso');
        const armLeft = document.getElementById('arm-left');
        const armRight = document.getElementById('arm-right');
        const legLeft = document.getElementById('leg-left');
        const legRight = document.getElementById('leg-right');
        const mouth = document.getElementById('mouth');
        
        if (!torso) return;

        if (bmi < 18.5) {
            // Bajo peso - muy delgado
            torso.setAttribute('rx', '12');
            torso.setAttribute('ry', '22');
            armLeft.setAttribute('stroke-width', '4');
            armRight.setAttribute('stroke-width', '4');
            legLeft.setAttribute('stroke-width', '5');
            legRight.setAttribute('stroke-width', '5');
            mouth.setAttribute('d', 'M 43 31 Q 50 28 57 31'); // Boca triste
        } else if (bmi < 25) {
            // Normal - proporcionado
            torso.setAttribute('rx', '18');
            torso.setAttribute('ry', '25');
            armLeft.setAttribute('stroke-width', '6');
            armRight.setAttribute('stroke-width', '6');
            legLeft.setAttribute('stroke-width', '8');
            legRight.setAttribute('stroke-width', '8');
            mouth.setAttribute('d', 'M 43 29 Q 50 32 57 29'); // Sonrisa
        } else if (bmi < 30) {
            // Sobrepeso - más ancho
            torso.setAttribute('rx', '23');
            torso.setAttribute('ry', '28');
            armLeft.setAttribute('stroke-width', '7');
            armRight.setAttribute('stroke-width', '7');
            legLeft.setAttribute('stroke-width', '10');
            legRight.setAttribute('stroke-width', '10');
            mouth.setAttribute('d', 'M 43 30 L 57 30'); // Boca neutral
        } else {
            // Obesidad - más redondo
            torso.setAttribute('rx', '28');
            torso.setAttribute('ry', '30');
            armLeft.setAttribute('stroke-width', '8');
            armRight.setAttribute('stroke-width', '8');
            legLeft.setAttribute('stroke-width', '12');
            legRight.setAttribute('stroke-width', '12');
            mouth.setAttribute('d', 'M 43 31 Q 50 28 57 31'); // Boca triste
        }
    }

    // Event listeners
    weightInputDisplay.addEventListener('input', calculateAll);
    weightUnit.addEventListener('change', calculateAll);
    heightInput.addEventListener('input', calculateAll);
    waistInput.addEventListener('input', calculateAll);
    hipInput.addEventListener('input', calculateAll);
    neckInput.addEventListener('input', calculateAll);
    wristInput.addEventListener('input', calculateAll);
    activityLevel.addEventListener('change', calculateAll);
    nutritionGoal.addEventListener('change', calculateAll);

    // Calcular inicial si hay valores
    if (weightInputDisplay.value && heightInput.value) {
        calculateAll();
    }
});

// Protección contra múltiples envíos del formulario
const form = document.getElementById('attention-form');
const submitBtn = document.getElementById('submit-btn');
const cancelBtn = document.getElementById('cancel-btn');
const submitIcon = document.getElementById('submit-icon');
const submitText = document.getElementById('submit-text');
let isSubmitting = false;

form.addEventListener('submit', function(e) {
    // Validar que se hayan calculado los valores necesarios
    const bmiValue = document.getElementById('bmi').value;
    const diagnosisValue = document.getElementById('diagnosis').value.trim();
    const recommendationsValue = document.getElementById('recommendations').value.trim();
    
    if (!bmiValue || bmiValue === '') {
        e.preventDefault();
        alert('Por favor, ingresa peso y altura para calcular el IMC antes de guardar.');
        return false;
    }
    
    if (!diagnosisValue || diagnosisValue === '') {
        e.preventDefault();
        alert('Por favor, completa el diagnóstico antes de guardar.');
        document.getElementById('diagnosis').focus();
        return false;
    }
    
    if (!recommendationsValue || recommendationsValue === '') {
        e.preventDefault();
        alert('Por favor, completa las recomendaciones antes de guardar.');
        document.getElementById('recommendations').focus();
        return false;
    }
    
    // Si ya se está enviando, prevenir el envío
    if (isSubmitting) {
        e.preventDefault();
        return false;
    }

    // Marcar como enviando
    isSubmitting = true;

    // Deshabilitar el botón de enviar
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    submitBtn.classList.remove('hover:from-blue-700', 'hover:to-emerald-700');

    // Deshabilitar el botón de cancelar
    cancelBtn.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
    cancelBtn.classList.remove('hover:bg-gray-200', 'dark:hover:bg-gray-600');

    // Cambiar el contenido del botón de enviar
    submitIcon.classList.add('animate-spin');
    submitIcon.textContent = 'hourglass_empty';
    submitText.textContent = 'Guardando...';
});
