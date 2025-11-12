{{-- Modal Universal de Edición --}}
<div id="{{ $modalId }}" class="fixed inset-0 z-50 transition-opacity duration-300"
    style="background-color: rgba(0, 0, 0, 0.5); opacity: 0; visibility: hidden; display: none; top: 0; left: 0; right: 0; bottom: 0;"
    data-modal-container data-section="{{ $sectionType ?? '' }}">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl overflow-hidden flex flex-col transition-all duration-300"
         style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 95%; max-width: 80rem; max-height: 95vh; opacity: 1; z-index: 50;"
         data-modal-content
         onclick="event.stopPropagation()">
        
        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-linear-to-r from-amber-400 to-yellow-500">
            <div>
                <h3 class="text-xl font-bold text-slate-900">Editar {{ $sectionTitle ?? 'Elemento' }}</h3>
                <p class="text-sm text-slate-800 mt-1">Actualiza la información del {{ strtolower($sectionTitle ?? 'elemento') }}</p>
            </div>
            <button type="button" 
                    class="inline-flex items-center justify-center w-10 h-10 text-slate-900 hover:text-slate-700 hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors"
                    aria-label="Cerrar modal"
                    onclick="closeEditModal('{{ $modalId }}')">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Modal Body --}}
        <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col overflow-hidden">
            @csrf
            <div class="overflow-y-auto flex-1 px-6 py-4">
                {{-- Indicador de sección (solo si existe) --}}
                @if($sectionType)
                <div class="mb-6 pb-4 border-b border-slate-200 dark:border-slate-700">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tipo de Contenido</p>
                    <p class="text-sm text-slate-700 dark:text-slate-300 mt-1">{{ $sectionTitle }}</p>
                </div>
                @endif
                {{-- Campos específicos para User --}}
                @if($sectionType === 'user')
                @php
                // Obtener todos los roles para el selector
                $roles = \App\Models\Role::all();
                // Determinar si el usuario está activo (no tiene deleted_at)
                $isActive = !isset($tableM->deleted_at);
                @endphp
                <div class="px-6 py-4 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Nombre completo</label>
                            <input id="name" type="text" name="name" value="{{ $tableM->name ?? '' }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Correo electrónico</label>
                            <input id="email" type="email" name="email" value="{{ $tableM->email ?? '' }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label for="role_id" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Rol</label>
                            <select id="role_id" name="role_id" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Seleccionar rol</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ isset($tableM->roles) && $tableM->roles->contains($role->id) ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Estado</label>
                            <div class="flex items-center h-10">
                                <input id="status" type="checkbox" name="status" value="1" {{ $isActive ? 'checked' : '' }} class="rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-blue-600 focus:ring-blue-600 focus:ring-offset-2">
                                <label for="status" class="ml-2 text-sm text-slate-700 dark:text-slate-300">Usuario activo</label>
                            </div>
                        </div>
                    </div>

                    {{-- Sección de contraseña --}}
                    <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 rounded-lg bg-amber-100 dark:bg-amber-900/20 flex items-center justify-center">
                                <i class="fas fa-key text-amber-600"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Contraseña</h4>
                        </div>
                        <div class="space-y-4">
                            <p class="text-sm text-slate-600 dark:text-slate-400">Deja los campos en blanco si no deseas cambiar la contraseña actual.</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="password" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Nueva contraseña</label>
                                    <input id="password" type="password" name="password" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Confirmar contraseña</label>
                                    <input id="password_confirmation" type="password" name="password_confirmation" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sección de imagen de perfil --}}
                    <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                                <i class="fas fa-image text-blue-600"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Imagen de perfil</h4>
                        </div>
                        <div class="space-y-4">
                            <div class="relative">
                                <input id="image" type="file" name="image" accept="image/*" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-50 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-100 dark:hover:file:bg-slate-700 cursor-pointer">
                                <label class="absolute inset-0 w-full h-full cursor-pointer" for="image"></label>
                            </div>
                            {{-- Contenedor de vista previa para la sección Verse (igual que el slider) --}}
                            <div class="mt-3">
                                <div class="aspect-video bg-slate-200 dark:bg-slate-700 rounded-lg flex items-center justify-center">
                                    <div id="preview-verse" class="w-full h-full flex items-center justify-center">
                                        @if($tableM->image)
                                        <img src="{{ asset('images/bible/' . $tableM->image) }}" alt="Imagen" class="w-full h-full object-cover rounded-lg">
                                        @else
                                        <i class="fas fa-image text-slate-400 dark:text-slate-500 text-2xl"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if($tableM->image)
                            <div class="flex items-center justify-between p-4 bg-slate-100 dark:bg-slate-800 rounded-lg">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $tableM->image }}</span>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ asset('images/user/' . $tableM->image) }}" target="_blank" class="text-blue-500 hover:text-blue-700 dark:text-blue-400">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <button type="button" class="text-blue-500 hover:text-blue-700" data-preview-url="{{ asset('images/user/' . $tableM->image) }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                {{-- Campos específicos para Role --}}
                @if($sectionType === 'role')
                <div class="px-6 py-4 space-y-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Nombre del rol</label>
                            <input id="name" type="text" name="name" value="{{ $tableM->name ?? '' }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        {{-- Sección de permisos --}}
                        <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                                    <i class="fas fa-key text-purple-600"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Permisos del rol</h4>
                            </div>
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- Aquí puedes agregar los permisos específicos --}}
                                    <div class="flex items-center">
                                        <input id="perm_news" type="checkbox" name="permissions[]" value="news"
                                            class="rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-blue-600 focus:ring-blue-600"
                                            @if(isset($tableM->permissions) && in_array('news', $tableM->permissions)) checked @endif>
                                        <label for="perm_news" class="ml-2 text-sm text-slate-700 dark:text-slate-300">Gestionar noticias</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="perm_users" type="checkbox" name="permissions[]" value="users"
                                            class="rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-blue-600 focus:ring-blue-600"
                                            @if(isset($tableM->permissions) && in_array('users', $tableM->permissions)) checked @endif>
                                        <label for="perm_users" class="ml-2 text-sm text-slate-700 dark:text-slate-300">Gestionar usuarios</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="perm_roles" type="checkbox" name="permissions[]" value="roles"
                                            class="rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-blue-600 focus:ring-blue-600"
                                            @if(isset($tableM->permissions) && in_array('roles', $tableM->permissions)) checked @endif>
                                        <label for="perm_roles" class="ml-2 text-sm text-slate-700 dark:text-slate-300">Gestionar roles</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="perm_settings" type="checkbox" name="permissions[]" value="settings"
                                            class="rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-blue-600 focus:ring-blue-600"
                                            @if(isset($tableM->permissions) && in_array('settings', $tableM->permissions)) checked @endif>
                                        <label for="perm_settings" class="ml-2 text-sm text-slate-700 dark:text-slate-300">Configuración del sistema</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Estado del rol --}}
                        <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                                    <i class="fas fa-toggle-on text-green-600"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Estado del rol</h4>
                            </div>
                            <div class="flex items-center">
                                <input id="active" type="checkbox" name="active" value="1"
                                    class="rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-blue-600 focus:ring-blue-600"
                                    @if(isset($tableM->active) && $tableM->active) checked @endif>
                                <label for="active" class="ml-2 text-sm text-slate-700 dark:text-slate-300">Rol activo</label>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                {{-- Campos específicos para News --}}
                @if($sectionType === 'news')
                <div class="px-6 py-4 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="category" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Categoría</label>
                            <select id="category" name="category" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="1" {{ ($tableM->category ?? 1) == 1 ? 'selected' : '' }}>Mensaje de la semana</option>
                                <option value="2" {{ ($tableM->category ?? 1) == 2 ? 'selected' : '' }}>Mirada afro</option>
                            </select>
                        </div>
                        <div>
                            <label for="broadcast" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Fecha de emisión</label>
                            <input id="broadcast" type="date" name="broadcast" value="{{ $tableM->broadcast ?? '' }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    {{-- Sección de archivos --}}
                    <div class="space-y-6">
                        {{-- PDF --}}
                        <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-lg bg-red-100 dark:bg-red-900/20 flex items-center justify-center">
                                    <i class="fas fa-file-pdf text-red-600"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Documento PDF</h4>
                            </div>
                            <div class="space-y-4">
                                <div class="relative">
                                    <input id="pdfdoc" type="file" name="pdfdoc" accept=".pdf" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-50 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-100 dark:hover:file:bg-slate-700 cursor-pointer">
                                    <label class="absolute inset-0 w-full h-full cursor-pointer" for="pdfdoc"></label>
                                </div>
                                @if($tableM->pdfdoc)
                                <div class="flex items-center justify-between p-4 bg-slate-100 dark:bg-slate-800 rounded-lg">
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $tableM->pdfdoc }}</span>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ asset('documents/news/' . $tableM->pdfdoc) }}" target="_blank" class="text-green-600 hover:text-green-800 dark:text-green-400">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <button type="button" class="text-blue-500 hover:text-blue-700" data-preview-url="{{ asset('documents/news/' . $tableM->pdfdoc) }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Imagen --}}
                        <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                                    <i class="fas fa-image text-blue-600"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Imagen</h4>
                            </div>
                            <div class="space-y-4">
                                <div class="relative">
                                    <input id="image" type="file" name="image" accept="image/*" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-50 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-100 dark:hover:file:bg-slate-700 cursor-pointer">
                                    <label class="absolute inset-0 w-full h-full cursor-pointer" for="image"></label>
                                </div>
                                @if($tableM->image)
                                <div class="flex items-center justify-between p-4 bg-slate-100 dark:bg-slate-800 rounded-lg">
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $tableM->image }}</span>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ asset('images/news/' . $tableM->image) }}" target="_blank" class="text-blue-500 hover:text-blue-700 dark:text-blue-400">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <button type="button" class="text-blue-500 hover:text-blue-700" data-preview-url="{{ asset('images/news/' . $tableM->image) }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Audio --}}
                        <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                                    <i class="fas fa-volume-up text-green-600"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Audio</h4>
                            </div>
                            <div class="space-y-4">
                                <div class="relative">
                                    <input id="audio" type="file" name="audio" accept="audio/*" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-50 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-100 dark:hover:file:bg-slate-700 cursor-pointer">
                                    <label class="absolute inset-0 w-full h-full cursor-pointer" for="audio"></label>
                                </div>
                                @if($tableM->audio)
                                <div class="p-4 bg-slate-100 dark:bg-slate-800 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $tableM->audio }}</span>
                                        <a href="{{ asset('audio/news/' . $tableM->audio) }}" target="_blank" class="text-green-600 hover:text-green-800 dark:text-green-400">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                    <audio controls class="w-full">
                                        <source src="{{ asset('audio/news/' . $tableM->audio) }}" type="audio/mpeg">
                                        Tu navegador no soporta este elemento de audio.
                                    </audio>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Campos específicos para Verse --}}
                @if($sectionType === 'verse')
                <div class="px-6 py-4 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Fecha</label>
                            <input id="date" type="date" name="date" value="{{ $tableM->date ?? '' }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                    </div>
                    {{-- Sección de archivos --}}
                    <div class="space-y-6">
                        {{-- Imagen --}}
                        <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                                    <i class="fas fa-image text-blue-600"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Imagen</h4>
                            </div>
                            <div class="space-y-4">
                                <div class="relative">
                                    <input id="image" type="file" name="image" accept="image/*" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-50 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-100 dark:hover:file:bg-slate-700 cursor-pointer">
                                    <label class="absolute inset-0 w-full h-full cursor-pointer" for="image"></label>
                                </div>
                                @if($tableM->image)
                                <div class="flex items-center justify-between p-4 bg-slate-100 dark:bg-slate-800 rounded-lg">
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $tableM->image }}</span>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ asset('images/bible/' . $tableM->image) }}" target="_blank" class="text-blue-500 hover:text-blue-700 dark:text-blue-400">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <button type="button" class="text-blue-500 hover:text-blue-700" data-preview-url="{{ asset('images/bible/' . $tableM->image) }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Video/PDF --}}
                        <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-lg bg-red-100 dark:bg-red-900/20 flex items-center justify-center">
                                    <i class="fas fa-file-pdf text-red-600"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Documento PDF</h4>
                            </div>
                            <div class="space-y-4">
                                <div class="relative">
                                    <input id="video" type="file" name="video" accept=".pdf" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-50 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-100 dark:hover:file:bg-slate-700 cursor-pointer">
                                    <label class="absolute inset-0 w-full h-full cursor-pointer" for="video"></label>
                                </div>
                                @if($tableM->video)
                                <div class="flex items-center justify-between p-4 bg-slate-100 dark:bg-slate-800 rounded-lg">
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $tableM->video }}</span>
                                    <a href="{{ asset('documents/quote/' . $tableM->video) }}" target="_blank" class="text-green-600 hover:text-green-800 dark:text-green-400">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Audio --}}
                        <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                                    <i class="fas fa-volume-up text-green-600"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Audio</h4>
                            </div>
                            <div class="space-y-4">
                                <div class="relative">
                                    <input id="audio" type="file" name="audio" accept="audio/*" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-50 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-100 dark:hover:file:bg-slate-700 cursor-pointer">
                                    <label class="absolute inset-0 w-full h-full cursor-pointer" for="audio"></label>
                                </div>
                                @if($tableM->audio)
                                <div class="p-4 bg-slate-100 dark:bg-slate-800 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $tableM->audio }}</span>
                                        <a href="{{ asset('audio/quote/' . $tableM->audio) }}" target="_blank" class="text-green-600 hover:text-green-800 dark:text-green-400">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                    <audio controls class="w-full">
                                        <source src="{{ asset('audio/quote/' . $tableM->audio) }}" type="audio/mpeg">
                                        Tu navegador no soporta este elemento de audio.
                                    </audio>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Campos específicos para Schedule --}}
                @if($sectionType === 'schedule')
                <div class="px-6 py-4 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Nombre del programa</label>
                            <input id="name" type="text" name="name" value="{{ $tableM->name ?? '' }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label for="host" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Director(a)</label>
                            <input id="host" type="text" name="host" value="{{ $tableM->host ?? '' }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label for="start" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Hora de inicio</label>
                            <input id="start" type="time" name="start" value="{{ $tableM->start ?? '' }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label for="end" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Hora de finalización</label>
                            <input id="end" type="time" name="end" value="{{ $tableM->end ?? '' }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div class="md:col-span-2">
                            <label for="about" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Descripción</label>
                            <textarea id="about" name="about" rows="3" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $tableM->about ?? '' }}</textarea>
                        </div>
                    </div>

                    {{-- Sección de días de emisión --}}
                    <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                                <i class="fas fa-calendar-week text-blue-600"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Días de emisión</h4>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @php
                            $daysSelected = isset($tableM) ?
                            \App\Models\Schedule::where('emission_key', $tableM->emission_key)->pluck('day')->toArray() :
                            [];
                            $dias = [
                            1 => 'Lunes',
                            2 => 'Martes',
                            3 => 'Miércoles',
                            4 => 'Jueves',
                            5 => 'Viernes',
                            6 => 'Sábado',
                            7 => 'Domingo',
                            ];
                            @endphp
                            @foreach ($dias as $num => $nombre)
                            <div class="flex items-center">
                                <input id="day{{ $num }}" type="checkbox" name="day[]" value="{{ $num }}" ...>
                                    class="rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-green-600 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-500"
                                    @if (in_array($num, $daysSelected)) checked @endif>
                                <label for="day_{{ $num }}" class="ml-2 text-sm text-slate-700 dark:text-slate-300">{{ $nombre }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Sección de imagen --}}
                    <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                                <i class="fas fa-image text-blue-600"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Imagen del Programa</h4>
                        </div>
                        <div class="space-y-4">
                            <div class="relative">
                                <input id="image" type="file" name="image" accept="image/*" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <label class="absolute inset-0 w-full h-full cursor-pointer" for="image"></label>
                            </div>
                            @if($tableM->image)
                            <div class="flex items-center justify-between p-4 bg-slate-100 dark:bg-slate-800 rounded-lg">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $tableM->image }}</span>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ asset('images/schedule/' . $tableM->image) }}" target="_blank" class="text-green-600 hover:text-green-800 dark:text-green-400">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <button type="button" class="text-blue-500 hover:text-blue-700" data-preview-url="{{ asset('images/schedule/' . $tableM->image) }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                {{-- Campos específicos para Slider --}}
                @if($sectionType === 'slider')
                <div class="px-6 py-4 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="image_left" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Imagen izquierda</label>
                            <div class="relative">
                                <input id="image_left" type="file" name="image_left" accept="image/*"
                                    class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-50 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-100 dark:hover:file:bg-slate-700 cursor-pointer">
                                <label class="absolute inset-0 w-full h-full cursor-pointer" for="image_left"></label>
                            </div>
                            @if($tableM->image_left)
                            <div class="flex items-center justify-between p-4 bg-slate-100 dark:bg-slate-800 rounded-lg mt-2">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $tableM->image_left }}</span>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ asset('images/slider/' . $tableM->image_left) }}" target="_blank" class="text-blue-500 hover:text-blue-700 dark:text-blue-400">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <button type="button" class="text-blue-500 hover:text-blue-700" data-preview-url="{{ asset('images/slider/' . $tableM->image_left) }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div>
                            <label for="image_right" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Imagen derecha</label>
                            <div class="relative">
                                <input id="image_right" type="file" name="image_right" accept="image/*"
                                    class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-50 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-100 dark:hover:file:bg-slate-700 cursor-pointer">
                                <label class="absolute inset-0 w-full h-full cursor-pointer" for="image_right"></label>
                            </div>
                            @if($tableM->image_right)
                            <div class="flex items-center justify-between p-4 bg-slate-100 dark:bg-slate-800 rounded-lg mt-2">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $tableM->image_right }}</span>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ asset('images/slider/' . $tableM->image_right) }}" target="_blank" class="text-blue-500 hover:text-blue-700 dark:text-blue-400">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <button type="button" class="text-blue-500 hover:text-blue-700" data-preview-url="{{ asset('images/slider/' . $tableM->image_right) }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Vista previa de imágenes --}}
                    <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                                <i class="fas fa-images text-blue-600"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Vista previa del carrusel</h4>
                        </div>
                        <div class="bg-slate-100 dark:bg-slate-800 rounded-lg p-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="aspect-video bg-slate-200 dark:bg-slate-700 rounded-lg flex items-center justify-center">
                                    <div id="preview-left" class="w-full h-full flex items-center justify-center">
                                        @if($tableM->image_left)
                                        <img src="{{ asset('images/slider/' . $tableM->image_left) }}" alt="Imagen izquierda" class="w-full h-full object-cover rounded-lg">
                                        @else
                                        <i class="fas fa-image text-slate-400 dark:text-slate-500 text-2xl"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="aspect-video bg-slate-200 dark:bg-slate-700 rounded-lg flex items-center justify-center">
                                    <div id="preview-right" class="w-full h-full flex items-center justify-center">
                                        @if($tableM->image_right)
                                        <img src="{{ asset('images/slider/' . $tableM->image_right) }}" alt="Imagen derecha" class="w-full h-full object-cover rounded-lg">
                                        @else
                                        <i class="fas fa-image text-slate-400 dark:text-slate-500 text-2xl"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Modal Footer --}}
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900">
                <button type="button"
                        class="px-5 py-2.5 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                        onclick="closeEditModal('{{ $modalId }}')">
                    <i class="fa-solid fa-times mr-2"></i>Cancelar
                </button>
                <button type="submit"
                        class="px-5 py-2.5 rounded-lg text-sm font-semibold text-slate-900 bg-linear-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 dark:from-amber-500 dark:to-yellow-600 dark:hover:from-amber-600 dark:hover:to-yellow-700 transition-all shadow-md hover:shadow-lg">
                    <i class="fa-solid fa-check mr-2"></i>Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal para vista previa de imagen --}}
