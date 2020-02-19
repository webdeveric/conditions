<?php

declare(strict_types = 1);

namespace webdeveric\Conditions\Tests;

use PHPUnit\Framework\TestCase;
use webdeveric\Conditions\Conditions;

function returnTrue()
{
  return true;
}

function returnFalse()
{
  return false;
}

/**
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
class ConditionsTest extends TestCase
{
  public function testAny()
  {
    $conditions = Conditions::any(__NAMESPACE__ . '\returnTrue', __NAMESPACE__ . '\returnFalse');

    $this->assertTrue($conditions->check());
  }

  public function testAll()
  {
    $mixed = Conditions::all(__NAMESPACE__ . '\returnTrue', __NAMESPACE__ . '\returnFalse');

    $this->assertFalse($mixed->check());

    $allTrue = Conditions::all(__NAMESPACE__ . '\returnTrue', __NAMESPACE__ . '\returnTrue');

    $this->assertTrue($allTrue->check());
  }

  public function testNone()
  {
    $mixed = Conditions::none(__NAMESPACE__ . '\returnTrue', __NAMESPACE__ . '\returnFalse');

    $this->assertFalse($mixed->check());

    $allTrue = Conditions::none(__NAMESPACE__ . '\returnFalse', __NAMESPACE__ . '\returnFalse');

    $this->assertTrue($allTrue->check());
  }
}
