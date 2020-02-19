<?php

declare(strict_types = 1);

namespace webdeveric\Conditions;

use SplQueue;
use Exception;

interface ConditionsInterface
{
  public function add(callable $callback) : self;

  public function check() : bool;

  public function __invoke() : bool;
}
