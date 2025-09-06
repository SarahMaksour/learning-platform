<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
     protected $fillable = ['course_id','title', 'video_path', 'duration','description'];

protected $hidden = ['courseContent'];
    public function courseContent()
    {
        return $this->morphOne(CourseContent::class, 'contentable');
    }
public function course()
{
    return $this->belongsTo(Course::class);
}
public function quiz()
{
    return $this->hasOneThrough(
        Quiz::class,          // الموديل النهائي
        CourseContent::class, // الموديل الوسيط
        'contentable_id',     // المفتاح في CourseContent اللي بيربط الفيديو
        'content_id',         // المفتاح في Quiz اللي بيربط CourseContent
        'id',                 // المفتاح المحلي في Video
        'id'                  // المفتاح المحلي في CourseContent
    )->where('contentable_type', self::class);
}
public function isPassedByUser($user)
{
    return $this->courseContent
        ->studentProgress()
        ->where('user_id', $user->id)
        ->where('is_passed', true)
        ->exists();
}

}
