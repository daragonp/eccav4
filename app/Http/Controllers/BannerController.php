<?php

namespace App\Http\Controllers;

use App\DataTables\BannerDataTable;
use App\Models\Banner;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Muestra la lista de banners
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
        // Validación con mensajes personalizados
        $request->validate([
            'image_left' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            'image_right' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
        ], [
            'image_left.required' => 'Debe seleccionar una imagen para el lado izquierdo.',
            'image_left.image' => 'El archivo del lado izquierdo debe ser una imagen.',
            'image_left.mimes' => 'La imagen izquierda debe ser de tipo: jpeg, png, jpg, gif, webp.',
            'image_left.max' => 'La imagen izquierda no debe superar los 20MB.',
            'image_right.required' => 'Debe seleccionar una imagen para el lado derecho.',
            'image_right.image' => 'El archivo del lado derecho debe ser una imagen.',
            'image_right.mimes' => 'La imagen derecha debe ser de tipo: jpeg, png, jpg, gif, webp.',
            'image_right.max' => 'La imagen derecha no debe superar los 20MB.',
        ]);

        try {
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

            return redirect()->back()->with('success', 'El carrusel ha sido creado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Error al crear el carrusel: ' . $e->getMessage()])
                ->withInput();
        }
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
        ], [
            'image_left.image' => 'El archivo del lado izquierdo debe ser una imagen.',
            'image_left.mimes' => 'La imagen izquierda debe ser de tipo: jpeg, png, jpg, gif, webp.',
            'image_left.max' => 'La imagen izquierda no debe superar los 20MB.',
            'image_right.image' => 'El archivo del lado derecho debe ser una imagen.',
            'image_right.mimes' => 'La imagen derecha debe ser de tipo: jpeg, png, jpg, gif, webp.',
            'image_right.max' => 'La imagen derecha no debe superar los 20MB.',
        ]);

        try {
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

            return redirect()->back()->with('success', 'El carrusel ha sido actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Error al actualizar el carrusel: ' . $e->getMessage()]);
        }
    }

    /**
     * Desactiva un banner (soft delete)
     */
    public function destroy($id)
    {
        $slider = Banner::findOrFail($id);
        $slider->active = false;
        $slider->save();

        return redirect()->back()->with('success', 'El carrusel ha sido desactivado.');
    }

    /**
     * Activa un banner
     */
    public function activate($id)
    {
        $slider = Banner::findOrFail($id);
        $slider->active = true;
        $slider->save();

        return redirect()->back()->with('success', 'El carrusel ha sido activado.');
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

        return redirect()->back()->with('success', 'El carrusel ha sido eliminado definitivamente.');
    }
}
