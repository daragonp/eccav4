<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibraryCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug', 'description'];

    /**
     * Recursos que pertenecen a esta categoría.
     */
    public function resources()
    {
        return $this->hasMany(LibraryResource::class, 'category_id');
    }
}
