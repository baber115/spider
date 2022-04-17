<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
    protected $fillable = [
        'ip',
        'post',
        'available_time',
        'is_discard',
    ];
}
