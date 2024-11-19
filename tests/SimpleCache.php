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
        if (!array_key_exists($key, $this->store)) {
            return $default;
        }

        return $this->store[$key];
    }

    public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null): bool
    {
        $this->store[$key] = $value;
        return true;
    }

    public function delete(string $key): bool
    {
        unset($this->store[$key]);
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
            if (!array_key_exists($key, $this->store)) {
                $returns[$key] = $default;
                continue;
            }

            $returns[$key] = $this->store[$key];
        }

        return $returns;
    }

    public function setMultiple(iterable $values, \DateInterval|int|null $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }

        return true;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }

        return true;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->store);
    }
}