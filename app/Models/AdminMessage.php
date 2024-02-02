<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        "message",
        "student",
        "author"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "author");
    }
}
