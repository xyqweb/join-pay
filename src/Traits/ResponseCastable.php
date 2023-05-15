<?php
declare(strict_types = 1);

/**
 * Created by PhpStorm.
 * User: xingyongqiang
 * Date: 5/15/23
 * Time: 10:42 AM
 */

namespace xyqWeb\JoinPay\Traits;


use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;

trait ResponseCastable
{
    /**
     * @param ResponseInterface $response
     *
     * @return Collection
     */
    protected function castResponse(ResponseInterface $response): Collection
    {
        $contents = $response->getBody()->getContents();
        $response->getBody()->rewind();
        $array = json_decode($contents, true, 512, JSON_BIGINT_AS_STRING);
        if (JSON_ERROR_NONE === json_last_error()) {
            return new Collection($array);
        }
        return new Collection();
    }
}