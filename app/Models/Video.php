<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
     protected $fillable = ['title', 'video_path', 'duration','description'];

protected $hidden = ['courseContent'];

    public function courseContent()
    {
        return $this->morphOne(CourseContent::class, 'contentable');
    }
public function course()
{
    return $this->belongsTo(Course::class);
}

}
