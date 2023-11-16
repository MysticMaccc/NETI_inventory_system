<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_type extends Model
{
    use HasFactory;

    public function User()
    {
        return $this->hasMany(User::class , 'usertype_id');
    }
}