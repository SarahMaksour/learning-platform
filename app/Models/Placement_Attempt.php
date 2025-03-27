<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Placement_Attempt extends Model
{
    use HasFactory;
    protected $fillable=[
      'user_id',
      'course_id',
      'quize_id',
      'score',
      'status',
    ];
}
