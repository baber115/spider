<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleType extends Model
{
    protected $fillable = [
        'tag',
        'pos_num',
        'key',
        'title',
        'url',
        'cover_image_url',
        'mp_create_at',
        'msgid',
    ];
}
