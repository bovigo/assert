<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use bovigo\assert\AssertionFailure;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\assert;
use function bovigo\assert\expect;
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
class HasKeyTest extends TestCase
{
    /**
     * @test
     */
    public function createWithNonIntegetOrStringThrowsInvalidArgumentException()
    {
        expect(function() { hasKey([]); })
                ->throws(\InvalidArgumentException::class);
    }

    /**
     * returns tuples which evaluate to true
     *
     * @return  array
     */
    public function tuplesEvaluatingToTrue(): array
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
    public function tuplesEvaluatingToFalse(): array
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
     */
    public function throwsInvalidArgumentExceptionWhenValueCanNotHaveKey()
    {
        expect(function() { hasKey('foo')->test(303); })
                ->throws(\InvalidArgumentException::class);
    }

    /**
     * @test
     */
    public function assertionFailureWithArrayContainsMeaningfulInformation()
    {
        expect(function() { assert([], hasKey('bar')); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that an array has the key 'bar'.");
    }

    /**
     * @test
     */
    public function assertionFailureWithArrayAccessContainsMeaningfulInformation()
    {
        expect(function() { assert(new ArrayAccessExample(), hasKey('bar')); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        "Failed asserting that " . ArrayAccessExample::class
                        . " implementing \ArrayAccess has the key 'bar'."
        );
    }
}
