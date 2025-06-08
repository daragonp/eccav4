<?php

namespace App\Http\Controllers;

use App\DataTables\BannerDataTable;
use App\Models\Banner;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;


class BannerController extends Controller
{
    //

    public function show(BannerDataTable $dataTable)
    {
        //
        return $dataTable->render('admin.slider.show-slider');
    }

    public function view($id)
    {
        //
        $slider = Banner::findorFail($id);

        return view('admin.slider.view-slider', compact('slider'));
    }

    /*public function store(Request $request)
    {
        $request->validate([
            'izqimage' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            'derimage' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
        ], [
            'izqimage.required' => 'Debe seleccionar una imagen para el lado izquierdo.',
            'derimage.required' => 'Debe seleccionar una imagen para el lado derecho.',
        ]);

        $slide = new Banner();

        // Guardar imágenes en storage/app/public/slider

        $imageizq = $request->izqimage;
        $imageder = $request->derimage;

        if ($imageizq) {

            $imgName = time() . '.' . $imageizq->getClientOriginalExtension();

            $request->izqimage->move('images/slider', $imgName);


            $slide->izqimage = $imgName;
        }

        $imgIzq = $request->file('izqimage')->store('slider', 'public');
        $imgDer = $request->file('derimage')->store('slider', 'public');

        $slide->image_left = $imgIzq;
        $slide->image_right = $imgDer;
        $slide->active = 0;

        $slide->save();

        return redirect()->back()->with('success', 'El recurso ha sido creado');
    }*/

    public function store(Request $request)
    {

        $this->validate($request, [
            'izqimage' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            'derimage' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
        ], [
            'izqimage.required' => 'Debe seleccionar una imagen para el lado izquierdo.',
            'derimage.required' => 'Debe seleccionar una imagen para el lado derecho.',
        ]);

        $slide = new Banner();

        $imageizq = $request->izqimage;
        $imageder = $request->derimage;

        if ($imageizq) {

            $imgIzq = uniqid('izq_') . '.' . $imageizq->getClientOriginalExtension();


            $request->izqimage->move('images/slider', $imgIzq);


            $slide->image_left = $imgIzq;
        }

        if ($imageder) {

            $imgDer = uniqid('der_') . '.' . $imageder->getClientOriginalExtension();

            $request->derimage->move('images/slider', $imgDer);

            $slide->image_right = $imgDer;
        }


        $slide->active = 0;

        //dd($slide);
        $slide->save();

        return redirect()->back()->with('success', 'El recurso ha sido creado');
    }


    public function update($id)
    {
        $slider = Banner::findOrFail($id);
        return view('admin.slider.update-slider', compact('slider'));
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'image_left' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            'image_right' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
        ]);

        $slider = Banner::findOrFail($id);

        // Imagen izquierda
        $imageizq = $request->izqimage;
        $imageder = $request->derimage;

        if ($imageizq) {

            $imgIzq = uniqid('izq_') . '.' . $imageizq->getClientOriginalExtension();


            $request->izqimage->move('images/slider', $imgIzq);


            $slider->image_left = $imgIzq;
        }

        if ($imageder) {

            $imgDer = uniqid('der_') . '.' . $imageder->getClientOriginalExtension();

            $request->derimage->move('images/slider', $imgDer);

            $slider->image_right = $imgDer;
        }

        $slider->updated_at = Carbon::now();
        $slider->save();

        return redirect()->back()->with('success', 'El banner ha sido actualizado correctamente');
    }

    public function destroy($id)
    {
        //
        $slider = Banner::findorFail($id);

        $slider->active = 0;
        $slider->deleted_at = Carbon::now();

        $slider->save();

        return redirect()->back()->with('success', 'La publicación no está disponible al público');
    }
    public function activate($id)
    {
        //
        $slider = Banner::findorFail($id);

        $slider->active = 1;
        $slider->deleted_at = NULL;

        $slider->save();

        return redirect()->back()->with('success', 'La publicación ha sido activada al público');
    }

    public function delete($id)
    {
        //
        $slider = Banner::findorFail($id);

        $slider->delete();

        return redirect()->back()->with('success', 'La publicación ha sido eliminada definitivamente.');
    }
}
