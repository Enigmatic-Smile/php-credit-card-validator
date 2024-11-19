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

    public function get(string $key, mixed $default = null): mixed
    {
        if (!array_key_exists($this->transformKey($key), $this->store)) {
            return $default;
        }

        return $this->store[$this->transformKey($key)];
    }

    public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null): bool
    {
        $this->store[$this->transformKey($key)] = $value;
        return true;
    }

    public function delete(string $key): bool
    {
        unset($this->store[$this->transformKey($key)]);
        return true;
    }

    public function clear(): bool
    {
        $this->store = [];
        return true;
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
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

    public function setMultiple(iterable $values, \DateInterval|int|null $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->set($this->transformKey($key), $value, $ttl);
        }

        return true;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($this->transformKey($key));
        }

        return true;
    }

    public function has(string $key): bool
    {
        return array_key_exists($this->transformKey($key), $this->store);
    }

    private function transformKey(string $key): string {
        return substr($key, 0, 6);
    }
}