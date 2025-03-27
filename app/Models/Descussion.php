<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Descussion extends Model
{
    use HasFactory;
    protected $fillable = [
      'content_id',
        'user_id',
        'message'
    ];
}