<div id="imagePreviewModal" class="fixed inset-0 z-50 transition-opacity duration-300"
     style="background-color: rgba(0, 0, 0, 0.7); opacity: 0; visibility: hidden; display: none; top: 0; left: 0; right: 0; bottom: 0;"
     data-modal-container
     onclick="if(event.target === this) closeImagePreview()">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl overflow-hidden flex flex-col"
         style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 95%; max-width: 56rem; max-height: 70vh; z-index: 50;"
         data-modal-content
         onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-linear-to-r from-amber-400 to-yellow-500">
            <h3 class="text-lg font-semibold text-slate-900">Vista Previa de Imagen</h3>
            <button type="button"
                    class="inline-flex items-center justify-center w-10 h-10 text-slate-900 hover:text-slate-700 hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors"
                    onclick="closeImagePreview()">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="p-6 flex items-center justify-center bg-slate-50 dark:bg-slate-900" style="max-height: 70vh;">
            <img id="previewImage" src="" alt="Vista previa" class="max-w-full max-h-full object-contain rounded-lg">
        </div>
    </div>
</div>

<script>
// Gestión del modal de edición
window.openEditModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
        // Forzar reflow para que se aplique el display antes de cambiar opacity
        void modal.offsetWidth;
        modal.style.opacity = '1';
        modal.style.visibility = 'visible';
        document.body.style.overflow = 'hidden';
    }
};

