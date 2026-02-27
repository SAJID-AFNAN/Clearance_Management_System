<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClearanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'request_no',
        'status',
        'submitted_at',
        'completed_at',
        'principal_approved_at',
        'final_pdf_path',
        'qr_code',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'completed_at' => 'datetime',
        'principal_approved_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }
}
