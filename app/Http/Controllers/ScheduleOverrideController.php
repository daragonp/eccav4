<?php

namespace App\Http\Controllers;

use App\Models\ScheduleOverride;
use App\Models\Week;
use Illuminate\Http\Request;
use Exception;

class ScheduleOverrideController extends Controller
{
    /**
     * Mostrar la lista de días festivos / overrides
     */
    public function index()
    {
        $overrides = ScheduleOverride::with('week')
            ->orderBy('date', 'desc')
            ->paginate(20);
        
        $weeks = Week::all();
        
        return view('admin.schedule.holiday-schedule', compact('overrides', 'weeks'));
    }

    /**
     * Almacenar un nuevo override
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'date' => 'required|date|after_or_equal:today',
            'override_day' => 'required|integer|between:1,7',
            'reason' => 'nullable|string|max:255',
        ], [
            'date.required' => 'La fecha es obligatoria.',
            'date.after_or_equal' => 'La fecha debe ser hoy o una fecha futura.',
            'override_day.required' => 'Debe seleccionar un día de programación.',
            'override_day.between' => 'El día debe estar entre 1 (Lunes) y 7 (Domingo).',
        ]);

        try {
            // Verificar si ya existe un override activo para esa fecha
            $existing = ScheduleOverride::where('date', $request->date)
                ->where('is_active', true)
                ->whereNull('deleted_at')
                ->first();

            if ($existing) {
                return back()
                    ->withErrors(['date' => 'Ya existe una programación especial activa para esta fecha.'])
                    ->withInput();
            }

            ScheduleOverride::create([
                'date' => $request->date,
                'override_day' => $request->override_day,
                'reason' => $request->reason,
                'is_active' => true,
            ]);

            return redirect()->back()->with('success', 'Programación especial agregada correctamente.');
            
        } catch (Exception $e) {
            return back()
                ->withErrors(['error' => 'Error al crear la programación especial: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Actualizar un override existente
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'date' => 'required|date',
            'override_day' => 'required|integer|between:1,7',
            'reason' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        try {
            $override = ScheduleOverride::findOrFail($id);

            // Si se está cambiando la fecha, verificar que no haya conflictos
            if ($request->date != $override->date) {
                $existing = ScheduleOverride::where('date', $request->date)
                    ->where('is_active', true)
                    ->where('id', '!=', $id)
                    ->whereNull('deleted_at')
                    ->first();

                if ($existing) {
                    return back()
                        ->withErrors(['date' => 'Ya existe una programación especial activa para esta fecha.'])
                        ->withInput();
                }
            }

            $override->update([
                'date' => $request->date,
                'override_day' => $request->override_day,
                'reason' => $request->reason,
                'is_active' => $request->has('is_active') ? true : false,
            ]);

            return redirect()->back()->with('success', 'Programación especial actualizada correctamente.');
            
        } catch (Exception $e) {
            return back()
                ->withErrors(['error' => 'Error al actualizar la programación especial: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Activar/Desactivar un override
     */
    public function toggleActive($id)
    {
        try {
            $override = ScheduleOverride::findOrFail($id);
            $override->is_active = !$override->is_active;
            $override->save();

            $status = $override->is_active ? 'activada' : 'desactivada';
            return redirect()->back()->with('success', "Programación especial {$status} correctamente.");
            
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al cambiar el estado: ' . $e->getMessage()]);
        }
    }

    /**
     * Eliminar un override (soft delete)
     */
    public function destroy($id)
    {
        try {
            $override = ScheduleOverride::findOrFail($id);
            $override->delete();

            return redirect()->back()->with('success', 'Programación especial eliminada correctamente.');
            
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al eliminar la programación especial: ' . $e->getMessage()]);
        }
    }

    /**
     * Obtener la programación para una fecha específica (API)
     * Considera overrides si existen
     */
    public function getScheduleForDate($date)
    {
        try {
            // Verificar si hay un override para esta fecha
            $override = ScheduleOverride::where('date', $date)
                ->where('is_active', true)
                ->whereNull('deleted_at')
                ->first();

            // Obtener el día de la semana (1-7)
            $dayOfWeek = date('N', strtotime($date)); // 1 = Lunes, 7 = Domingo
            
            // Si hay override, usar ese día, sino usar el día normal
            $dayToUse = $override ? $override->override_day : $dayOfWeek;

            // Obtener la programación de ese día
            $schedules = \App\Models\Schedule::where('day', $dayToUse)
                ->whereNull('deleted_at')
                ->orderBy('start')
                ->get()
                ->map(function ($schedule) {
                    return [
                        'name' => $schedule->name,
                        'host' => $schedule->host,
                        'start' => $schedule->start,
                        'end' => $schedule->end,
                        'image_url' => $schedule->image_url,
                    ];
                });

            return response()->json([
                'success' => true,
                'date' => $date,
                'has_override' => $override ? true : false,
                'override_reason' => $override ? $override->reason : null,
                'day_used' => $dayToUse,
                'schedules' => $schedules,
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la programación: ' . $e->getMessage(),
            ], 500);
        }
    }
}
