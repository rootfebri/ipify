<?php

namespace IPify\Interfaces;

interface FromArray {
  /**
   * @param array $params The response body from the API
   * @return static The class that implements this interface
   */
  public static function fromArray(array $params): static;
}
