<?php

namespace App\Console\Commands\wechat_mp;

use App\Models\Proxy;
use App\Supports\KDL\CheckDPSValid;
use App\Supports\KDL\GetDPS;
use Illuminate\Console\Command;

abstract class SpiderMPArticleAbstract extends Command
{
    protected $proxy = '';

    protected function getProxies(): array
    {
        $getDPS = new GetDPS(2);

        $proxies = $getDPS->handle();
        $data = [];
        foreach ($proxies["proxy_list"] as $item) {
            $proxy = explode(':', $item);
            $data[] = [
                'ip' => $proxy[0] ?? '',
                'post' => $proxy[1] ?? '',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Proxy::query()->insert($data);

        return [];
    }

    protected function checkProxies()
    {
        $checkProxy = new CheckDPSValid([
            '114.217.104.113:23068',
        ]);
    }
}
