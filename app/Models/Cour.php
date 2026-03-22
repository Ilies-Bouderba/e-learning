<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Cour extends Model
{
    use HasFactory;

    protected $fillable = ['icon', 'teacher_id', 'title', 'description'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
