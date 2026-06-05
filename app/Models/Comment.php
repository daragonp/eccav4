<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'blogpostid',
        'useremail',
        'username',
        'description',
    ];

    public function news()
    {
        return $this->belongsTo(News::class, 'blogpostid');
    }
}
