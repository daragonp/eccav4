<?php

namespace App\Http\Controllers;

use App\DataTables\BannerDataTable;
use App\Models\Banner;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function show(BannerDataTable $dataTable)
    {
        return $dataTable->render('admin.slider.show-slider');
    }

    public function view($id)
    {
        $slider = Banner::findOrFail($id);
        return view('admin.slider.view-slider', compact('slider'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image_left' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            'image_right' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
        ], [
            'image_left.required' => 'Debe seleccionar una imagen para el lado izquierdo.',
            'image_right.required' => 'Debe seleccionar una imagen para el lado derecho.',
        ]);

        $slider = new Banner();

        // Procesar imagen izquierda
        if ($request->hasFile('image_left')) {
            $imageLeft = $request->file('image_left');
            $imageNameLeft = uniqid('slider_') . '.' . $imageLeft->getClientOriginalExtension();
            
            // Guardar en storage/app/public/images/slider
            $imageLeft->storeAs('images/slider/' . $imageNameLeft, 'public');
            $slider->image_left = $imageNameLeft;
        }

        // Procesar imagen derecha
        if ($request->hasFile('image_right')) {
            $imageRight = $request->file('image_right');
            $imageNameRight = uniqid('slider_') . '.' . $imageRight->getClientOriginalExtension();
            
            // Guardar en storage/app/public/images/slider
            $imageRight->storeAs('images/slider/' . $imageNameRight, 'public');
            $slider->image_right = $imageNameRight;
        }

        $slider->active = false; // Por defecto inactivo hasta que se active manualmente
        $slider->save();

        return redirect()->back()->with('success', 'El carrusel ha sido creado correctamente');
    }

    public function update($id)
    {
        $slider = Banner::findOrFail($id);
        return view('admin.slider.editmodal', compact('slider'));
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'image_left' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            'image_right' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
        ]);

        $slider = Banner::findOrFail($id);

        // Actualizar imagen izquierda si se proporciona una nueva
        if ($request->hasFile('image_left')) {
            // Eliminar imagen anterior si existe
            if ($slider->image_left && Storage::disk('public')->exists('images/slider/' . $slider->image_left)) {
                Storage::disk('public')->delete('images/slider/' . $slider->image_left);
            }
            
            $imageLeft = $request->file('image_left');
            $imageNameLeft = uniqid('slider_') . '.' . $imageLeft->getClientOriginalExtension();
            
            // Guardar la nueva imagen
            $imageLeft->storeAs('images/slider/' . $imageNameLeft, 'public');
            $slider->image_left = $imageNameLeft;
        }

        // Actualizar imagen derecha si se proporciona una nueva
        if ($request->hasFile('image_right')) {
            // Eliminar imagen anterior si existe
            if ($slider->image_right && Storage::disk('public')->exists('images/slider/' . $slider->image_right)) {
                Storage::disk('public')->delete('images/slider/' . $slider->image_right);
            }
            
            $imageRight = $request->file('image_right');
            $imageNameRight = uniqid('slider_') . '.' . $imageRight->getClientOriginalExtension();
            
            // Guardar la nueva imagen
            $imageRight->storeAs('images/slider/' . $imageNameRight, 'public');
            $slider->image_right = $imageNameRight;
        }

        $slider->updated_at = Carbon::now();
        $slider->save();

        return redirect()->back()->with('success', 'El carrusel ha sido actualizado correctamente');
    }

    public function destroy($id)
    {
        $slider = Banner::findOrFail($id);
        $slider->active = false;
        $slider->save();

        return redirect()->back()->with('success', 'El carrusel ha sido desactivado');
    }

    public function activate($id)
    {
        $slider = Banner::findOrFail($id);
        $slider->active = true;
        $slider->save();

        return redirect()->back()->with('success', 'El carrusel ha sido activado');
    }

    public function delete($id)
    {
        $slider = Banner::findOrFail($id);
        
        // Eliminar imágenes asociadas
        if ($slider->image_left && Storage::disk('public')->exists('images/slider/' . $slider->image_left)) {
            Storage::disk('public')->delete('images/slider/' . $slider->image_left);
        }
        
        if ($slider->image_right && Storage::disk('public')->exists('images/slider/' . $slider->image_right)) {
            Storage::disk('public')->delete('images/slider/' . $slider->image_right);
        }
        
        $slider->delete();

        return redirect()->back()->with('success', 'El carrusel ha sido eliminado definitivamente');
    }
}