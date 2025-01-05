<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Schedule;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\ScheduleDataTable;
use Illuminate\Support\Facades\Storage;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'name' => 'required',
            'start' => 'required',
            'end' => 'required',
            'host' => 'required',
            'day' => 'required|array|min:1|max:7', // al menos un día y máximo 7 días
        ], [
            'name.required' => 'El campo nombre del programa es obligatorio.',
            'start.required' => 'El campo hora de inicio es obligatorio.',
            'end.required' => 'El campo hora de finalización es obligatorio.',
            'host.required' => 'El campo director(a) es obligatorio.',
            'day.required' => 'Debe seleccionar al menos un día de emisión',
        ]);
        $selectedDays = $request->input('day');

        foreach ($selectedDays as $selectedDay) {

            $program = new Schedule();

            $program->name = $request->input('name');

            $image = $request->image;

            if ($image) {
                if ($image->isValid()) {
                    $imgName = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                    Storage::put('images/schedule/' . $imgName, file_get_contents($image));
                    $program->image = $imgName;
                } else {
                    // Manejar el error de subida
                    dd($image->getError());
                }
            }

            $slug = Str::slug($request->name);
            $str = preg_replace('/[^a-z0-9]/', '-', $slug);
            $program->slug = $str;
            $program->about = $request->input('about');
            $program->start = $request->input('start');
            $program->end = $request->input('end');

            $inicio = Carbon::createFromFormat('H:i', $request->input('start'));
            $fin = Carbon::createFromFormat('H:i', $request->input('end'));

            $program->host = $request->input('host');
            $program->day = $selectedDay;
            $program->duration =  $inicio->diffInMinutes($fin);
            $program->created_at = Carbon::now();
            $program->updated_at = Carbon::now();

            //dd($program);

            $program->save();
        }
        return redirect('/show-schedule')->with('success', 'El programa ha sido agregado');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(ScheduleDataTable $schedule)
    {
        //
        return $schedule->render('admin.schedule.show-schedule');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        //

        $this->validate($request, [
            'name' => 'required',
            'start' => 'required',
            'end' => 'required',
            'host' => 'required',
            'day' => 'required|array|min:1|max:7', // al menos un día y máximo 7 días
        ], [
            'name.required' => 'El campo nombre del programa es obligatorio.',
            'start.required' => 'El campo hora de inicio es obligatorio.',
            'end.required' => 'El campo hora de finalización es obligatorio.',
            'host.required' => 'El campo director(a) es obligatorio.',
            'day.required' => 'Debe seleccionar al menos un día de emisión',
        ]);
        $selectedDays = $request->input('day');

        foreach ($selectedDays as $selectedDay) {

            $program = Schedule::find($id);

            $program->name = $request->input('name');

            $image = $request->image;

            if ($image) {
                if ($image->isValid()) {
                    $imgName = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                    Storage::put('images/schedule/' . $imgName, file_get_contents($image));
                    $program->image = $imgName;
                } else {
                    // Manejar el error de subida
                    dd($image->getError());
                }
            }

            $slug = Str::slug($request->name);
            $str = preg_replace('/[^a-z0-9]/', '-', $slug);
            $program->slug = $str;
            $program->about = $request->input('about');
            $program->start = $request->input('start');
            $program->end = $request->input('end');
            try {
                $inicio = Carbon::createFromFormat('H:i', $request->input('start'));
                $fin = Carbon::createFromFormat('H:i', $request->input('end'));
                $program->duration =  $inicio->diffInMinutes($fin);

            } catch (\Exception $e) {
                // Manejar la excepción, por ejemplo, imprimir el mensaje de error.
                $e->getMessage();
            }
            $program->host = $request->input('host');
            $program->day = $selectedDay;
            $program->created_at = Carbon::now();
            $program->updated_at = Carbon::now();

            $program->save();
        }
        return redirect('/show-schedule')->with('success', 'El programa ha sido agregado');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        //
        $schedule = Schedule::find($id);

        return view('admin.update-schedule', compact('schedule'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $schedule = Schedule::findorFail($id);

        $schedule->deleted_at = Carbon::now();

        $schedule->save();

        return redirect()->back()->with('mensaje', 'La publicación no está disponible al público');
    }

    public function activate($id)
    {
        //
        $schedule = Schedule::findorFail($id);

        $schedule->deleted_at = NULL;

        $schedule->save();

        return redirect()->back()->with('success', 'La publicación ha sido activada al público');
    }

    public function delete($id)
    {
        //
        $schedule = Schedule::findorFail($id);

        $schedule->delete();

        return redirect()->back()->with('success', 'La publicación ha sido eliminada definitivamente.');
    }
}
