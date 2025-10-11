{{-- Modal Universal de Edición --}}
<section id="{{ $modalId }}" class="tw-modal" aria-modal="true" role="dialog" aria-hidden="true">
    <div class="tw-modal-panel max-w-5xl max-h-[90vh] flex flex-col">
        <div class="tw-modal-header">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Editar {{ $sectionTitle ?? 'Elemento' }}</h3>
                <button data-modal-close class="btn btn-ghost text-slate-500 hover:text-slate-700 dark:text-slate-400" aria-label="Cerrar">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col overflow-hidden">
            @csrf
            <div class="tw-modal-body overflow-y-auto">
                {{-- Cabecera del formulario con indicador de sección --}}
                @if($sectionType)
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold shadow-md">
                            @switch($sectionType)
                            @case('news')
                            <i class="fas fa-newspaper"></i>
                            @break
                            @case('verse')
                            <i class="fas fa-book-bible"></i>
                            @break
                            @case('schedule')
                            <i class="fas fa-clock"></i>
                            @break
                            @case('slider')
                            <i class="fas fa-images"></i>
                            @break
                            @case('role')
                            <i class="fas fa-user-shield"></i>
                            @break
                            @case('user')
                            <i class="fas fa-user-circle"></i>
                            @break
                            @endswitch
                        </div>
                        <div>
                            <h4 class="text-base font-semibold text-slate-900 dark:text-white">Editar {{ $sectionTitle }}</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Modifica la información del {{ strtolower($sectionTitle) }}</p>
                        </div>
                    </div>
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
                        <div>
                            <label for="title" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Título</label>
                            <input id="title" type="text" name="title" value="{{ $tableM->title ?? '' }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label for="verse" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Versículo</label>
                            <input id="verse" type="text" name="verse" value="{{ $tableM->verse ?? '' }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label for="abstract" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Resumen</label>
                            <textarea id="abstract" name="abstract" rows="3" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $tableM->abstract ?? '' }}</textarea>
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
                                <input id="day_{{ $num }}" type="checkbox" name="day[]" value="{{ $num }}"
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
            <div class="tw-modal-footer">
                <button type="button" data-modal-close class="btn btn-secondary">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</section>

{{-- Modal para vista previa de imagen --}}
<section id="imagePreviewModal" class="tw-modal" aria-modal="true" role="dialog" aria-hidden="true">
    <div class="tw-modal-panel max-w-5xl">
        <div class="tw-modal-header">
            <h3 class="text-lg font-semibold">Vista previa de imagen</h3>
            <button data-modal-close class="btn btn-ghost" aria-label="Cerrar">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="tw-modal-body p-0">
            <img id="previewImage" src="" alt="Vista previa" class="w-full h-auto">
        </div>
    </div>
</section>

<script>
    function previewImage(url) {
        var img = document.getElementById('previewImage');
        var modal = document.getElementById('imagePreviewModal');
        if (img && modal) {
            img.src = url;
            modal.classList.add('open');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Delegación para botones con data-preview-url
        document.body.addEventListener('click', function(e) {
            var btn = e.target.closest('[data-preview-url]');
            if (btn) {
                var url = btn.getAttribute('data-preview-url');
                if (url) previewImage(url);
            }
        });

        // Vista previa de imágenes al seleccionar archivos del slider
        var imageLeftInput = document.getElementById('image_left');
        var imageRightInput = document.getElementById('image_right');

        if (imageLeftInput) {
            imageLeftInput.addEventListener('change', function(ev) {
                var file = ev.target.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(ev2) {
                        var previewLeft = document.getElementById('preview-left');
                        if (previewLeft) {
                            previewLeft.innerHTML = '<img src="' + ev2.target.result + '" alt="Vista previa" class="w-full h-full object-cover rounded-lg">';
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        if (imageRightInput) {
            imageRightInput.addEventListener('change', function(ev) {
                var file = ev.target.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(ev2) {
                        var previewRight = document.getElementById('preview-right');
                        if (previewRight) {
                            previewRight.innerHTML = '<img src="' + ev2.target.result + '" alt="Vista previa" class="w-full h-full object-cover rounded-lg">';
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>