<?php

declare(strict_types=1);

namespace App\DataStructures;

use IteratorAggregate;
use Traversable;

final class HashTable implements IteratorAggregate
{
    protected array $buckets;

    public function __construct(protected int $size = 20)
    {
        // Initialize buckets to hold arrays for separate chaining
        $this->buckets = array_fill(0, $this->size, []);
    }

    public function insert(string $key, $value): void
    {
        $index = $this->hash($key);
        if ($this->buckets[$index] === null) {
            $this->buckets[$index] = []; // Initialize the bucket if empty
        }

        $this->buckets[$index][$key] = $value; // Add or update the key
    }

    public function get(string $key)
    {
        $index = $this->hash($key);

        // Check if bucket and key exist before returning
        return $this->buckets[$index][$key] ?? null;
    }

    public function contains(string $key): bool
    {
        $index = $this->hash($key);

        return isset($this->buckets[$index][$key]);
    }

    public function delete(string $key): void
    {
        $index = $this->hash($key);
        if (! isset($this->buckets[$index][$key])) {
            return;
        }

        unset($this->buckets[$index][$key]);
    }

    public function getIterator(): Traversable
    {
        foreach ($this->buckets as $bucket) {
            foreach ($bucket as $key => $value) {
                yield $key => $value;
            }
        }
    }

    protected function hash(string $key): int
    {
        $hash = 0;
        for ($i = 0, $iMax = mb_strlen($key); $i < $iMax; $i++) {
            $hash = ($hash + ord($key[$i])) % $this->size;
        }

        return $hash;
    }
}
