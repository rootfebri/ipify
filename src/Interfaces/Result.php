<?php

namespace IPify\Interfaces;

/**
 * Interface Result
 *
 * @package IPify\Interfaces
 *
 * @template T
 * @template E
 */
interface Result {
  /**
   * @return bool
   */
  public function is_ok(): bool;

  /**
   * @return bool
   */
  public function is_err(): bool;

  /**
   * @return T
   */
  public function unwrap();

  /**
   * @return E
   */
  public function unwrap_err();
}
