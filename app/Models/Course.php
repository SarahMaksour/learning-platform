<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'level',
        'is_free',
        'is_popular',
        'price',
        'description',
    ];
    public function enrollments()
    {
        return $this->hasMany(Enrolment::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
    public function contents()
    {
        return $this->hasMany(CourseContent::class);
    }
    // العلاقة مع الكورس السابق
    public function previousCourse()
    {
        return $this->belongsTo(Course::class, 'previous_course_id');
    }

    // العلاقة مع الكورس التالي
    public function nextCourse()
    {
        return $this->belongsTo(Course::class, 'next_course_id');
    }
    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }
    public function placementAttempts()
    {
        return $this->hasMany(PlacementAttempt::class);
    }
}
