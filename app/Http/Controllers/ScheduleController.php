<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Schedule;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\ScheduleDataTable;
use Illuminate\Support\Facades\Storage; // Asegúrate de tener esta importación

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private function hayConflictoHorario($start, $end, $day, $emissionKey = null)
    {
        return Schedule::where('day', $day)
            ->whereNull('deleted_at')
            ->when($emissionKey, function ($query) use ($emissionKey) {
                // Excluye los registros que pertenecen al mismo bloque que se está editando.
                // Esto permite que un programa se "solape consigo mismo" si se está editando.
                $query->where('emission_key', '!=', $emissionKey);
            })
            ->where(function ($query) use ($start, $end) {
                // Lógica de solapamiento de horarios:
                // (Inicio entre el nuevo rango) OR (Fin entre el nuevo rango) OR
                // (Nuevo rango envuelve al existente) OR (Existente envuelve al nuevo rango)
                $query->where(function ($q) use ($start, $end) {
                    $q->where('start', '<', $end)->where('end', '>', $start);
                });
            })
            ->exists();
    }

    private function traducirDia($numero)
    {
        $dias = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo'];
        return $dias[$numero] ?? 'Día desconocido';
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'start' => 'required|date_format:H:i',
            'end' => 'required|date_format:H:i|after:start',
            'host' => 'required',
            'day' => 'required|array|min:1|max:7',
            'about' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $start = Carbon::createFromFormat('H:i', $request->start);
        $end = Carbon::createFromFormat('H:i', $request->end);

        $emissionKey = uniqid(); // Generar clave única para el bloque

        $imgName = 'genericprogramimage.png';
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $request->file('image');
            $imgName = uniqid() . '.' . $image->getClientOriginalExtension();
            // Usar Storage Facade para guardar la imagen
            Storage::disk('public')->putFileAs('images/schedule', $image, $imgName);
        }

        foreach ($request->day as $day) {
            // Usar la función hayConflictoHorario para la creación (sin emissionKey)
            if ($this->hayConflictoHorario($request->start, $request->end, $day)) {
                return back()->withErrors(['day' => 'Ya existe un programa en ese horario para el día ' . $this->traducirDia($day) . '.'])->withInput();
            }

            Schedule::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'start' => $request->start,
                'end' => $request->end,
                'duration' => $start->diffInMinutes($end),
                'host' => $request->host,
                'about' => $request->about,
                'day' => $day,
                'image' => $imgName,
                'emission_key' => $emissionKey,
            ]);
        }

        return redirect('/show-schedule')->with('success', 'Programa agregado correctamente.');
    }

    public function show(ScheduleDataTable $schedule)
    {
        return $schedule->render('admin.schedule.show-schedule');
    }

    /**
     * Procesar la solicitud de actualización del recurso especificado.
     * Este es tu actual método 'edit' que procesa el formulario.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request) // Tu método actual para procesar la actualización
    {
        $this->validate($request, [
            'name' => 'required',
            'start' => 'required|date_format:H:i',
            'end' => 'required|date_format:H:i|after:start',
            'host' => 'required',
            'day' => 'required|array|min:1|max:7',
            'about' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $original = Schedule::findOrFail($id);
        // Usamos la emission_key existente o generamos una nueva si por alguna razón no existiera
        $emissionKey = $original->emission_key ?? uniqid();

        $start = Carbon::createFromFormat('H:i', $request->start);
        $end = Carbon::createFromFormat('H:i', $request->end);

        // --- Lógica de Validación de Solapamiento para Edición ---
        // Itera sobre los DÍAS PROPUESTOS por el usuario en el formulario.
        foreach ($request->day as $day) {
            // Llama a hayConflictoHorario, pasando la emissionKey del bloque actual.
            // Esto le dice a la función que ignore cualquier programa con esta misma clave
            // (es decir, el programa que estamos editando), evitando que se solape consigo mismo.
            if ($this->hayConflictoHorario($request->start, $request->end, $day, $emissionKey)) {
                return back()->withErrors([
                    'day' => "Ya existe un programa en ese horario el {$this->traducirDia($day)}."
                ])->withInput(); // ¡Importante para mantener los datos del formulario!
            }
        }
        // --- Fin Lógica de Validación ---

        // Si la validación de solapamiento pasa:
        // 1. Borrar todos los registros ANTERIORES del bloque (soft delete)
        // Esto elimina las entradas existentes para este emission_key antes de recrearlas.
        Schedule::where('emission_key', $emissionKey)->delete();

        // 2. Procesar imagen
        $imgName = $original->image; // Mantener la imagen original por defecto
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Eliminar imagen antigua si no es la genérica y existe
            if ($imgName != 'genericprogramimage.png' && Storage::disk('public')->exists('images/schedule/' . $imgName)) {
                Storage::disk('public')->delete('images/schedule/' . $imgName);
            }
            $image = $request->file('image');
            $imgName = uniqid() . '.' . $image->getClientOriginalExtension();
            // Usar Storage Facade para mayor control y guardar la nueva imagen
            Storage::disk('public')->putFileAs('images/schedule', $image, $imgName);
        }

        // 3. Crear los nuevos registros por día con la información actualizada
        foreach ($request->day as $day) {
            Schedule::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'start' => $request->start,
                'end' => $request->end,
                'duration' => $start->diffInMinutes($end),
                'host' => $request->host,
                'about' => $request->about,
                'day' => $day,
                'image' => $imgName,
                'emission_key' => $emissionKey, // Asegura que la clave de emisión sea la misma
            ]);
        }

        return redirect('/show-schedule')->with('success', 'Programa actualizado correctamente.');
    }

    /**
     * Mostrar el formulario para editar el recurso especificado.
     * Este es tu actual método 'update' que muestra el formulario.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id) // Tu método actual para mostrar el formulario de edición
    {
        $schedule = Schedule::findOrFail($id);

        // Importante: obtener todos los días asociados a la misma emission_key
        // para marcar correctamente los checkboxes en el formulario.
        $daysSelected = Schedule::where('emission_key', $schedule->emission_key)
                                ->pluck('day')
                                ->toArray();

        // Asegúrate de que la variable que pasas a la vista coincida con lo que tu Blade espera,
        // que según tu formulario es '$tableM'.
        return view('admin.update-schedule', [
            'tableM' => $schedule,
            'daysSelected' => $daysSelected
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $program = Schedule::findOrFail($id);
        Schedule::where('emission_key', $program->emission_key)->delete();

        // Opcional: Eliminar la imagen física si no es la genérica y existe
        // if ($program->image != 'genericprogramimage.png' && Storage::disk('public')->exists('images/schedule/' . $program->image)) {
        //     Storage::disk('public')->delete('images/schedule/' . $program->image);
        // }

        return redirect()->back()->with('success', 'El bloque del programa ha sido eliminado definitivamente.');
    }


    public function activate($id)
    {
        $program = Schedule::findOrFail($id);
        Schedule::where('emission_key', $program->emission_key)
            ->update(['deleted_at' => null]);

        return redirect()->back()->with('success', 'El bloque del programa ha sido activado.');
    }



    public function delete($id)
    {
        $program = Schedule::findOrFail($id);
        Schedule::where('emission_key', $program->emission_key)
            ->update(['deleted_at' => now()]);

        return redirect()->back()->with('success', 'El bloque del programa ha sido desactivado.');
    }


    public function view($id)
    {
        $schedule = Schedule::findorFail($id);
        return view('admin.schedule.view-schedule', compact('schedule'));
    }
}