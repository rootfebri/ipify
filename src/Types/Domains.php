<?php

namespace IPify\Types;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IPify\Interfaces\FromArray;
use IteratorAggregate;
use JsonSerializable;
use Stringable;
use Traversable;

class Domains implements Countable, IteratorAggregate, ArrayAccess, JsonSerializable, Stringable, FromArray {
  public function __construct(
    private array $domains = [],
  )
  {
  }

  public static function fromArray(array $params): static
  {
    return new static(
      domains: $params,
    );
  }

  public function add(string $domain): void
  {
    if (!$this->has($domain)) {
      $this->domains[] = $domain;
    }
  }

  public function has(string $domain): bool
  {
    return in_array($domain, $this->domains);
  }

  public function remove(string $domain): void
  {
    if ($this->has($domain)) {
      $this->domains = array_diff($this->domains, [$domain]);
    }
  }

  public function all(): array
  {
    return $this->domains;
  }

  public function count(): int
  {
    return count($this->domains);
  }

  public function __toString(): string
  {
    return implode(', ', $this->domains);
  }

  public function getIterator(): Traversable
  {
    return new ArrayIterator($this->domains);
  }

  public function offsetExists(mixed $offset): bool
  {
    return isset($this->domains[$offset]);
  }

  public function offsetGet(mixed $offset): mixed
  {
    return $this->domains[$offset] ?? null;
  }

  public function offsetSet(mixed $offset, mixed $value): void
  {
    if (is_null($offset)) {
      $this->domains[] = $value;
    } else {
      $this->domains[$offset] = $value;
    }
  }

  public function offsetUnset(mixed $offset): void
  {
    if (isset($this->domains[$offset])) {
      unset($this->domains[$offset]);
    }
  }

  public function jsonSerialize(): mixed
  {
    return $this->domains;
  }
}
