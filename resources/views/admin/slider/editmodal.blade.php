<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Columna de imágenes actuales --}}
    <div class="space-y-6">
        <div>
            <h3 class="text-lg font-semibold mb-3 text-slate-800 dark:text-slate-200">Imágenes actuales</h3>
            <div class="grid grid-cols-1 gap-4">
                {{-- Imagen izquierda --}}
                <div class="relative group">
                    <div class="aspect-video bg-slate-100 dark:bg-slate-800 rounded-lg overflow-hidden">
                        @if($tableM->image_left)
                            <img src="{{ asset('images/slider/' . $tableM->image_left) }}" 
                                 alt="Imagen izquierda" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-image text-slate-400 dark:text-slate-500 text-4xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="bg-slate-800 text-white text-xs px-2 py-1 rounded">Izquierda</span>
                    </div>
                </div>
                
                {{-- Imagen derecha --}}
                <div class="relative group">
                    <div class="aspect-video bg-slate-100 dark:bg-slate-800 rounded-lg overflow-hidden">
                        @if($tableM->image_right)
                            <img src="{{ asset('images/slider/' . $tableM->image_right) }}" 
                                 alt="Imagen derecha" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-image text-slate-400 dark:text-slate-500 text-4xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="bg-slate-800 text-white text-xs px-2 py-1 rounded">Derecha</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Columna de formulario --}}
    <div>
        <form action="{{ url('update-slider/' . $tableM->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            {{-- Campo para imagen izquierda --}}
            <div>
                <label for="image_left" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">
                    Nueva imagen de la izquierda
                </label>
                <div class="relative">
                    <input id="image_left" type="file" name="image_left" accept="image/*" 
                           class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-50 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-100 dark:hover:file:bg-slate-700 cursor-pointer">
                    <label class="absolute inset-0 w-full h-full cursor-pointer" for="image_left"></label>
                </div>
                <div class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                    Formatos admitidos: JPG, PNG, GIF, WebP (máx. 20MB)
                </div>
                
                {{-- Vista previa --}}
                <div class="mt-3">
                    <div class="aspect-video bg-slate-100 dark:bg-slate-800 rounded-lg overflow-hidden">
                        <div id="preview-left" class="w-full h-full flex items-center justify-center">
                            @if($tableM->image_left)
                                <img src="{{ asset('images/slider/' . $tableM->image_left) }}" 
                                     alt="Vista previa" 
                                     class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-image text-slate-400 dark:text-slate-500 text-4xl"></i>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Campo para imagen derecha --}}
            <div>
                <label for="image_right" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">
                    Nueva imagen de la derecha
                </label>
                <div class="relative">
                    <input id="image_right" type="file" name="image_right" accept="image/*" 
                           class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-50 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-100 dark:hover:file:bg-slate-700 cursor-pointer">
                    <label class="absolute inset-0 w-full h-full cursor-pointer" for="image_right"></label>
                </div>
                <div class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                    Formatos admitidos: JPG, PNG, GIF, WebP (máx. 20MB)
                </div>
                
                {{-- Vista previa --}}
                <div class="mt-3">
                    <div class="aspect-video bg-slate-100 dark:bg-slate-800 rounded-lg overflow-hidden">
                        <div id="preview-right" class="w-full h-full flex items-center justify-center">
                            @if($tableM->image_right)
                                <img src="{{ asset('images/slider/' . $tableM->image_right) }}" 
                                     alt="Vista previa" 
                                     class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-image text-slate-400 dark:text-slate-500 text-4xl"></i>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" class="btn btn-secondary">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Vista previa de imágenes al seleccionar archivos
    const imageLeftInput = document.getElementById('image_left');
    const imageRightInput = document.getElementById('image_right');
    const previewLeft = document.getElementById('preview-left');
    const previewRight = document.getElementById('preview-right');

    imageLeftInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewLeft.innerHTML = `<img src="${e.target.result}" alt="Vista previa" class="w-full h-full object-cover rounded-lg">`;
            }
            reader.readAsDataURL(file);
        }
    });

    imageRightInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewRight.innerHTML = `<img src="${e.target.result}" alt="Vista previa" class="w-full h-full object-cover rounded-lg">`;
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>