<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quizze extends Model
{
    use HasFactory;
    protected $fillable=[
        'content_id',
        'course_id',
        'title',
        'type',
        'total_point',
        'created_at',

    ];
}