window.closeEditModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.opacity = '0';
        modal.style.visibility = 'hidden';
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }, 300);
    }
};

// Gestión del modal de vista previa
window.showImagePreview = function(imageUrl) {
    const modal = document.getElementById('imagePreviewModal');
    const img = document.getElementById('previewImage');
    if (modal && img) {
        img.src = imageUrl;
        modal.style.display = 'block';
        void modal.offsetWidth;
        modal.style.opacity = '1';
        modal.style.visibility = 'visible';
        document.body.style.overflow = 'hidden';
    }
};

window.closeImagePreview = function() {
    const modal = document.getElementById('imagePreviewModal');
    if (modal) {
        modal.style.opacity = '0';
        modal.style.visibility = 'hidden';
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }, 300);
    }
};

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    // Cerrar modal con Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const editModal = document.querySelector('[data-modal-container]:not(.hidden)');
            if (editModal && editModal.id === '{{ $modalId }}') {
                closeEditModal('{{ $modalId }}');
            }
            const previewModal = document.getElementById('imagePreviewModal');
            if (previewModal && !previewModal.classList.contains('hidden')) {
                closeImagePreview();
            }
        }
    });

    // Cerrar modal al hacer click en el fondo
    document.getElementById('{{ $modalId }}').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal('{{ $modalId }}');
        }
    });

    // Delegación para botones de vista previa
    document.body.addEventListener('click', function(e) {
        const previewBtn = e.target.closest('[data-preview-url]');
        if (previewBtn) {
            e.preventDefault();
            const url = previewBtn.getAttribute('data-preview-url');
            if (url) showImagePreview(url);
        }
    });

    // Vista previa en tiempo real para inputs de tipo file dentro de ESTE modal
    // Se agregan miniaturas locales (.local-image-preview) junto al input y al hacer clic abren la vista previa grande
    (function() {
    const modalEl = document.getElementById('{{ $modalId }}');
    if (!modalEl) return;
    // Leer tipo de sección desde el atributo data-section (evita insertar Blade en JS)
    const __SECTION_TYPE = modalEl.getAttribute('data-section') || '';

        const fileInputs = modalEl.querySelectorAll('input[type="file"]');
        fileInputs.forEach(function(input) {
            input.addEventListener('change', function(e) {
                const file = e.target.files && e.target.files[0];
                // buscar contenedor de preview local existente dentro del mismo bloque
                let previewContainer = input.closest('.relative') || input.parentElement;
                if (!previewContainer) previewContainer = input.parentElement;

                let localPreview = previewContainer.querySelector('.local-image-preview');
                if (!file) {
                    if (localPreview) localPreview.remove();
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(evt) {
                    const src = evt.target.result;
                    // Si estamos en la sección Verse, actualizar el contenedor #preview-verse como hace el slider
                    if (typeof __SECTION_TYPE !== 'undefined' && __SECTION_TYPE === 'verse' && input.id === 'image') {
                        const previewVerse = document.getElementById('preview-verse');
                        if (previewVerse) {
                            previewVerse.innerHTML = `<img src="${src}" alt="Imagen" class="w-full h-full object-cover rounded-lg">`;
                            const imgV = previewVerse.querySelector('img');
                            if (imgV) imgV.addEventListener('click', function() { if (window.showImagePreview) window.showImagePreview(src); });
                        }
                        return;
                    }

                    if (!localPreview) {
                        localPreview = document.createElement('div');
                        localPreview.className = 'local-image-preview mt-3';
                        // estilo responsivo y click to open
                        localPreview.innerHTML = `<img src="${src}" alt="Vista previa" class="max-w-full max-h-40 object-cover rounded-lg cursor-pointer">`;
                        previewContainer.appendChild(localPreview);
                    } else {
                        localPreview.innerHTML = `<img src="${src}" alt="Vista previa" class="max-w-full max-h-40 object-cover rounded-lg cursor-pointer">`;
                    }

                    const img = localPreview.querySelector('img');
                    if (img) {
                        img.addEventListener('click', function() {
                            // abrir vista previa grande (usar la modal existente)
                            if (window.showImagePreview) window.showImagePreview(src);
                        });
                    }
                };
                reader.readAsDataURL(file);
            });
        });
    })();
});
</script>