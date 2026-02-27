<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'registration_no',
        'session',
        'department_id',
        'hall_id',
        'phone',
        'photo_path',
        'signature_path',
        'profile_completed',
    ];

    protected $casts = [
        'profile_completed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    public function clearanceRequests()
    {
        return $this->hasMany(ClearanceRequest::class);
    }
}