<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    use HasFactory;
    protected $fillable = [
      'content_id',
        'user_id',
        'parent_id',
        'message'
    ];
    public function user()
{
    return $this->belongsTo(User::class);
}
public function replies()
{
    return $this->hasMany(self::class, 'parent_id');
}
public function parent()
{
    return $this->belongsTo(self::class, 'parent_id');
}
public function content()
{
    return $this->belongsTo(CourseContent::class, 'content_id');
}

}
