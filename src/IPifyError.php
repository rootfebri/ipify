<?php

namespace IPify;

use IPify\Interfaces\Result;
use Stringable;

final class IPifyError implements Stringable, Result {
  public function __construct(
    protected int    $code,
    protected string $message,
  )
  {
  }

  public function getMessage(): string
  {
    return $this->message;
  }

  public function getCode()
  {
    return $this->code;
  }

  public function __toString()
  {
    return sprintf(
      '%s: [%d]: %s',
      __CLASS__,
      $this->code,
      $this->message
    );
  }

  public function is_ok(): bool
  {
    return false;
  }

  public function is_err(): bool
  {
    return true;
  }

  public function unwrap()
  {
    return null;
  }

  public function unwrap_err()
  {
    return $this;
  }
}
