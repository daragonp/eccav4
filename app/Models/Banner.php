<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    //
    protected $table = 'carousel_images';
    protected $fillable = ['image_left', 'image_right', 'active'];

    protected $casts = [
        'active' => 'boolean',
    ];
}
