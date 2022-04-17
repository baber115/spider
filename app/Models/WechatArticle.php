<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WechatArticle extends Model
{
    protected $fillable = [
        'aid',
        'album_id',
        'appmsgid',
        'checking',
        'copyright_type',
        'cover',
        'create_time',
        'digest',
        'has_red_packet_cover',
        'is_pay_subscribe',
        'item_show_type',
        'itemidx',
        'link',
        'media_duration',
        'mediaapi_publish_status',
        'title',
        'update_time',
    ];
}
