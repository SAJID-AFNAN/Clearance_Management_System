<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'clearance_request_id',
        'authority_type',
        'authority_id',
        'status',
        'comments',
        'signature_path',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function clearanceRequest()
    {
        return $this->belongsTo(ClearanceRequest::class);
    }

    public function authority()
    {
        return $this->belongsTo(Teacher::class, 'authority_id');
    }
}
