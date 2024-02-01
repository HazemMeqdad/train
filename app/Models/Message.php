<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        "author",
        "subject",
        "content"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "author");
    }

    public function messageSubject()
    {
        return $this->belongsTo(Subject::class, "subject");
    }
}
