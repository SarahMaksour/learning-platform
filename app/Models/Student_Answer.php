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
public function question()
    {
        return $this->belongsTo(Question::class);
    }
    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}


