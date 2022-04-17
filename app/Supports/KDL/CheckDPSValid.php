<?php

namespace App\Supports\KDL;

class CheckDPSValid extends KDLAbstract
{
    protected array $proxy;

    public function __construct(array $proxy)
    {
        $this->proxy = $proxy;
    }

    public function handle()
    {
        $api = 'https://dps.kdlapi.com/api/checkdpsvalid';
        $this->setQuery([
            'proxy' => implode(',', $this->proxy),
        ]);

        return $this->request($api);
    }
}
