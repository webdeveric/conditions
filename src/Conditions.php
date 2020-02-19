<?php

declare(strict_types = 1);

namespace webdeveric\Conditions;

use SplQueue;
use Exception;
use webdeveric\Conditions\BaseConditions;

/**
 * This checks that all callbacks return true.
 *
 * It will return early and stop calling callbacks if a callback
 * returns false or throws an exception.
 */
class Conditions extends BaseConditions
{
  /**
   * Create an instace with a mode.
   *
   * @param array $args
   *   Array of callables.
   * @param int $mode
   *   The mode can be Conditions::ALL, Conditions::ANY, or Conditions::NONE.
   *
   * @return self
   *   New Conditions instance.
   */
  protected static function create(array $args, int $mode) : self
  {
    $instance = new self(...$args);
    $instance->setMode($mode);

    return $instance;
  }

  /**
   * Create an instace with mode set to ANY.
   *
   * @param callable[] $args
   *   Array of callbacks.
   *
   * @return self
   *   New Conditions instance.
   */
  public static function any(callable ...$args) : self
  {
    return self::create($args, self::ANY);
  }

  /**
   * Create an instace with mode set to ALL.
   *
   * @param callable[] $args
   *   Array of callbacks.
   *
   * @return self
   *   New Conditions instance.
   */
  public static function all(callable ...$args) : self
  {
    return self::create($args, self::ALL);
  }

  /**
   * Create an instace with mode set to NONE.
   *
   * @param callable[] $args
   *   Array of callbacks.
   *
   * @return self
   *   New Conditions instance.
   */
  public static function none(callable ...$args) : self
  {
    return self::create($args, self::NONE);
  }
}
