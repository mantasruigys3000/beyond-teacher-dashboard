<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    //
    public $hidden = [
        'user_id'
    ];
}
