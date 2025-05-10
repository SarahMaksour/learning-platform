<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
        'user_id',
        'rating',
        'comment',
    ];
public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}


