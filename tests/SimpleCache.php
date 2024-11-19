<?php

namespace Freelancehunt\Validators\Tests;

use Psr\SimpleCache\CacheInterface;

class SimpleCache implements CacheInterface
{
    private array $store;

    public function __construct(array $store = [])
    {
        $this->store = $store;
    }

    public function get($key, $default = null)
    {
        if (!array_key_exists($this->transformKey($key), $this->store)) {
            return $default;
        }

        return $this->store[$this->transformKey($key)];
    }

    public function set($key, $value, $ttl = null)
    {
        $this->store[$this->transformKey($key)] = $value;
        return true;
    }

    public function delete($key)
    {
        unset($this->store[$this->transformKey($key)]);
        return true;
    }

    public function clear()
    {
        $this->store = [];
        return true;
    }

    public function getMultiple($keys, $default = null)
    {
        $returns = [];
        foreach ($keys as $key) {
            if (!array_key_exists($this->transformKey($key), $this->store)) {
                $returns[$this->transformKey($key)] = $default;
                continue;
            }

            $returns[$this->transformKey($key)] = $this->store[$this->transformKey($key)];
        }

        return $returns;
    }

    public function setMultiple($values, $ttl = null)
    {
        foreach ($values as $key => $value) {
            $this->set($this->transformKey($key), $value, $ttl);
        }

        return true;
    }

    public function deleteMultiple($keys)
    {
        foreach ($keys as $key) {
            $this->delete($this->transformKey($key));
        }

        return true;
    }

    public function has($key)
    {
        return array_key_exists($this->transformKey($key), $this->store);
    }

    private function transformKey($key) {
        return substr($key, 0, 6);
    }
}