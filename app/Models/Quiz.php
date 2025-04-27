<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    public function content()
    {
        return $this->belongsTo(CourseContent::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function placementAttempts()
    {
        return $this->hasMany(PlacementAttempt::class);
    }
}
