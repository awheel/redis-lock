# redis-lock
Redis 锁

## 使用

1. 继承 `RedisLockAbstract` 抽象类
2. 实现 `getInstance` 方法, 且 `getInstance` 方法必须返回一个标准的可用的 `Redis` 实例

示例:
````
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

````
