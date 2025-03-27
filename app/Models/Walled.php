<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Walled extends Model
{
    use HasFactory;
    protected $fillable =[
        'user_id',
        'balance',
        'created_at',
        'updated_at'
    ];
}
