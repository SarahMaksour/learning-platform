<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course_Unlock extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
       'course_id',
        'reson'
    ];
}
