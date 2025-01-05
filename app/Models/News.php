<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'slug',
        'abstract',
        'audio',
        'pdfdoc',
        'autor',
        'image'
    ];

        public function getNewstextContentAttribute(){
            return \Illuminate\Support\Str::words(html_entity_decode(strip_tags($this->text)), 400);
            }

        public function getNewsabstractContentAttribute(){
            return \Illuminate\Support\Str::words(html_entity_decode(strip_tags($this->abstract)), 200);
            }

        public function newscategory()
        {
            return $this->hasMany(BlogCategory::class);
        }

        public function newscomments()
        {
            return $this->hasMany(Comment::class);
        }

}
