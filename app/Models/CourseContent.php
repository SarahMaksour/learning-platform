<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseContent extends Model
{
    use HasFactory;
 protected $fillable = [

   'course_id',
    'contentable_type',
    'contentable_id',
    ];
    public function discussions()
{
    return $this->hasMany(Discussion::class, 'content_id');
}
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
      public function contentable(){
        return $this->morphTo();
    }
    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }
    public function isPassedByUser($user)
{
    return $this->studentProgress()
        ->where('user_id', $user->id)
        ->where('is_passed', true)
        ->exists();
}

public function studentProgress()
{
    return $this->hasMany(StudentLessonProgress::class);
}

}
