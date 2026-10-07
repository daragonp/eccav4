<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\Verse;
use App\Models\Banner;
use App\Models\News;
use App\Models\Worship;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    /**
     * Dashboard principal
     */
    public function index()
    {
        $cacheKey = 'dashboard:stats:' . Auth::id();

        // Cacheamos estadísticas globales y listados pesados
        $dashboardData = Cache::remember($cacheKey, now()->addMinutes(3), function () {
            return [
                'stats' => [
                    'users'     => User::count(),
                    'verses'    => Verse::count(),
                    'schedules' => Schedule::count(),
                    'banners'   => Banner::count(),
                    'news'      => News::count(),
                    'worships'  => Worship::count(),
                    'podcasts'  => \App\Models\Podcast::count(),
                ],
                'smartMetrics' => [
                    'worships_pending_ai'  => Worship::where('ai_processed', false)->count(),
                    'worships_processed_ai'=> Worship::where('ai_processed', true)->count(),
                    'verses_this_month'    => Verse::whereMonth('date', now()->month)->whereYear('date', now()->year)->count(),
                    'schedules_today'      => Schedule::where('day', now()->dayOfWeekIso)->whereNull('deleted_at')->count(),
                    'php_version'          => PHP_VERSION,
                    'laravel_version'      => app()->version(),
                ],
                'latestVerses'  => Verse::orderByDesc('date')
                    ->take(5)
                    ->get(['id', 'date', 'image', 'video']),
                'latestNews'    => News::orderByDesc('created_at')
                    ->take(5)
                    ->get(['id', 'title', 'created_at']),
                'latestWorships'=> Worship::orderByDesc('broadcast')
                    ->take(5)
                    ->get(['id', 'title', 'broadcast', 'audio', 'video', 'pdfdoc', 'ai_processed', 'autor']),
                'latestPodcasts'=> \App\Models\Podcast::with('category')
                    ->orderByDesc('id')
                    ->take(5)
                    ->get(['id', 'title', 'description', 'audio_file', 'category_id', 'created_at']),
            ];
        });

        // El "ahora suena" se obtiene en tiempo real desde el endpoint de WideStream
        // (ver nowPlaying()), consumido por el dashboard vía fetch. Ya no se calcula
        // la programación local (currentProgram/nextProgram/parrilla) en esta vista.
        $dashboardData['streamUrl'] = config('app.stream_url') ?: 'https://widestream.app/radio.aac';
        $dashboardData['streamHls'] = config('app.stream_hls_url') ?: 'https://widestream.app/hls/live.m3u8';
        $dashboardData['streamEmbed'] = config('app.stream_embed_url') ?: 'https://widestream.app/embed/main';

        return view('admin.dashboard', $dashboardData);
    }

    /**
     * Proxy del endpoint "ahora suena" de WideStream.
     *
     * Llama al API de WideStream server-side con la clave almacenada en
     * config('services.widestream.api_key') para no exponerla al navegador,
     * y devuelve el JSON normalizado al dashboard. Cachea 10 segundos para
     * no saturar el servicio ante refrescos frecuentes.
     */
    public function nowPlaying()
    {
        $apiKey = (string) config('services.widestream.api_key');
        $apiBase = rtrim((string) config('services.widestream.api_base', 'https://widestream.app/api/v1'), '/');

        if ($apiKey === '') {
            return response()->json([
                'playing' => false,
                'error'   => 'WideStream API key no configurada.',
            ], 200);
        }

        $data = Cache::remember('widestream:now_playing', now()->addSeconds(10), function () use ($apiKey, $apiBase) {
            try {
                $response = \Illuminate\Support\Facades\Http::withToken($apiKey)
                    ->timeout(5)
                    ->acceptJson()
                    ->get($apiBase . '/now-playing');

                if ($response->successful()) {
                    return $response->json();
                }

                return ['playing' => false, 'error' => 'Respuesta no exitosa de WideStream.'];
            } catch (\Throwable $e) {
                return ['playing' => false, 'error' => 'No se pudo contactar a WideStream.'];
            }
        });

        return response()->json($data, 200);
    }

    /**
     * Perfil del usuario autenticado
     */
    public function profile()
    {
        // Usar Auth::user() en vez de auth()->user()
        // calma a Intelephense y es equivalente en Laravel.
        $user = Auth::user()?->loadMissing('roles');

        if ($user) {
            return view('admin.profile', compact('user'));
        }

        // Vista de login con notación correcta tipo Blade
        return view('auth.login');
    }

    /**
     * Actualiza el perfil del usuario autenticado.
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:30'],
            'birthdate' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (Schema::hasColumn('users', 'phone')) {
            $user->phone = $validated['phone'] ?? null;
        }
        if (Schema::hasColumn('users', 'birthdate')) {
            $user->birthdate = $validated['birthdate'] ?? null;
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $request->file('image');
            $directory = public_path('images/users');

            if (!is_dir($directory)) {
                @mkdir($directory, 0775, true);
            }

            $newImageName = 'user_' . Str::uuid()->toString() . '.' . $image->getClientOriginalExtension();
            $image->move($directory, $newImageName);

            if (!empty($user->image) && str_starts_with($user->image, 'user_')) {
                $oldImagePath = $directory . DIRECTORY_SEPARATOR . $user->image;
                if (is_file($oldImagePath)) {
                    @unlink($oldImagePath);
                }
            }

            $user->image = $newImageName;
        }

        $user->save();

        return redirect()
            ->back()
            ->with('profile_updated', 'Perfil actualizado correctamente.');
    }

    /**
     * Formulario "crear usuario"
     */
    public function user()
    {
        $roles = Role::all();
        return view('admin.new-user', compact('roles'));
    }

    /**
     * Guardar nuevo usuario
     */
    public function ustore(Request $request)
    {
        $roleId = $request->input('role_id') ?? $request->input('select');

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role_id'  => ['nullable', 'exists:roles,id'],
            'select'   => ['nullable', 'exists:roles,id'],
        ]);

        $user = new User();
        $user->name     = $validated['name'];
        $user->email    = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->image    = 'human.png';
        $user->save();

        if ($roleId) {
            $role = Role::find($roleId);
            if ($role) {
                $user->syncRoles([$role->name]);
            }
        }

        if ($request->input('status') === '0') {
            $user->delete();
        }

        return redirect()
            ->back()
            ->with('success', 'El usuario ha sido creado exitosamente.');
    }

    /**
     * Tabla nativa de usuarios
     */
    public function ushow(Request $request)
    {
        $query = User::withTrashed()->with('roles');

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();
        $roles = Role::orderBy('name')->get();

        return view('admin.user.show-user', compact('users', 'roles'));
    }

    /**
     * Ver un usuario individual
     */
    public function uview($id)
    {
        $user = User::withTrashed()->with('roles')->findOrFail($id);
        return view('admin.user.view-user', compact('user'));
    }

    /**
     * Actualizar usuario existente
     */
    public function uedit($id, Request $request)
    {
        $user = User::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'role_id'  => ['nullable', 'exists:roles,id'],
            'select'   => ['nullable', 'exists:roles,id'],
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $roleId = $validated['role_id'] ?? $validated['select'] ?? null;
        if (!empty($roleId)) {
            $role = Role::find($roleId);
            if ($role) {
                $user->syncRoles([$role->name]);
            }
        }

        // Manejo de estado activo / inactivo
        if ($request->has('status')) {
            $status = $request->input('status');
            $shouldBeActive = ($status === '1' || $status === 1 || $status === 'on' || $status === true);

            if ($shouldBeActive && $user->trashed()) {
                $user->restore();
            } elseif (!$shouldBeActive && !$user->trashed()) {
                if ((int)$id === (int)auth()->id()) {
                    return redirect()->back()->with('error', 'No puedes desactivar tu propia cuenta en sesión.');
                }
                $user->delete();
            }
        }

        return redirect()
            ->back()
            ->with('success', 'Los datos del usuario han sido actualizados exitosamente.');
    }

    /**
     * Eliminar usuario definitivamente (forceDelete)
     */
    public function udelete($id)
    {
        if ((int)$id === (int)auth()->id()) {
            return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user = User::withTrashed()->findOrFail($id);

        if ($this->isSuperAdminUser($user)) {
            $superAdminsCount = User::whereHas('roles', function($q) {
                $q->where('name', 'like', '%superadmin%')
                  ->orWhere('name', 'like', '%super administrador%')
                  ->orWhere('name', 'like', '%superadministrador%');
            })->count();

            if ($superAdminsCount <= 1) {
                return redirect()->back()->with('error', 'No es posible eliminar al único Superadministrador del sistema.');
            }
        }

        $user->forceDelete();

        return redirect()
            ->back()
            ->with('mensaje', 'El usuario ha sido eliminado definitivamente');
    }

    /**
     * Desactivar usuario (soft delete)
     */
    public function udestroy($id)
    {
        if ((int)$id === (int)auth()->id()) {
            return redirect()->back()->with('error', 'No puedes desactivar tu propia cuenta en sesión.');
        }

        $user = User::findOrFail($id);

        if ($this->isSuperAdminUser($user)) {
            $superAdminsCount = User::whereNull('deleted_at')->whereHas('roles', function($q) {
                $q->where('name', 'like', '%superadmin%')
                  ->orWhere('name', 'like', '%super administrador%')
                  ->orWhere('name', 'like', '%superadministrador%');
            })->count();

            if ($superAdminsCount <= 1) {
                return redirect()->back()->with('error', 'No es posible desactivar al único Superadministrador activo.');
            }
        }

        $user->delete();

        return redirect()
            ->back()
            ->with('success', 'El usuario ha sido desactivado del sistema.');
    }

    /**
     * Reactivar usuario desactivado (restore)
     */
    public function uactivate($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()
            ->back()
            ->with('success', 'El usuario ha sido reactivado en el sistema.');
    }

    /**
     * Alias retrocompatible para udestroy
     */
    public function destroy($id)
    {
        return $this->udestroy($id);
    }

    /**
     * Alias retrocompatible para uactivate
     */
    public function activate($id)
    {
        return $this->uactivate($id);
    }

    /**
     * Vista topbar (parece UI auxiliar)
     */
    public function center()
    {
        $hoy = Carbon::now();
        return view('admin.topbar', compact('hoy'));
    }

    /**
     * Vista para gestión de accesos (solo superadmin).
     */
    public function accessControl()
    {
        $columns = ['id', 'name', 'email'];
        if (Schema::hasColumn('users', 'role_id')) {
            $columns[] = 'role_id';
        }

        $users = User::query()
            ->with(['roles:id,name', 'permissions:id,name'])
            ->orderBy('name')
            ->get($columns)
            ->reject(fn (User $user) => $user->id === (int) Auth::id() || $this->isSuperAdminUser($user))
            ->values();

        $roles = SpatieRole::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $permissions = Permission::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.access.index', compact('users', 'roles', 'permissions'));
    }

    /**
     * Asigna roles y permisos directos a un usuario (solo superadmin).
     */
    public function updateUserAccess(Request $request, User $user)
    {
        if ($this->isSuperAdminUser($user)) {
            return redirect()
                ->back()
                ->withErrors(['access_control' => 'No está permitido modificar los accesos del superadministrador.']);
        }

        $validated = $request->validate([
            'roles' => ['nullable', 'array'],
            'roles.*' => ['integer', 'exists:roles,id'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $roleIds = array_map('intval', $validated['roles'] ?? []);
        $permissionIds = array_map('intval', $validated['permissions'] ?? []);

        $roleNames = SpatieRole::query()
            ->whereIn('id', $roleIds)
            ->pluck('name')
            ->all();

        $permissionNames = Permission::query()
            ->whereIn('id', $permissionIds)
            ->pluck('name')
            ->all();

        $user->syncRoles($roleNames);
        $user->syncPermissions($permissionNames);

        if (!empty($roleIds) && Schema::hasColumn('users', 'role_id')) {
            $user->role_id = $roleIds[0];
            $user->save();
        }

        return redirect()
            ->back()
            ->with('success', 'Accesos actualizados para ' . $user->name . '.');
    }

    /**
     * Prueba si un permiso está concedido para un usuario.
     */
    public function testUserPermission(Request $request, User $user)
    {
        $validated = $request->validate([
            'permission_id' => ['required', 'integer', 'exists:permissions,id'],
        ]);

        $permission = Permission::query()->findOrFail((int) $validated['permission_id']);
        $granted = $user->can($permission->name);

        return redirect()
            ->back()
            ->with('permission_test', [
                'user_id' => $user->id,
                'permission_name' => $permission->name,
                'granted' => $granted,
            ]);
    }

    /**
     * Vista de configuración del panel con diagnóstico del sistema.
     */
    public function settings()
    {
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug'),
            'app_url' => config('app.url'),
            'db_connection' => config('database.default'),
            'app_version' => app(\App\Services\VersionService::class)->getVersion(),
            'provider' => 'WideStream (widestream.app)',
            'station_url' => config('app.stream_station_url', 'https://widestream.app/'),
            'stream_url' => config('app.stream_url', 'https://widestream.app/radio.aac'),
            'stream_hls' => config('app.stream_hls_url', 'https://widestream.app/hls/live.m3u8'),
            'stream_embed' => config('app.stream_embed_url', 'https://widestream.app/embed/main'),
            'domains' => [
                'www.tezbrillante.org',
                'www.emancipacioncristianaafro.org',
            ],
        ];

        return view('admin.settings', compact('systemInfo'));
    }

    /**
     * Retorna el HTML del modal universal de edición para un elemento específico.
     */
    public function getEditModal($type, $id)
    {
        $model = null;
        $title = '';

        switch ($type) {
            case 'user':
                $model = User::findOrFail($id);
                $title = 'Usuario';
                break;
            case 'role':
                $model = Role::findOrFail($id);
                $title = 'Rol';
                break;
            case 'worship':
                $model = \App\Models\Worship::withTrashed()->findOrFail($id);
                $title = 'Culto Dominical';
                break;
            case 'verse':
                $model = Verse::withTrashed()->findOrFail($id);
                $title = 'Palabra de vida';
                break;
            case 'slider':
                $model = Banner::findOrFail($id);
                $title = 'Banner Carrusel';
                break;
            case 'news':
                $model = News::withTrashed()->findOrFail($id);
                $title = 'Mensaje de la semana';
                break;
            case 'podcast':
                $model = \App\Models\Podcast::findOrFail($id);
                $title = 'PodCast';
                break;
            case 'category':
                $model = \App\Models\Category::findOrFail($id);
                $title = 'Categoría de Podcast';
                break;
            case 'schedule':
                $model = Schedule::withTrashed()->findOrFail($id);
                $title = 'Programación';
                break;
            case 'library':
                $model = \App\Models\LibraryResource::withTrashed()->findOrFail($id);
                $title = 'Recurso de Biblioteca';
                break;
        }

        if (!$model) {
            return response('No se encontró el elemento', 404);
        }

        $formAction = '';
        switch ($type) {
            case 'user': $formAction = url("update-user/{$id}"); break;
            case 'role': $formAction = url("updaterole/{$id}"); break;
            case 'worship': $formAction = url("update-worship/{$id}"); break;
            case 'verse': $formAction = url("update-quote/{$id}"); break;
            case 'slider': $formAction = url("update-slider/{$id}"); break;
            case 'news': $formAction = url("update-news/{$id}"); break;
            case 'podcast': $formAction = url("updatepodcast/{$id}"); break;
            case 'category': $formAction = url("updatecategory/{$id}"); break;
            case 'schedule': $formAction = url("update-schedule/{$id}"); break;
            case 'library': $formAction = url("update-library/{$id}"); break;
        }

        return view('admin.partials.universal-edit-modal', [
            'modalId' => 'EditModal_' . $id,
            'formAction' => $formAction,
            'tableM' => $model,
            'sectionType' => $type,
            'sectionTitle' => $title,
        ]);
    }

    private function isSuperAdminUser(User $user): bool
    {
        if (isset($user->role_id) && (int) $user->role_id === 1) {
            return true;
        }

        $roleNames = collect($user->roles ?? [])
            ->pluck('name')
            ->map(fn ($name) => mb_strtolower((string) $name));

        return $roleNames->contains('superadministrador')
            || $roleNames->contains('super-admin')
            || $roleNames->contains('super admin');
    }

    /**
     * Muestra la vista de sugerencias.
     */
    public function suggestions()
    {
        return view('admin.suggestions');
    }

    /**
     * Guarda la sugerencia recibida (simulada por log y redirigida con éxito).
     */
    public function storeSuggestion(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'in:sugerencia,error,comentario,otro'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        // Registrar la sugerencia en los logs del sistema para auditoría
        \Illuminate\Support\Facades\Log::info('Nueva sugerencia recibida', [
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'user_email' => \Illuminate\Support\Facades\Auth::user()->email,
            'type' => $validated['type'],
            'message' => $validated['message'],
        ]);

        return redirect()
            ->back()
            ->with('success', '¡Gracias por su sugerencia! Ha sido recibida correctamente por el equipo de administración.');
    }
}
