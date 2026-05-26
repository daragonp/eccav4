<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\ScheduleOverride;
use App\DataTables\ScheduleDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Exception;

class ScheduleController extends Controller
{
    /**
     * Verificar si hay conflicto de horario para un día específico
     *
     * @param string $start Hora de inicio (HH:MM)
     * @param string $end Hora de finalización (HH:MM)
     * @param int $day Día de la semana (1-7)
     * @param string|null $emissionKey Clave de emisión a excluir (para edición)
     * @return bool True si hay conflicto
     */
    private function hayConflictoHorario($start, $end, $day, $emissionKey = null)
    {
        $day = (int) $day;
        $nextDay = $day === 7 ? 1 : $day + 1;
        $previousDay = $day === 1 ? 7 : $day - 1;

        $newSegments = $this->getTimeSegmentsWithDay($day, $start, $end);
        $candidateDays = [$day];

        if (count($newSegments) > 1) {
            $candidateDays[] = $nextDay;
        } else {
            $candidateDays[] = $previousDay;
        }

        $query = Schedule::whereIn('day', array_unique($candidateDays))
            ->whereNull('deleted_at')
            ->when($emissionKey, function ($query) use ($emissionKey) {
                $query->where('emission_key', '!=', $emissionKey);
            });

        $existingSchedules = $query->get();

        foreach ($existingSchedules as $schedule) {
            $existingSegments = $this->getTimeSegmentsWithDay((int) $schedule->day, $schedule->start, $schedule->end);

            foreach ($existingSegments as $existingSegment) {
                foreach ($newSegments as $newSegment) {
                    if ($this->segmentsOverlap($existingSegment, $newSegment)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    private function getTimeSegmentsWithDay(int $day, string $start, string $end): array
    {
        $startTime = Carbon::createFromFormat('H:i', $start);
        $endTime = Carbon::createFromFormat('H:i', $end);
        $startMinutes = $startTime->hour * 60 + $startTime->minute;
        $endMinutes = $endTime->hour * 60 + $endTime->minute;

        if ($endMinutes > $startMinutes) {
            return [[
                'day' => $day,
                'start' => $startMinutes,
                'end' => $endMinutes,
            ]];
        }

        $nextDay = $day === 7 ? 1 : $day + 1;

        return [
            [
                'day' => $day,
                'start' => $startMinutes,
                'end' => 1440,
            ],
            [
                'day' => $nextDay,
                'start' => 0,
                'end' => $endMinutes,
            ],
        ];
    }

    private function segmentsOverlap(array $first, array $second): bool
    {
        return $first['day'] === $second['day']
            && $first['start'] < $second['end']
            && $second['start'] < $first['end'];
    }

    private function traducirDia($numero)
    {
        $dias = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo'
        ];
        return $dias[$numero] ?? 'Día desconocido';
    }

    /**
     * Mostrar la lista de programas con DataTables
     */
    public function show(ScheduleDataTable $dataTable)
    {
        return $dataTable->render('admin.schedule.show-schedule');
    }

    /**
     * Almacenar un nuevo programa
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'start' => 'required|date_format:H:i',
            'end' => 'required|date_format:H:i',
            'host' => 'required|string|max:255',
            'day' => 'required|array|min:1|max:7',
            'day.*' => 'integer|between:1,7',
            'about' => 'nullable|string|max:5000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'name.required' => 'El nombre del programa es obligatorio.',
            'start.required' => 'La hora de inicio es obligatoria.',
            'end.required' => 'La hora de finalización es obligatoria.',
            'end.date_format' => 'La hora de finalización debe tener formato HH:MM.',
            'host.required' => 'El director(a) del programa es obligatorio.',
            'day.required' => 'Debe seleccionar al menos un día de emisión.',
            'day.min' => 'Debe seleccionar al menos un día de emisión.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.max' => 'La imagen no debe superar los 2MB.',
        ]);

        try {
            $start = Carbon::createFromFormat('H:i', $request->start);
            $end = Carbon::createFromFormat('H:i', $request->end);

            // Generar clave única para el bloque de programas
            $emissionKey = Str::slug($request->name) . '_' . $request->start . '_' . $request->end;

            // Validar horario de inicio y fin
            if ($start->equalTo($end)) {
                return back()
                    ->withErrors(['end' => 'La hora de finalización debe ser distinta a la hora de inicio.'])
                    ->withInput();
            }

            if ($end->lessThanOrEqualTo($start)) {
                $end = $end->copy()->addDay();
            }
            $duration = $start->diffInMinutes($end);

            // Verificar conflictos para cada día seleccionado
            foreach ($request->day as $day) {
                if ($this->hayConflictoHorario($request->start, $request->end, $day)) {
                    return back()
                        ->withErrors(['day' => 'Ya existe un programa en ese horario para el día ' . $this->traducirDia($day) . '.'])
                        ->withInput();
                }
            }

            // Procesar imagen
            $imgName = 'genericprogramimage.png';
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $image = $request->file('image');
                $imgName = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('images/schedule', $image, $imgName);
            }

            // Crear un registro por cada día seleccionado
            foreach ($request->day as $day) {
                Schedule::create([
                    'name' => $request->name,
                    'slug' => Str::slug($request->name),
                    'start' => $request->start,
                    'end' => $request->end,
                    'duration' => $duration,
                    'host' => $request->host,
                    'about' => $request->about,
                    'day' => $day,
                    'image' => $imgName,
                    'emission_key' => $emissionKey,
                ]);
            }

            return redirect('/show-schedule')->with('success', 'Programa agregado correctamente.');

        } catch (Exception $e) {
            return back()
                ->withErrors(['error' => 'Error al crear el programa: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Ver detalles de un programa específico
     */
    public function view($id)
    {
        $schedule = Schedule::findOrFail($id);

        // Obtener todos los días del mismo bloque
        $allDays = Schedule::where('emission_key', $schedule->emission_key)
            ->whereNull('deleted_at')
            ->orderBy('day')
            ->get();

        return view('admin.schedule.view-schedule', compact('schedule', 'allDays'));
    }

    /**
     * Actualizar un programa existente
     */
    public function edit($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'start' => 'required|date_format:H:i',
            'end' => 'required|date_format:H:i',
            'host' => 'required|string|max:255',
            'day' => 'required|array|min:1|max:7',
            'day.*' => 'integer|between:1,7',
            'about' => 'nullable|string|max:5000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'name.required' => 'El nombre del programa es obligatorio.',
            'start.required' => 'La hora de inicio es obligatoria.',
            'end.required' => 'La hora de finalización es obligatoria.',
            'end.date_format' => 'La hora de finalización debe tener formato HH:MM.',
            'host.required' => 'El director(a) del programa es obligatorio.',
            'day.required' => 'Debe seleccionar al menos un día de emisión.',
            'day.min' => 'Debe seleccionar al menos un día de emisión.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.max' => 'La imagen no debe superar los 2MB.',
        ]);

        try {
            $original = Schedule::findOrFail($id);
            $emissionKey = $original->emission_key ?? uniqid();

            $start = Carbon::createFromFormat('H:i', $request->start);
            $end = Carbon::createFromFormat('H:i', $request->end);

            if ($start->equalTo($end)) {
                return back()
                    ->withErrors(['end' => 'La hora de finalización debe ser distinta a la hora de inicio.'])
                    ->withInput();
            }

            if ($end->lessThanOrEqualTo($start)) {
                $end = $end->copy()->addDay();
            }
            $duration = $start->diffInMinutes($end);

            // Verificar conflictos para cada día seleccionado
            foreach ($request->day as $day) {
                if ($this->hayConflictoHorario($request->start, $request->end, $day, $emissionKey)) {
                    return back()
                        ->withErrors(['day' => "Ya existe un programa en ese horario el {$this->traducirDia($day)}."])
                        ->withInput();
                }
            }

            // Eliminar todos los registros anteriores del bloque (soft delete)
            Schedule::where('emission_key', $emissionKey)->delete();

            // Procesar imagen
            $imgName = $original->image;
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                // Eliminar imagen antigua si no es la genérica
                if ($imgName != 'genericprogramimage.png' && Storage::disk('public')->exists('images/schedule/' . $imgName)) {
                    Storage::disk('public')->delete('images/schedule/' . $imgName);
                }

                $image = $request->file('image');
                $imgName = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('images/schedule', $image, $imgName);
            }

            // Crear los nuevos registros por día con la información actualizada
            foreach ($request->day as $day) {
                Schedule::create([
                    'name' => $request->name,
                    'slug' => Str::slug($request->name),
                    'start' => $request->start,
                    'end' => $request->end,
                    'duration' => $duration,
                    'host' => $request->host,
                    'about' => $request->about,
                    'day' => $day,
                    'image' => $imgName,
                    'emission_key' => $emissionKey,
                ]);
            }

            return redirect('/show-schedule')->with('success', 'Programa actualizado correctamente.');

        } catch (Exception $e) {
            return back()
                ->withErrors(['error' => 'Error al actualizar el programa: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Eliminar permanentemente un programa (hard delete)
     */
    public function destroy($id)
    {
        try {
            $program = Schedule::withTrashed()->findOrFail($id);
            $emissionKey = $program->emission_key;
            $image = $program->image;

            // Eliminar permanentemente todos los registros del bloque
            Schedule::withTrashed()
                ->where('emission_key', $emissionKey)
                ->forceDelete();

            // Eliminar la imagen física si no es la genérica
            if ($image != 'genericprogramimage.png' && Storage::disk('public')->exists('images/schedule/' . $image)) {
                Storage::disk('public')->delete('images/schedule/' . $image);
            }

            return redirect()->back()->with('success', 'El programa ha sido eliminado definitivamente.');

        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al eliminar el programa: ' . $e->getMessage()]);
        }
    }

    /**
     * Activar un programa (restaurar soft delete)
     */
    public function activate($id)
    {
        try {
            $program = Schedule::withTrashed()->findOrFail($id);

            Schedule::withTrashed()
                ->where('emission_key', $program->emission_key)
                ->restore();

            return redirect()->back()->with('success', 'El programa ha sido activado correctamente.');

        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al activar el programa: ' . $e->getMessage()]);
        }
    }

    /**
     * Desactivar un programa (soft delete)
     */
    public function delete($id)
    {
        try {
            $program = Schedule::findOrFail($id);

            Schedule::where('emission_key', $program->emission_key)
                ->update(['deleted_at' => now()]);

            return redirect()->back()->with('success', 'El programa ha sido desactivado correctamente.');

        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al desactivar el programa: ' . $e->getMessage()]);
        }
    }

    /**
     * Obtener el programa actual en emisión (JSON para reproductor web)
     */
    public function getCurrentProgram()
    {
        try {
            $program = Schedule::getCurrentProgram();

            if (!$program) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay programa en emisión en este momento',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Programa en emisión',
                'data' => [
                    'id' => $program->id,
                    'name' => $program->name,
                    'host' => $program->host,
                    'about' => $program->about,
                    'start' => $program->start,
                    'end' => $program->end,
                    'duration' => $program->duration,
                    'formatted_duration' => $program->formatted_duration,
                    'day' => $program->day,
                    'day_name' => $program->day_name,
                    'image_url' => $program->image_url,
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el programa actual: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Obtener la programación completa de un día específico (JSON)
     */
    public function getDaySchedule($day)
    {
        try {
            $programs = Schedule::where('day', $day)
                ->whereNull('deleted_at')
                ->orderBy('start')
                ->get()
                ->map(function ($program) {
                    return [
                        'id' => $program->id,
                        'name' => $program->name,
                        'host' => $program->host,
                        'start' => $program->start,
                        'end' => $program->end,
                        'duration' => $program->formatted_duration,
                        'image_url' => $program->image_url,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Programación del día',
                'data' => $programs
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la programación: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Importar programación desde archivo CSV
     */
    public function importFromCSV(Request $request)
    {
        $this->validate($request, [
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
        ], [
            'csv_file.required' => 'Debe seleccionar un archivo CSV.',
            'csv_file.mimes' => 'El archivo debe ser un CSV.',
            'csv_file.max' => 'El archivo no debe superar los 10MB.',
        ]);

        try {
            $file = $request->file('csv_file');
            $path = $file->getRealPath();
            $data = array_map('str_getcsv', file($path));

            $imported = 0;
            $errors = [];
            $lineNumber = 1;

            foreach ($data as $row) {
                $lineNumber++;

                // Validar que la fila tenga todos los campos necesarios
                if (count($row) < 9) {
                    $errors[] = "Línea {$lineNumber}: Faltan campos requeridos";
                    continue;
                }

                try {
                    // Parsear los datos del CSV
                    // Formato: name, image, slug, about, start, end, host, day, duration, created_at, updated_at, emission_key
                    $name = trim($row[0]);
                    $image = trim($row[1]);
                    $slug = trim($row[2]);
                    $about = trim($row[3]);
                    $start = trim($row[4]);
                    $end = trim($row[5]);
                    $host = trim($row[6]);
                    $day = (int)trim($row[7]);
                    $duration = (int)trim($row[8]);
                    $emissionKey = isset($row[11]) ? trim($row[11]) : Str::slug($name) . '_' . $start . '_' . $end;

                    // Validar datos
                    if (empty($name) || empty($start) || empty($end) || empty($host) || $day < 1 || $day > 7) {
                        $errors[] = "Línea {$lineNumber}: Datos inválidos";
                        continue;
                    }

                    // Verificar conflicto de horario
                    if ($this->hayConflictoHorario($start, $end, $day)) {
                        $errors[] = "Línea {$lineNumber}: Conflicto de horario para {$name} el {$this->traducirDia($day)}";
                        continue;
                    }

                    // Crear el programa
                    Schedule::create([
                        'name' => $name,
                        'image' => $image,
                        'slug' => $slug,
                        'about' => $about,
                        'start' => $start,
                        'end' => $end,
                        'host' => $host,
                        'day' => $day,
                        'duration' => $duration,
                        'emission_key' => $emissionKey,
                    ]);

                    $imported++;

                } catch (Exception $e) {
                    $errors[] = "Línea {$lineNumber}: Error al importar - " . $e->getMessage();
                }
            }

            $message = "Se importaron {$imported} programas correctamente.";
            if (count($errors) > 0) {
                $message .= " Errores encontrados: " . implode(', ', $errors);
            }

            return redirect('/show-schedule')->with('success', $message);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Error al importar el archivo: ' . $e->getMessage()])->withInput();
        }
    }
}
