<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\DataTables\UserDataTable;
use App\Models\User;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\Verse;
use App\Models\Banner;
use App\Models\News;

class AdminController extends Controller
{
    /**
     * Obtener el programa actualmente en emisión.
     * Devuelve un Schedule o null si no hay ninguno.
     */
    private function getCurrentProgram(): ?Schedule
    {
        try {
            $now = now();
            $currentDay = $now->dayOfWeekIso; // 1=lunes ... 7=domingo
            $currentTime = $now->format('H:i');

            $program = Schedule::where('day', $currentDay)
                ->whereNull('deleted_at')
                ->where('start', '<=', $currentTime)
                ->where('end', '>', $currentTime)
                ->first();

            return $program;
        } catch (\Throwable $e) {
            // Si algo falla no tumbes el dashboard
            return null;
        }
    }

    /**
     * Dashboard principal
     */
    public function index()
    {
        $stats = [
            'users'     => User::count(),
            'verses'    => Verse::count(),
            'schedules' => Schedule::count(),
            'banners'   => Banner::count(),
            'news'      => News::count(),
        ];

        $latestVerses = Verse::orderByDesc('date')
            ->take(5)
            ->get(['id', 'date', 'image', 'video']);

        $latestNews = News::orderByDesc('created_at')
            ->take(5)
            ->get(['id', 'title', 'created_at']);

        $currentProgram = $this->getCurrentProgram();

        return view('admin.dashboard', compact(
            'stats',
            'latestVerses',
            'latestNews',
            'currentProgram'
        ));
    }

    /**
     * Perfil del usuario autenticado
     */
    public function profile()
    {
        // Usar Auth::user() en vez de auth()->user()
        // calma a Intelephense y es equivalente en Laravel.
        $user = Auth::user();

        if ($user) {
            return view('admin.profile', compact('user'));
        }

        // Vista de login con notación correcta tipo Blade
        return view('auth.login');
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
        // Validación básica
        $validated = $request->validate([
            'select'   => ['required', 'exists:roles,id'],
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = new User();
        $user->role_id    = $validated['select'];
        $user->name       = $validated['name'];
        $user->email      = $validated['email'];
        $user->password   = Hash::make($validated['password']); // <- HASH!
        // created_at / updated_at se setean solos si $timestamps = true (default)
        $user->save();

        return redirect()
            ->back()
            ->with('mensaje', 'El usuario ha sido creado');
    }

    /**
     * DataTable de usuarios
     */
    public function ushow(UserDataTable $userDataTable)
    {
        // Nota: el nombre de la variable $userDataTable evita pisar App\Models\User
        return $userDataTable->render('admin.user.show-user');
    }

    /**
     * Ver un usuario individual
     */
    public function uview($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.view-user', compact('user'));
    }

    /**
     * Actualizar usuario existente
     */
    public function uedit($id, Request $request)
    {
        $user = User::findOrFail($id);

        // validamos. El email debe ser único excepto el del propio usuario
        $validated = $request->validate([
            'role_id'  => ['required', 'exists:roles,id'],
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user->role_id = $validated['role_id'];
        $user->name    = $validated['name'];
        $user->email   = $validated['email'];

        // Si se envió password, lo actualizamos hasheado
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // updated_at también se setea solo, pero lo forzamos si quieres explícito
        $user->updated_at = Carbon::now();
        $user->save();

        return redirect()
            ->back()
            ->with('mensaje', 'Los datos del usuario han sido actualizados');
    }

    /**
     * Eliminar usuario (delete normal / SoftDelete según el modelo)
     */
    public function udelete($id)
    {
        $user = User::findOrFail($id);
        $user->delete(); // si User usa SoftDeletes -> soft delete. Si no, delete físico.

        return redirect()
            ->back()
            ->with('mensaje', 'El usuario ha sido eliminado');
    }

    /**
     * Marcar como "no disponible al público"
     * OJO: tu mensaje dice "publicación", pero aquí actúas sobre User.
     * Esto asume que 'deleted_at' en User se usa como soft-block manual.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->deleted_at = Carbon::now();
        $user->save();

        return redirect()
            ->back()
            ->with('success', 'La publicación no está disponible al público');
    }

    /**
     * Volver a activar.
     */
    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->deleted_at = null;
        $user->save();

        return redirect()
            ->back()
            ->with('success', 'La publicación ha sido activada al público');
    }

    /**
     * Vista topbar (parece UI auxiliar)
     */
    public function center()
    {
        $hoy = Carbon::now();
        return view('admin.topbar', compact('hoy'));
    }
}
