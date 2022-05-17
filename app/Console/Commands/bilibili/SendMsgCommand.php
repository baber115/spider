<?php

namespace App\Console\Commands\bilibili;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class SendMsgCommand extends Command
{
    protected $signature = 'bilibili:send_msg';

    protected $description = '发送直播弹幕，bilibili';

    protected $emotions = [147, 109, 113, 120, 150, 103, 128, 133, 149, 124, 146, 148, 102, 121, 137, 118, 129, 108, 104, 105, 106, 114, 107, 110, 111, 136, 115, 116, 117, 119, 122, 123, 125, 126, 127, 134, 135, 138,];

    protected $cookie = "buvid3=847B12A8-2195-6A3B-D378-E5F309CE277C56126infoc; _uuid=E1921F8F-8B59-D6C10-A1095-75F3A9ABDEC456227infoc; buvid4=28F7EB88-8471-5130-B891-2318D24AA9DE56970-022021117-DLQaI8fO0w/aXrjcPEQScw%3D%3D; blackside_state=1; rpdid=|(u~|mR)Y)J|0J'uYR~)JRlJl; i-wanna-go-back=-1; b_ut=7; nostalgia_conf=-1; CURRENT_BLACKGAP=0; innersign=0; b_lsid=F1410AE3C_180D0DC21FC; sid=b2fu2pg1; fingerprint=562fe084394159b503d807ec95f0fbe7; buvid_fp_plain=undefined; DedeUserID=33874315; DedeUserID__ckMd5=c349b8d8e97c6afd; SESSDATA=fed133f6%2C1668323556%2C95609*51; bili_jct=0172a422fe0bde076d668410080af6da; CURRENT_FNVAL=80; hit-dyn-v2=1; buvid_fp=562fe084394159b503d807ec95f0fbe7; _dfcaptcha=e4e52256bf78fd7d9b99dad329a72471; PVID=1; LIVE_BUVID=AUTO9516527715768399; Hm_lvt_8a6e55dbd2870f0f5bc9194cddf32a02=1652771600; Hm_lpvt_8a6e55dbd2870f0f5bc9194cddf32a02=1652771600";

    public function handle()
    {

        while (true) {
            $this->sendEmotion();
            sleep(10);
        }
    }

    public function sendEmotion()
    {
        $data = [
            'bubble' => 0,
            'color' => 16777215,
            'mode' => 1,
            'fontsize' => 25,
            'rnd' => 1652771574,
            'roomid' => 251332,
            'csrf' => '0172a422fe0bde076d668410080af6da',
            'csrf_token' => '0172a422fe0bde076d668410080af6da',
        ];
        if (random_int(1, 99) > 50) {
            $data['dm_type'] = 1;
            $data['msg'] = "official_".Arr::random($this->emotions);
        } else {
            $text = file_get_contents(app_path('Console\Commands\bilibili\tangshi.txt'));
            $data['msg'] = Arr::random(explode("\n", $text));
        }
        dump($data);
        $response = Http::withHeaders([
            'cookie' => $this->cookie,
        ])->asForm()
            ->post("https://api.live.bilibili.com/msg/send", $data)
            ->json();
        dump($response);
    }
}
