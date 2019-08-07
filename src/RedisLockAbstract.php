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
     * @var Redis;
     */
    protected static $redis;

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
        $is_lock = $redis->set($lock_key, time() + $expire, array('nx', 'ex' => $expire));

        if (!$is_lock) {
            $lock_time = $redis->get($lock_key);
            if (time() > $lock_time) {
                static::unlock($key);
                $is_lock = $redis->set($lock_key, time() + $expire, array('nx', 'ex' => $expire));
            }
        }

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

    /**
     * 续锁时间
     *
     * @param string $key 锁 key
     * @param int $expire 过时时间
     *
     * @return bool
     */
    static public function renew($key, $expire = 5)
    {
        $lock_key = $key . '_lock_key';
        return static::getInstance()->set($lock_key, time() + $expire, $expire);
    }
}