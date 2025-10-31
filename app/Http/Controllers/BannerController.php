<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\DataTables\BannerDataTable;

class BannerController extends Controller
{
    /**
     * Muestra la lista de banners usando DataTable
     */
    public function index(BannerDataTable $dataTable)
    {
        return $dataTable->render('admin.slider.show-slider');
    }

    /**
     * Alias del método index para compatibilidad con rutas existentes
     */
    public function show(BannerDataTable $dataTable)
    {
        return $this->index($dataTable);
    }

    /**
     * Muestra un banner específico
     */
    public function view($id)
    {
        $slider = Banner::findOrFail($id);
        return view('admin.slider.view-slider', compact('slider'));
    }

    /**
     * Crea un nuevo banner
     */
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
            $imageNameLeft = uniqid('slider_left_') . '.' . $imageLeft->getClientOriginalExtension();
            
            // Asegurar que existe el directorio
            if (!file_exists(public_path('images/slider'))) {
                mkdir(public_path('images/slider'), 0775, true);
            }
            
            // Guardar en public/images/slider
            $imageLeft->move(public_path('images/slider'), $imageNameLeft);
            $slider->image_left = $imageNameLeft;
        }

        // Procesar imagen derecha
        if ($request->hasFile('image_right')) {
            $imageRight = $request->file('image_right');
            $imageNameRight = uniqid('slider_right_') . '.' . $imageRight->getClientOriginalExtension();
            
            // Asegurar que existe el directorio
            if (!file_exists(public_path('images/slider'))) {
                mkdir(public_path('images/slider'), 0775, true);
            }
            
            // Guardar en public/images/slider
            $imageRight->move(public_path('images/slider'), $imageNameRight);
            $slider->image_right = $imageNameRight;
        }

        $slider->active = false; // Por defecto inactivo hasta que se active manualmente
        $slider->save();

        return redirect()->back()->with('success', 'El carrusel ha sido creado correctamente');
    }

    /**
     * Prepara la vista de edición
     */
    public function update($id)
    {
        $slider = Banner::findOrFail($id);
        return view('admin.slider.editmodal', compact('slider'));
    }

    /**
     * Actualiza un banner existente
     */
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
            if ($slider->image_left && file_exists(public_path('images/slider/' . $slider->image_left))) {
                unlink(public_path('images/slider/' . $slider->image_left));
            }

            $imageLeft = $request->file('image_left');
            $imageNameLeft = uniqid('slider_left_') . '.' . $imageLeft->getClientOriginalExtension();
            
            // Asegurar que existe el directorio
            if (!file_exists(public_path('images/slider'))) {
                mkdir(public_path('images/slider'), 0775, true);
            }
            
            // Guardar la nueva imagen
            $imageLeft->move(public_path('images/slider'), $imageNameLeft);
            $slider->image_left = $imageNameLeft;
        }

        // Actualizar imagen derecha si se proporciona una nueva
        if ($request->hasFile('image_right')) {
            // Eliminar imagen anterior si existe
            if ($slider->image_right && file_exists(public_path('images/slider/' . $slider->image_right))) {
                unlink(public_path('images/slider/' . $slider->image_right));
            }

            $imageRight = $request->file('image_right');
            $imageNameRight = uniqid('slider_right_') . '.' . $imageRight->getClientOriginalExtension();
            
            // Asegurar que existe el directorio
            if (!file_exists(public_path('images/slider'))) {
                mkdir(public_path('images/slider'), 0775, true);
            }
            
            // Guardar la nueva imagen
            $imageRight->move(public_path('images/slider'), $imageNameRight);
            $slider->image_right = $imageNameRight;
        }

        $slider->updated_at = Carbon::now();
        $slider->save();

        return redirect()->back()->with('success', 'El carrusel ha sido actualizado correctamente');
    }

    /**
     * Desactiva un banner (soft delete)
     */
    public function destroy($id)
    {
        $slider = Banner::findOrFail($id);
        $slider->active = false;
        $slider->save();

        return redirect()->back()->with('success', 'El carrusel ha sido desactivado');
    }

    /**
     * Activa un banner
     */
    public function activate($id)
    {
        $slider = Banner::findOrFail($id);
        $slider->active = true;
        $slider->save();

        return redirect()->back()->with('success', 'El carrusel ha sido activado');
    }

    /**
     * Elimina permanentemente un banner y sus imágenes
     */
    public function delete($id)
    {
        $slider = Banner::findOrFail($id);

        // Eliminar imágenes asociadas del directorio public
        if ($slider->image_left && file_exists(public_path('images/slider/' . $slider->image_left))) {
            unlink(public_path('images/slider/' . $slider->image_left));
        }

        if ($slider->image_right && file_exists(public_path('images/slider/' . $slider->image_right))) {
            unlink(public_path('images/slider/' . $slider->image_right));
        }

        $slider->delete();

        return redirect()->back()->with('success', 'El carrusel ha sido eliminado definitivamente');
    }
}
