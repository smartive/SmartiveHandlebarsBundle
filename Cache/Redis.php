<?php

namespace Smartive\HandlebarsBundle\Cache;

use Handlebars\Cache;
use Predis\Client as Predis;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Redis as PhpRedis;

/**
 * Client to cache Handlebars templates using Redis.
 */
class Redis implements Cache
{
    /**
     * Default key prefix
     */
    const KEY_PREFIX_DEFAULT = 'smartive-handlebars:';

    /**
     * @var Client|\Redis
     */
    private $redisClient;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $keyPrefix;

    /**
     * Constructor
     *
     * @param Predis|PhpRedis $redisClient Redis client instance
     * @param LoggerInterface $logger      Logger instance
     * @param string          $keyPrefix   A prefix to append
     */
    public function __construct($redisClient, LoggerInterface $logger = null, $keyPrefix = self::KEY_PREFIX_DEFAULT)
    {
        if (!$redisClient instanceof Predis && !(class_exists('Redis') && $redisClient instanceof PhpRedis)) {
            throw new \InvalidArgumentException('redisClient has to be of type Predis\Client or Redis');
        }

        $this->redisClient = $redisClient;
        $this->keyPrefix = $keyPrefix;

        if ($logger) {
            $this->logger = $logger;
        } else {
            $this->logger = new NullLogger();
        }
    }

    /**
     * Get cache for $name if exist.
     *
     * @param string $name Cache id
     *
     * @return mixed data on hit, boolean false on cache not found
     */
    public function get($name)
    {
        try {
            $data = $this->redisClient->get($this->keyPrefix.$name);

            return $data !== false && $data !== null ? unserialize($data) : false;
        } catch (\Exception $e) {
            $this->logger->alert(sprintf('Failed to read value of key "%s" using Redis: %s', $name, $e->getMessage()));

            return false;
        }
    }

    /**
     * Set a cache
     *
     * @param string $name  cache id
     * @param mixed  $value data to store
     *
     * @return void
     */
    public function set($name, $value)
    {
        try {
            $this->redisClient->set($this->keyPrefix.$name, serialize($value));
        } catch (\Exception $e) {
            $this->logger->alert(sprintf('Failed to set value "%s" for key "%s" using Redis: %s', $value, $name, $e->getMessage()));
        }
    }

    /**
     * Remove cache
     *
     * @param string $name Cache id
     *
     * @return void
     */
    public function remove($name)
    {
        try {
            $this->redisClient->del($this->keyPrefix . $name);
        } catch (\Exception $e) {
            $this->logger->alert(sprintf('Failed to remove key "%s" using Redis: %s', $name, $e->getMessage()));
        }
    }
}
