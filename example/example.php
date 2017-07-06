<?php

namespace awheel\RedisLock;

require '../src/RedisLockAbstract.php';

use Redis;

class example extends RedisLockAbstract
{
    /**
     * 获取 Redis 对象
     *
     * @return Redis
     */
    static public function getInstance()
    {
        $redis = new Redis();
        $redis->connect('127.0.0.1');

        return $redis;
    }
}

$key = 'redis_lock_test';
$data = example::getInstance()->get($key);

if (example::lock($key)) {
    $data = 'hello world';
    example::getInstance()->setex($key, 10, $data);
    example::unlock($key);
}

var_dump($data);