<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';
    protected $fillable = ['FullName', 'E-Mail', 'Phone', 'PositionId', 'Photo'];
}
