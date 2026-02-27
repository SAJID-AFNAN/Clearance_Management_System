<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'capacity'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}