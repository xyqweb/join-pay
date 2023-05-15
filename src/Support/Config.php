<?php
declare(strict_types = 1);

/**
 * Created by PhpStorm.
 * User: xingyongqiang
 * Date: 5/13/23
 * Time: 5:45 PM
 */

namespace xyqWeb\JoinPay\Support;


use Illuminate\Support\Collection;

class Config extends Collection
{
    public function __get($key)
    {
        return $this->get($key);
    }
}