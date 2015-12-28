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
 * Tests for bovigo\assert\predicate\StringStartsWith.
 *
 * @group  predicate
 * @since  1.1.0
 */
class StringStartsWithTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException  InvalidArgumentException
     */
    public function createWithNonStringThrowsInvalidArgumentException()
    {
        startsWith(303);
    }

    /**
     * @test
     * @expectedException  InvalidArgumentException
     */
    public function nonStringValuesThrowInvalidArgumentException()
    {
        startsWith('foo')->test(303);
    }

    /**
     * @return  array
     */
    public function trueValues()
    {
        return [
          'string which starts with and contains foo' => ['foobarfoobaz'],
          'string which starts with foo'              => ['foobarbaz']
        ];
    }

    /**
     * @param  string  $value
     * @test
     * @dataProvider  trueValues
     */
    public function evaluatesToTrueIfStringStartsWithPrefix($value)
    {
        assert(startsWith('foo')->test($value), isTrue());
    }

    /**
     * @return  array
     */
    public function falseValues()
    {
        return [
          'string which contains foo'  => ['barfoobaz'],
          'string which ends with foo' => ['barbazfoo']
        ];
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  falseValues
     */
    public function evaluatesToFalseIfGivenValueIsFalse($value)
    {
        assert(startsWith('foo')->test($value), isFalse());
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 'bar' starts with 'foo'.
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        assert('bar', startsWith('foo'));
    }
}
