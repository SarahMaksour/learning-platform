<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pdf extends Model
{
    use HasFactory;
protected $fillable = ['title', 'pdf_path'];

    public function courseContent()
    {
        return $this->morphOne(CourseContent::class, 'contentable');
    }

}
