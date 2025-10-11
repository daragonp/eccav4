<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'carousel_images';
    protected $fillable = ['image_left', 'image_right', 'active'];
    
    protected $casts = [
        'active' => 'boolean',
    ];
    
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    
    /**
     * Obtiene la URL de la imagen izquierda
     */
    public function getImageLeftUrlAttribute()
    {
        return $this->image_left ? asset('images/slider/' . $this->image_left) : null;
    }
    
    /**
     * Obtiene la URL de la imagen derecha
     */
    public function getImageRightUrlAttribute()
    {
        return $this->image_right ? asset('images/slider/' . $this->image_right) : null;
    }
    
    /**
     * Obtiene el estado formateado
     */
    public function getStatusFormattedAttribute()
    {
        return $this->active ? 'Activo' : 'Inactivo';
    }
}