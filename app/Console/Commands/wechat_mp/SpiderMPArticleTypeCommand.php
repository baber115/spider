<?php

namespace App\Console\Commands\wechat_mp;

use App\Models\ArticleType;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class SpiderMPArticleTypeCommand extends Command
{
    protected $signature = 'spider:mp_article_type';

    protected $description = 'Command description';

    private $startArticleTypeUrl = 'https://mp.weixin.qq.com/mp/appmsgalbum';

    private $articleTypeParameters = [
        'action' => 'getalbum',
        'count' => '20',
        'f' => 'json',
    ];

    private $albums = [
        //保研声
        '上交高金' => '2147366435507994629',
        '复旦管院' => '2115392478190043139',
        '复旦泛海' => '2206760596556021761',
        '经管保研故事' => '2215446783713935361',
        '经管热点' => '2254808071560757251',
        '保研面试' => '2254808071141326850',
        '碳中和' => '2180639034639712258',
        '北交所' => '2180639035445018626',
        '数字经济' => '2254808071745306626',

        // 第一次抓取
//        '保研' => '1507954667740332036',
//        '保研夏令营' => '1849553906872221701',
//        '推免生' => '2259849671626113033',
//        '保研经验' => '1573152218646708226',
//        '文书' => '2271848546490564609',
    ];

    public function handle()
    {
        foreach ($this->albums as $key => $album) {
            dump("-----------start {$key}--------------------");
            $this->articleTypeParameters['album_id'] = $album;
            $this->articleType($key, '', '');
            dump("------------{$key} success-----------------");
            sleep(random_int(3, 6));
        }
    }

    public function articleType($album, $beginMsgId = '', $beginItemIdx = '')
    {
        if ($beginMsgId && $beginItemIdx) {
            $this->articleTypeParameters['begin_msgid'] = $beginMsgId;
            $this->articleTypeParameters['begin_itemidx'] = $beginItemIdx;
        } else {
            unset($this->articleTypeParameters['begin_msgid']);
            unset($this->articleTypeParameters['begin_itemidx']);
        }
        $response = Http::get($this->startArticleTypeUrl, $this->articleTypeParameters)->json();
        $articleType = [];
        foreach ($response['getalbum_resp']['article_list'] ?? [] as $item) {
            $articleType[] = [
                'tag' => $album,
                'pos_num' => $item['pos_num'],
                'key' => $item['key'],
                'title' => $item['title'],
                'url' => $item['url'],
                'cover_image_url' => $item['cover_img_1_1'],
                'mp_create_at' => $item['create_time'],
                'msgid' => $item['msgid'],
                'itemidx' => $item['itemidx'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        ArticleType::query()->insert($articleType);
        if (isset($response['getalbum_resp']['continue_flag']) && $response['getalbum_resp']['continue_flag']) {
            sleep(random_int(3, 6));
            $lastArticleType = Arr::last($articleType);
            $this->articleType($album, $lastArticleType['msgid'], $lastArticleType['itemidx']);
        }

        if (! isset($response['getalbum_resp']['continue_flag'])) {
            dump($response);
            dump($this->startArticleTypeUrl, $this->articleTypeParameters);
        }
    }
}
