<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Course extends Model
{
    use HasFactory,Searchable;
    protected $fillable = [
        'user_id',
        'title',
        'level',
        'is_free',
        'is_popular',
        'price',
        'description',
    ];
  protected static function booted()
    {
        static::saved(function ($course) {
            $course->searchable();  // يضيف أو يحدث الفهرس للكورس اللي اتخزن
        });

        static::deleted(function ($course) {
            $course->unsearchable(); // يحذف من الفهرس لو اتشال الكورس
        });
    }
public function searchableAs()
{
    return 'courses';
}
 public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
           
        ];
    }

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
public function instructor()
{
    return $this->belongsTo(User::class, 'user_id');
} 
public function parent(){
    return $this->belongsTo(Course::class,'parent_course_id');
}
public function children(){
    return $this->hasMany(Course::class,'parent_course_id');
}
public function videos()
{
    return $this->hasMany(Video::class);
}
public function getTotalVideoDurationFormattedAttribute()
{
    $minutes = $this->contents
        ->where('contentable_type', \App\Models\Video::class)
        ->sum(fn($content) => $content->contentable->duration ?? 0);

    $hours = floor($minutes / 60);
    $mins = $minutes % 60;

    if ($hours && $mins) {
        return "{$hours}h {$mins}m";
    } elseif ($hours) {
        return "{$hours}h";
    } else {
        return "{$mins}m";
    }
}


}

