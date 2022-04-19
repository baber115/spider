<?php

namespace App\Console\Commands;

use App\Models\WechatArticle;
use App\Models\WechatArticleAlbum;
use App\Supports\KDL\KDLAbstract;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class SpiderMPArticleTypeNewCommand extends SpiderMPArticleAbstract
{
    protected $signature = 'spider:mp_article_type_new';

    protected $description = 'Command description';

    /**
     * @var \App\Supports\KDL\KDLAbstract
     */
    protected KDLAbstract $KDLAbstract;

    private $url = 'https://mp.weixin.qq.com/cgi-bin/appmsg?action=list_ex&begin=%s&count=5&fakeid=Mzg2NTU1NDQwMw==&type=9&query=&token=1921337103&lang=zh_CN&f=json&ajax=1';

    private $header = [
        'cookie' => 'appmsglist_action_3900032031=card; ptui_loginuin=934472858; RK=3Iv9OTXkXo; ptcz=330a0b6f119a180c773803aef5b6d1b57223f7aa58d2199295e41b2d569d0e4d; pgv_pvid=5253886320; ua_id=3Knh8jQnk6dsA2TAAAAAAI6r1cvHkvsBCcKSOXjSGHE=; mm_lang=zh_CN; pgv_info=ssid=s9479660000; uuid=0bb2d5102086a3a6529207a538fac7e1; rand_info=CAESIPoOygncZBsxHddktDWHVR/FJ8M2OvkTFklTUcR3eR3p; slave_bizuin=3900032031; data_bizuin=3900032031; bizuin=3900032031; data_ticket=xTA38ASQPv/ft51eIUSqUyPVQCSxKVzpt33+QHlRB65WV0SV0Mj1LIliqa997SFO; slave_sid=T1RtTGphQnFDNUFLYXZqMEpXbTN6QW5QMDlINlUwWVMwaHZhV3IyeEFDT19NY1h1a3NieDUxZkQzeGNrS3htRzdScXhyYjdDaE5lWWNyVjFRVUV4RTN1ZTFqVjNPaE1YeFFRMGpzZl9yOHFOT3BfUUJFUmp1STM4SEdPT3ZyMW1NdVcwejVlVEQ2ZGJTb3Vj; slave_user=gh_6834b7dada40; xid=960883fd8818b098062092c3620f324d; _dd_s=logs=1&id=628f74cb-b468-4486-a1ba-f4ec0909c2f2&created=1650158159720&expire=1650161538390',
    ];

    public function handle()
    {
        $begin = Redis::get('wechat_mp_begin');
        if (! $begin) {
            $begin = 0;
        }
        $this->crawl($begin);
    }

    /**
     * @param $begin
     * @return void
     */
    public function crawl($begin = 0)
    {
        dump("抓取微信公众号文章，begin:{$begin}");
        Redis::set('wechat_mp_begin', $begin);
        $url = sprintf($this->url, $begin);
        $request = $this->request($url);
        if (! $request) {
            $this->checkRequest($url);
            $this->request($url);
        }
        $this->crawl($begin + 5);
    }

    /**
     * @param  string  $url
     * @return bool
     */
    public function request(string $url): bool
    {
        $response = Http::withHeaders($this->header)->get($url)->json();
        if (isset($response['base_resp']['ret']) && $response['base_resp']['ret'] == 200013) {
            return false;
        }
        $count = count($response['app_msg_list']);
        if ($count == 0) {
            dd('----------', 'finish', '----------');
        }
        foreach ($response['app_msg_list'] as $arr) {
            foreach ($arr['appmsg_album_infos'] ?? [] as $albumInfo) {
                WechatArticleAlbum::firstOrCreate([
                    'target_id' => $albumInfo['id'],
                ], [
                    'album_id' => $albumInfo['album_id'],
                    'tagSource' => $albumInfo['tagSource'],
                    'title' => $albumInfo['title'],
                ]);
            }
            WechatArticle::firstOrCreate([
                'aid' => $arr['aid'],
            ], [
                'album_id' => $arr['album_id'],
                'appmsgid' => $arr['appmsgid'],
                'checking' => $arr['checking'],
                'copyright_type' => $arr['copyright_type'],
                'cover' => $arr['cover'],
                'create_time' => $arr['create_time'],
                'digest' => $arr['digest'],
                'has_red_packet_cover' => $arr['has_red_packet_cover'],
                'is_pay_subscribe' => $arr['is_pay_subscribe'],
                'item_show_type' => $arr['item_show_type'],
                'itemidx' => $arr['itemidx'],
                'link' => $arr['link'],
                'media_duration' => $arr['media_duration'],
                'mediaapi_publish_status' => $arr['mediaapi_publish_status'],
                'title' => $arr['title'],
                'update_time' => $arr['update_time'],
            ]);
        }
        dump("单次抓取成功，总数：{$count}");

        return true;
    }

    /**
     * @param  string  $url
     * @return bool
     */
    public function checkRequest(string $url): bool
    {
        $i = 0;
        while (true) {
            $i++;
            dump("重试次数:{$i}");
            $response = Http::withHeaders($this->header)->get($url)->json();
            if (! isset($response['app_msg_list'])) {
                sleep(300);
            } else {
                dump("重试成功");
                break;
            }
        }

        return true;
    }
}
