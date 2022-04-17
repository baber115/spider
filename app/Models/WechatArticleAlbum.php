<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WechatArticleAlbum extends Model
{
    protected $fillable = [
        'album_id',
        'target_id',
        'tagSource',
        'title',
    ];
}
