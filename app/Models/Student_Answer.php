<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student_Answer extends Model
{
    use HasFactory;
    protected $fillable =[
      'question_id',
      'user_id' ,
      'student_answer',
      'is_correct' ,
    ];
}
