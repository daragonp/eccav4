<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Podcast extends Model
{
    protected $fillable = [
        'title',
        'description',
        'audio_file',
        'category_id'
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
