<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use function bovigo\assert\assert;
/**
 * Tests for bovigo\assert\predicate\StringEndsWith.
 *
 * @group  predicate
 * @since  1.1.0
 */
class StringEndsWithTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException  InvalidArgumentException
     */
    public function createWithNonStringThrowsInvalidArgumentException()
    {
        endsWith(303);
    }

    /**
     * @test
     * @expectedException  InvalidArgumentException
     */
    public function nonStringValuesThrowInvalidArgumentException()
    {
        endsWith('foo')->test(303);
    }

    /**
     * @return  array
     */
    public function trueValues()
    {
        return [
          'string which ends with and contains foo' => ['barfoobazfoo'],
          'string which ends with foo'              => ['barbazfoo']
        ];
    }

    /**
     * @param  string  $value
     * @test
     * @dataProvider  trueValues
     */
    public function evaluatesToTrueIfStringStartsWithPrefix($value)
    {
        assert(endsWith('foo')->test($value), isTrue());
    }

    /**
     * @return  array
     */
    public function falseValues()
    {
        return [
          'string which contains foo'    => ['barfoobaz'],
          'string which starts with foo' => ['foobarbaz']
        ];
    }

    /**
     * @param  string  $value
     * @test
     * @dataProvider  falseValues
     */
    public function evaluatesToFalseIfStringDoesNotEndWithSuffix($value)
    {
        assert(endsWith('foo')->test($value), isFalse());
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 'bar' ends with 'foo'.
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        assert('bar', endsWith('foo'));
    }
}
