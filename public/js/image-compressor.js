/**
 * Inicializa el compresor de imágenes para un input de archivo
 * @param {string} inputId - ID del input file
 * @param {string} livewireProperty - Nombre de la propiedad Livewire para subir
 */
function initializeImageCompressor(inputId, livewireProperty = 'profile_photo') {
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById(inputId);
        
        if (!fileInput) {
            console.warn(`Input con id "${inputId}" no encontrado`);
            return;
        }
        
        fileInput.addEventListener('change', async function(e) {
            const file = e.target.files[0];
            
            if (!file || !file.type.startsWith('image/')) {
                return;
            }

            try {
                // Comprimir la imagen
                const { compressedFile, previewUrl } = await compressImage(file, {
                    maxWidth: 1920,
                    maxHeight: 1920,
                    quality: 0.8,
                    maxSizeMB: 2
                });

                // Indicar que la compresión terminó
                window.dispatchEvent(new CustomEvent('compression-complete'));

                // Mostrar vista previa
                window.dispatchEvent(new CustomEvent('photo-preview', { 
                    detail: { url: previewUrl } 
                }));

                // Crear un nuevo DataTransfer para asignar el archivo comprimido
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(compressedFile);
                fileInput.files = dataTransfer.files;

                // Actualizar Livewire manualmente
                const livewireElement = fileInput.closest('[wire\\:id]');
                if (livewireElement) {
                    const componentId = livewireElement.getAttribute('wire:id');
                    const livewireComponent = window.Livewire.find(componentId);
                    
                    if (livewireComponent) {
                        livewireComponent.upload(livewireProperty, compressedFile, 
                            // Success callback
                            () => {
                                console.log('Imagen subida correctamente');
                            },
                            // Error callback
                            (error) => {
                                console.error('Error al subir la imagen:', error);
                            },
                            // Progress callback
                            (event) => {
                                console.log('Progreso:', Math.round((event.detail.progress || 0)) + '%');
                            }
                        );
                    }
                }

            } catch (error) {
                console.error('Error al comprimir la imagen:', error);
                window.dispatchEvent(new CustomEvent('compression-complete'));
                alert('Error al procesar la imagen. Por favor, intenta con otra imagen.');
            }
        });
    });
}

/**
 * Comprime una imagen reduciendo su resolución y calidad
 * @param {File} file - Archivo de imagen a comprimir
 * @param {Object} options - Opciones de compresión
 * @returns {Promise<{compressedFile: File, previewUrl: string}>}
 */
async function compressImage(file, options = {}) {
    const {
        maxWidth = 1920,
        maxHeight = 1920,
        quality = 0.8,
        maxSizeMB = 2
    } = options;

    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const img = new Image();
            
            img.onload = function() {
                // Calcular nuevas dimensiones manteniendo el aspect ratio
                let width = img.width;
                let height = img.height;
                
                if (width > maxWidth || height > maxHeight) {
                    const aspectRatio = width / height;
                    
                    if (width > height) {
                        width = maxWidth;
                        height = width / aspectRatio;
                    } else {
                        height = maxHeight;
                        width = height * aspectRatio;
                    }
                }

                // Crear canvas y redimensionar
                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                // Función recursiva para ajustar la calidad hasta alcanzar el tamaño deseado
                let currentQuality = quality;
                
                function attemptCompression() {
                    canvas.toBlob(function(blob) {
                        const sizeMB = blob.size / 1024 / 1024;
                        
                        // Si el tamaño es aceptable o la calidad ya es muy baja, usar este blob
                        if (sizeMB <= maxSizeMB || currentQuality <= 0.1) {
                            const fileName = file.name.replace(/\.[^/.]+$/, '') + '_compressed.jpg';
                            const compressedFile = new File([blob], fileName, {
                                type: 'image/jpeg',
                                lastModified: Date.now()
                            });
                            
                            // Crear URL para vista previa
                            const previewUrl = URL.createObjectURL(blob);
                            
                            console.log(`Imagen comprimida: ${(file.size / 1024 / 1024).toFixed(2)}MB → ${sizeMB.toFixed(2)}MB`);
                            resolve({ compressedFile, previewUrl });
                        } else {
                            // Reducir calidad y volver a intentar
                            currentQuality -= 0.1;
                            attemptCompression();
                        }
                    }, 'image/jpeg', currentQuality);
                }
                
                attemptCompression();
            };
            
            img.onerror = function() {
                reject(new Error('No se pudo cargar la imagen'));
            };
            
            img.src = e.target.result;
        };
        
        reader.onerror = function() {
            reject(new Error('No se pudo leer el archivo'));
        };
        
        reader.readAsDataURL(file);
    });
}
