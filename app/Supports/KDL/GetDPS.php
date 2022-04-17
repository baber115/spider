<?php

namespace App\Supports\KDL;

/**
 * 获取私密代理IP
 */
class GetDPS extends KDLAbstract
{
    protected int $num;

    public function __construct(int $num)
    {
        $this->num = $num;
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $api = 'http://dps.kdlapi.com/api/getdps';
        $this->setQuery([
            'num' => $this->num,
        ]);

        return $this->request($api);
    }
}
