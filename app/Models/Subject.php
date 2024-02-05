<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'min_mark',
    ];

    public function students() 
    {
        return $this->hasMany(Mark::class, "student_id");
    }
    public function users()
    {
        return $this->hasManyThrough(User::class, Mark::class, "subject_id", "id", "id", "student_id");
    }
}
