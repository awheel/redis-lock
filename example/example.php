<?php

namespace awheel\RedisLock;

require __DIR__.'/../src/RedisLockAbstract.php';

use Redis;

class RedisLock extends RedisLockAbstract
{
    /**
     * 获取 Redis 对象
     *
     * @return Redis
     */
    static public function getInstance()
    {
        self::$redis = new Redis();
        self::$redis->connect('127.0.0.1');

        return self::$redis;
    }
}

$key = 'redis_lock_test';
if (RedisLock::lock($key)) {
    printf("locked\n");

    // do something
    RedisLock::renew($key, 5);

    $unlock = RedisLock::unlock($key);
    $unlock && printf("unlocked\n");
}
