<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLessonProgress extends Model
{
    use HasFactory;
     protected $fillable = ['user_id', 'content_id', 'is_passed', 'score'];

    public function lesson()
    {
        return $this->belongsTo(CourseContent::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
