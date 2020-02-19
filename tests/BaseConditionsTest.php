<?php

declare(strict_types = 1);

namespace webdeveric\Conditions\Tests;

use PHPUnit\Framework\TestCase;
use webdeveric\Conditions\BaseConditions;

class BaseConditionsTest extends TestCase
{
  public function testConstructor()
  {
    $conditions = new BaseConditions(function () {
    });

    $this->assertEquals($conditions->count(), 1);

    $this->assertEquals((new BaseConditions())->count(), 0);
  }

  public function testAdd()
  {
    $conditions = new BaseConditions();

    $conditions->add(function () {
    });

    $this->assertEquals($conditions->count(), 1);

    $this->assertTrue(
        $conditions->add(function () {
        }) === $conditions
    );
  }

  public function testCallbacks()
  {
    $example = new class {
      public static function validate()
      {
        return true;
      }
      public function check()
      {
        return true;
      }
    };

    $conditions = new BaseConditions();

    $conditions->add(function () {
      return true;
    });
    $conditions->add([$example, 'validate']);
    $conditions->add([new $example(), 'check']);

    $this->assertTrue($conditions->check());
  }

  public function testCheckPasses()
  {
    $conditions = new BaseConditions(function () {
      return true;
    });

    $this->assertTrue($conditions->check());
  }

  public function testCheckFails()
  {
    $conditions = new BaseConditions(
        function () {
          return true;
        },
        function () {
          return false;
        }
    );

    $this->assertFalse($conditions->check());
  }
}
