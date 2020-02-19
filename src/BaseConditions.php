<?php

declare(strict_types = 1);

namespace webdeveric\Conditions;

use SplQueue;
use Exception;
use webdeveric\Conditions\ConditionsInterface;

/**
 * This checks that all callbacks return true.
 *
 * It will return early and stop calling callbacks if a callback
 * returns false or throws an exception.
 */
class BaseConditions implements ConditionsInterface
{
  const ALL = 0;
  const ANY = 1;
  const NONE = 2;

  /**
   * This determines how the callback return values be handled.
   *
   * @var int
   */
  protected $mode = self::ALL;

  /**
   * This holds the callbacks.
   *
   * @var \SplQueue
   */
  protected $callbacks = null;

  /**
   * Create a Conditions.
   *
   * @param callable[] $callbacks
   *   Array of callbacks.
   */
  public function __construct(callable ...$callbacks)
  {
    $this->callbacks = new SplQueue();

    foreach ($callbacks as $callback) {
      $this->add($callback);
    }
  }

  /**
   * Get the number of callbacks.
   *
   * @return int
   *   The number of items in the callback queue.
   */
  public function count() : int
  {
    return $this->callbacks->count();
  }

  /**
   * Add a requirement.
   *
   * @param callable $callback
   *   A single callback.
   *
   * @return self
   *   The current instance.
   */
  public function add(callable $callback) : ConditionsInterface
  {
    $this->callbacks->enqueue($callback);

    return $this;
  }

  /**
   * Set the mode.
   *
   * @param int $mode
   *   The mode can be Conditions::ALL, Conditions::ANY, or Conditions::NONE.
   *
   * @return self
   *   The current instance.
   */
  protected function setMode(int $mode) : ConditionsInterface
  {
    $this->mode = $mode;

    return $this;
  }

  private function checkReturnValue($returnValue)
  {
    if ($this->mode === self::ANY && $returnValue === true) {
      // Only one callback must return true.
      return true;
    } elseif ($this->mode === self::ALL && $returnValue !== true) {
      // All callbacks must return true.
      return false;
    } elseif ($this->mode === self::NONE && $returnValue !== false) {
      // All callbacks must return false.
      return false;
    }

    return null;
  }

  /**
   * Check the requirements.
   *
   * @return bool
   *   Do all the callbacks pass?
   */
  public function check() : bool
  {
    $queue = clone $this->callbacks;
    $queue->setIteratorMode(SplQueue::IT_MODE_DELETE);

    try {
      foreach ($queue as $callback) {
        $returnValue = call_user_func($callback);

        // Callbacks are allowed to return a new callback
        // that'll be added to the end of the queue.
        if (is_callable($returnValue)) {
          $queue->enqueue($returnValue);

          continue;
        }

        // Maybe return early
        $value = $this->checkReturnValue($returnValue);

        if (isset($value)) {
          return $value;
        }
      }
    } catch (Exception $error) {
      return false;
    }

    return $this->mode !== self::ANY;
  }

  /**
   * Use the instance as a function.
   *
   * @return bool
   *   Do all the callbacks pass?
   */
  public function __invoke() : bool
  {
    return $this->check();
  }
}
