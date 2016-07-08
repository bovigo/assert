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

use function bovigo\assert\{
    assert,
    assertFalse,
    assertTrue,
    expect
};
/**
 * Helper class for the test.
 */
class IteratorAggregateEachKeyExample implements \IteratorAggregate
{
    private $iterator;

    public function __construct()
    {
        $this->iterator = new \ArrayIterator([303, 313, 'foo']);
    }
    public function getIterator(): \Traversable
    {
        return $this->iterator;
    }
}
/**
 * Tests for bovigo\assert\predicate\EachKey.
 *
 * @group  predicate
 * @since  1.3.0
 */
class EachKeyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testNonIterableValueThrowsInvalidArgumentException()
    {
        expect(function() { eachKey(isNotOfType('int'))->test(303); })
                ->throws(\InvalidArgumentException::class);
    }

    /**
     * @test
     */
    function canBeUsedWithCallable()
    {
        assert([303, 313], eachKey('is_int'));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfArrayIsEmpty()
    {
        assertTrue(eachKey(isNotOfType('int'))->test([]));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfTraversableIsEmpty()
    {
        assertTrue(eachKey(isNotOfType('int'))->test(new \ArrayIterator([])));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfEachKeyInArrayFulfillsPredicate()
    {
        assertTrue(eachKey(isNotOfType('int'))->test(['a' => 303, 'b' => 'foo']));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfEachKeyInTraversableFulfillsPredicate()
    {
        assertTrue(
                eachKey(isNotOfType('int'))
                        ->test(new \ArrayIterator(['a' => 303, 'b' => 'foo']))
        );
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfSingleKeyInArrayDoesNotFulfillPredicate()
    {
        assertFalse(eachKey(isNotOfType('int'))->test(['a' => 303, 'foo']));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfSingleValueInTraversableDoesNotFulfillPredicate()
    {
        assertFalse(
                eachKey(isNotOfType('int'))
                        ->test(new \ArrayIterator(['a' =>303, 'foo']))
        );
    }

    /**
     * @test
     */
    public function doesNotMovePointerOfPassedArray()
    {
        $array = [303, 313, 'foo'];
        next($array);
        eachKey(isOfType('int'))->test($array);
        assert(current($array), equals(313));
    }

    /**
     * @test
     */
    public function doesNotMovePointerOfPassedTraversable()
    {
        $traversable = new \ArrayIterator([303, 313, 'foo']);
        $traversable->next();
        eachKey(isOfType('int'))->test($traversable);
        assert($traversable->current(), equals(313));
    }

    /**
     * @test
     */
    public function doesNotMovePointerOfPassedIteratorAggregate()
    {
        $traversable = new IteratorAggregateEachKeyExample();
        $traversable->getIterator()->next();
        eachKey(isOfType('int'))->test($traversable);
        assert($traversable->getIterator()->current(), equals(313));
    }

    /**
     * @test
     */
    public function countReturnsCountOfWrappedPredicate()
    {
        assert(count(eachKey(isGreaterThanOrEqualTo(4))), equals(2));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        expect(function() { assert(['foo'], eachKey(isNotOfType('int'))); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        'Failed asserting that key 0 in Array &0 (
    0 => \'foo\'
) is not of type "int".'
        );
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformationWhenCombined()
    {
        expect(function() {
            assert([], isNotEmpty()->and(eachKey(isNotOfType('int'))));
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that an array is not empty and each key is not of type "int".'
        );
    }
}
