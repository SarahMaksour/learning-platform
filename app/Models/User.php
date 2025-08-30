<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use App\Models\Review;
use App\Models\Enrolment;
use App\Models\UserDetail;
use App\Models\Certificate;
use App\Models\PlacementAttempt;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function studentLessonProgress()
{
    return $this->hasMany(StudentLessonProgress::class, 'user_id');
}

public function courses()
{
    return $this->hasMany(Course::class);
}
 public function scopeSearchFullName($query, $name)
    {
        return $query->whereRaw("CONCAT(name) LIKE ?", ['%' . $name . '%']);
    }
public function scopeSearchEmail($query, $email)
    {
        return $query->where('email', 'like', '%' . $email . '%');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrolment::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
    public function walled()
    {
        return $this->hasOne(Wallet::class);
    }
    public function placementAttempts()
    {
        return $this->hasMany(PlacementAttempt::class);
    }
    public function UserDetail()
{
    return $this->hasOne(UserDetail::class);
}
public function discussions()
{
    return $this->hasMany(Discussion::class);
}

 
}
