<?php

namespace App\Supports\KDL;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class KDLAbstract implements KDLInterface
{
    protected array $query = [];

    protected function setQuery(array $query)
    {
        $this->query = $query;
    }

    /**
     * @throws \Exception
     */
    protected function request(string $api): array
    {
        try {
            $response = Http::get($api, $this->query + [
                    'format' => 'json',
                    'orderid' => config('kdl.order_id'),
                    'signature' => config('kdl.api_key'),
                ])->json();
            dump($response);
            if ($response['code'] != 0) {
                throw new \Exception($response['msg'], $response['code']);
            }
            return $response['data'];
        } catch (\Exception $e) {
            Log::info('KDL request error ', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'query' => $this->query,
            ]);
            throw new \Exception('KDL request error');
        }
    }
}
