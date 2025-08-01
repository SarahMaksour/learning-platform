<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
       'quiz_id',
       'text',
       'option',
    
       'correct_answer',
    ];
    
    protected $casts = [
        'option' => 'array',
    ];
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class);
    }

}
