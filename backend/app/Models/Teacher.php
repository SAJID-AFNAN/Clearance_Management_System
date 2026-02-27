<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'department_id',
        'designation',
        'phone',
        'signature_path',
        'is_hall_warden',
        'is_librarian',
        'is_lab_incharge',
    ];

    protected $casts = [
        'is_hall_warden' => 'boolean',
        'is_librarian' => 'boolean',
        'is_lab_incharge' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}