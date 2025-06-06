<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Text extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'content'];

    public function courseContent()
    {
        return $this->morphOne(CourseContent::class, 'contentable');
    }
}
