<?php

namespace awheel\RedisLock;

use Redis;

/**
 * Redis 锁
 *
 * @package awheel\RedisLock
 */
abstract class RedisLockAbstract
{
	/**
     * 获取 Redis 对象
     *
     * @return Redis
     */
    abstract static public function getInstance();

	/**
     * 加锁
     *
     * @param string $key 缓存 key
     * @param int $expire 锁定时间
     *
     * @return bool
     */
    static public function lock($key, $expire = 5)
    {
        $redis = static::getInstance();
        $lock_key = $key . '_lock_key';
        $is_lock = $redis->setnx($lock_key, time() + $expire);

        if (!$is_lock) {
            $lock_time = $redis->get($lock_key);
            if (time() > $lock_time) {
                static::unlock($key);
                $is_lock = $redis->setnx($lock_key, time() + $expire);
            }
        }

        $redis->expire($lock_key, $expire);

        return (bool) $is_lock;
    }

    /**
     * 释放锁
     *
     * @param string $key 缓存 key
     *
     * @return bool
     */
    static public function unlock($key)
    {
        $lock_key = $key . '_lock_key';

        return (bool) static::getInstance()->del($lock_key);
    }
}