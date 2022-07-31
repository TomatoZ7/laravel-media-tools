<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redis;
use RedisException;

class TestController extends Controller
{
    /**
     * @throws RedisException
     */
    public function test()
    {
        $redis = Redis::connection()->client();
        echo $redis->ping();
    }
}
