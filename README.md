# redis-lock
Redis 锁

## 使用

1. 继承 `RedisLockAbstract` 抽象类
2. 实现 `getInstance` 方法, 且 `getInstance` 方法必须返回一个标准的可用的 `Redis` 示例

示例:
````
<?php

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
		$redis = new Redis();
		$redis->connect('127.0.0.1');
		$redis->select(1);

		return $redis;
	}
}

// 实际使用
$key = 'redis_lock_test';
$data = RedisLock::getInstance()->get($key);

if (RedisLock::lock($key)) {
    $data = 'hello world';
    RedisLock::getInstance()->setex($key, 10, $data);
    Redislock::unlock($key);
}

var_dump($data);
````
