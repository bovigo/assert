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
 * Helper class for the test.
 */
class ArrayAccessExample implements \ArrayAccess
{
    public function offsetExists($offset)
    {
        return 'foo' === $offset || 303 === $offset;
    }

    public function offsetGet($offset) { }

    public function offsetSet($offset, $value) { }

    public function offsetUnset($offset) { }

}
/**
 * Tests for bovigo\assert\predicate\HasKey.
 *
 * @group  predicate
 */
class HasKeyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException  InvalidArgumentException
     */
    public function createWithNonIntegetOrStringThrowsInvalidArgumentException()
    {
        hasKey([]);
    }
    /**
     * returns tuples which evaluate to true
     *
     * @return  array
     */
    public function tuplesEvaluatingToTrue()
    {
        return [
                [0, ['foo']],
                ['bar', ['bar' => 'foo5']],
                [303, new ArrayAccessExample()],
                ['foo', new ArrayAccessExample()],
        ];
    }

    /**
     * @param  int|string          $key
     * @param  array|\ArrayAccess  $array
     * @test
     * @dataProvider  tuplesEvaluatingToTrue
     */
    public function evaluatesToTrue($key, $array)
    {
        assert(hasKey($key)->test($array), isTrue());
    }

    /**
     * returns tuples which evaluate to false
     *
     * @return  array
     */
    public function tuplesEvaluatingToFalse()
    {
        return [
                [5, []],
                ['foo', []],
                [313, new ArrayAccessExample()],
                ['bar', new ArrayAccessExample()]
        ];
    }

    /**
     * @param  mixed                      $key
     * @param  string|array|\Traversable  $array
     * @test
     * @dataProvider  tuplesEvaluatingToFalse
     */
    public function evaluatesToFalse($key, $array)
    {
        assert(hasKey($key)->test($array), isFalse());
    }

    /**
     * @test
     * @expectedException  InvalidArgumentException
     */
    public function throwsInvalidArgumentExceptionWhenValueCanNotHaveKey()
    {
        hasKey('foo')->test(303);
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that an array has the key 'bar'.
     */
    public function assertionFailureWithArrayContainsMeaningfulInformation()
    {
        assert([], hasKey('bar'));
    }

    /**
     * @group  foo
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that bovigo\assert\predicate\ArrayAccessExample implementing \ArrayAccess has the key 'bar'.
     */
    public function assertionFailureWithArrayAccessContainsMeaningfulInformation()
    {
        assert(new ArrayAccessExample(), hasKey('bar'));
    }
}
