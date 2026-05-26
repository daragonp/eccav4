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

        // Validación condicional según tipo seleccionado (image | video | youtube)
        $leftType = $request->input('left_type', 'image');
        $rightType = $request->input('right_type', 'image');

        $rules = [];
        $messages = [];

        if ($leftType === 'image') {
            $rules['image_left'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:20480';
            $messages['image_left.required'] = 'Debe seleccionar una imagen para el lado izquierdo.';
        } elseif ($leftType === 'video') {
            $rules['video_left'] = 'required|mimes:mp4,webm,mov,ogg|max:51200';
            $messages['video_left.required'] = 'Debe seleccionar un video para el lado izquierdo.';
        } else {
            $rules['youtube_left'] = 'required|url';
            $messages['youtube_left.required'] = 'Debe ingresar un enlace YouTube para el lado izquierdo.';
        }

        if ($rightType === 'image') {
            $rules['image_right'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:20480';
            $messages['image_right.required'] = 'Debe seleccionar una imagen para el lado derecho.';
        } elseif ($rightType === 'video') {
            $rules['video_right'] = 'required|mimes:mp4,webm,mov,ogg|max:51200';
            $messages['video_right.required'] = 'Debe seleccionar un video para el lado derecho.';
        } else {
            $rules['youtube_right'] = 'required|url';
            $messages['youtube_right.required'] = 'Debe ingresar un enlace YouTube para el lado derecho.';
        }

        $request->validate($rules, $messages);

        try {
            $slider = new Banner();

            // Procesar lado izquierdo según tipo
            if ($leftType === 'image' && $request->hasFile('image_left')) {
                $file = $request->file('image_left');
                $name = uniqid('slider_left_') . '.' . $file->getClientOriginalExtension();
                if (!file_exists(public_path('images/slider'))) {
                    mkdir(public_path('images/slider'), 0775, true);
                }
                $file->move(public_path('images/slider'), $name);
                $slider->image_left = $name;
            } elseif ($leftType === 'video' && $request->hasFile('video_left')) {
                $file = $request->file('video_left');
                $name = uniqid('slider_left_vid_') . '.' . $file->getClientOriginalExtension();
                if (!file_exists(public_path('images/slider'))) {
                    mkdir(public_path('images/slider'), 0775, true);
                }
                $file->move(public_path('images/slider'), $name);
                $slider->image_left = $name;
            } elseif ($leftType === 'youtube') {
                $slider->image_left = $request->input('youtube_left');
            }

            // Procesar lado derecho según tipo
            if ($rightType === 'image' && $request->hasFile('image_right')) {
                $file = $request->file('image_right');
                $name = uniqid('slider_right_') . '.' . $file->getClientOriginalExtension();
                if (!file_exists(public_path('images/slider'))) {
                    mkdir(public_path('images/slider'), 0775, true);
                }
                $file->move(public_path('images/slider'), $name);
                $slider->image_right = $name;
            } elseif ($rightType === 'video' && $request->hasFile('video_right')) {
                $file = $request->file('video_right');
                $name = uniqid('slider_right_vid_') . '.' . $file->getClientOriginalExtension();
                if (!file_exists(public_path('images/slider'))) {
                    mkdir(public_path('images/slider'), 0775, true);
                }
                $file->move(public_path('images/slider'), $name);
                $slider->image_right = $name;
            } elseif ($rightType === 'youtube') {
                $slider->image_right = $request->input('youtube_right');
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
        // Validación condicional similar a store pero con campos opcionales
        $leftType = $request->input('left_type', 'image');
        $rightType = $request->input('right_type', 'image');

        $rules = [];
        $messages = [];

        if ($leftType === 'image') {
            $rules['image_left'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480';
            $messages['image_left.image'] = 'El archivo del lado izquierdo debe ser una imagen.';
        } elseif ($leftType === 'video') {
            $rules['video_left'] = 'nullable|mimes:mp4,webm,mov,ogg|max:51200';
            $messages['video_left.mimes'] = 'El video izquierdo debe ser mp4, webm, mov u ogg.';
        } else {
            $rules['youtube_left'] = 'nullable|url';
        }

        if ($rightType === 'image') {
            $rules['image_right'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480';
            $messages['image_right.image'] = 'El archivo del lado derecho debe ser una imagen.';
        } elseif ($rightType === 'video') {
            $rules['video_right'] = 'nullable|mimes:mp4,webm,mov,ogg|max:51200';
            $messages['video_right.mimes'] = 'El video derecho debe ser mp4, webm, mov u ogg.';
        } else {
            $rules['youtube_right'] = 'nullable|url';
        }

        $request->validate($rules, $messages);

        try {
            $slider = Banner::findOrFail($id);

            // Actualizar lado izquierdo
            if ($leftType === 'image' && $request->hasFile('image_left')) {
                if ($slider->image_left && file_exists(public_path('images/slider/' . $slider->image_left))) {
                    unlink(public_path('images/slider/' . $slider->image_left));
                }
                $file = $request->file('image_left');
                $name = uniqid('slider_left_') . '.' . $file->getClientOriginalExtension();
                if (!file_exists(public_path('images/slider'))) { mkdir(public_path('images/slider'), 0775, true); }
                $file->move(public_path('images/slider'), $name);
                $slider->image_left = $name;
            } elseif ($leftType === 'video' && $request->hasFile('video_left')) {
                if ($slider->image_left && file_exists(public_path('images/slider/' . $slider->image_left))) {
                    unlink(public_path('images/slider/' . $slider->image_left));
                }
                $file = $request->file('video_left');
                $name = uniqid('slider_left_vid_') . '.' . $file->getClientOriginalExtension();
                if (!file_exists(public_path('images/slider'))) { mkdir(public_path('images/slider'), 0775, true); }
                $file->move(public_path('images/slider'), $name);
                $slider->image_left = $name;
            } elseif ($leftType === 'youtube' && $request->filled('youtube_left')) {
                $slider->image_left = $request->input('youtube_left');
            }

            // Actualizar lado derecho
            if ($rightType === 'image' && $request->hasFile('image_right')) {
                if ($slider->image_right && file_exists(public_path('images/slider/' . $slider->image_right))) {
                    unlink(public_path('images/slider/' . $slider->image_right));
                }
                $file = $request->file('image_right');
                $name = uniqid('slider_right_') . '.' . $file->getClientOriginalExtension();
                if (!file_exists(public_path('images/slider'))) { mkdir(public_path('images/slider'), 0775, true); }
                $file->move(public_path('images/slider'), $name);
                $slider->image_right = $name;
            } elseif ($rightType === 'video' && $request->hasFile('video_right')) {
                if ($slider->image_right && file_exists(public_path('images/slider/' . $slider->image_right))) {
                    unlink(public_path('images/slider/' . $slider->image_right));
                }
                $file = $request->file('video_right');
                $name = uniqid('slider_right_vid_') . '.' . $file->getClientOriginalExtension();
                if (!file_exists(public_path('images/slider'))) { mkdir(public_path('images/slider'), 0775, true); }
                $file->move(public_path('images/slider'), $name);
                $slider->image_right = $name;
            } elseif ($rightType === 'youtube' && $request->filled('youtube_right')) {
                $slider->image_right = $request->input('youtube_right');
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
