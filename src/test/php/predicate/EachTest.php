<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use bovigo\assert\AssertionFailure;

use function bovigo\assert\assert;
use function bovigo\assert\expect;
/**
 * Helper class for the test.
 */
class IteratorAggregateEachExample implements \IteratorAggregate
{
    private $iterator;

    public function __construct()
    {
        $this->iterator = new \ArrayIterator([303, 313, 'foo']);
    }
    public function getIterator()
    {
        return $this->iterator;
    }
}
/**
 * Tests for bovigo\assert\predicate\Each.
 *
 * @group  predicate
 * @since  1.1.0
 */
class EachTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testNonIterableValueThrowsInvalidArgumentException()
    {
        expect(function() { each(isNull())->test(303); })
                ->throws(\InvalidArgumentException::class);

    }

    /**
     * @test
     */
    function canBeUsedWithCallable()
    {
        assert([303, 313], each('is_finite'));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfArrayIsEmpty()
    {
        assert(each(isNotNull())->test([]), isTrue());
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfTraversableIsEmpty()
    {
        assert(each(isNotNull())->test(new \ArrayIterator([])), isTrue());
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfEachValueInArrayFulfillsPredicate()
    {
        assert(each(isNotNull())->test([303, 'foo']), isTrue());
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfEachValueInTraversableFulfillsPredicate()
    {
        assert(
                each(isNotNull())->test(new \ArrayIterator([303, 'foo'])),
                isTrue()
        );
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfSingleValueInArrayDoesNotFulfillPredicate()
    {
        assert(each(isNotNull())->test([303, null, 'foo']), isFalse());
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfSingleValueInTraversableDoesNotFulfillPredicate()
    {
        assert(
                each(isNotNull())->test(new \ArrayIterator([303, null, 'foo'])),
                isFalse()
        );
    }

    /**
     * @test
     */
    public function doesNotMovePointerOfPassedArray()
    {
        $array = [303, 313, 'foo'];
        next($array);
        each(isNotNull())->test($array);
        assert(current($array), equals(313));
    }

    /**
     * @test
     */
    public function doesNotMovePointerOfPassedTraversable()
    {
        $traversable = new \ArrayIterator([303, 313, 'foo']);
        $traversable->next();
        each(isNotNull())->test($traversable);
        assert($traversable->current(), equals(313));
    }

    /**
     * @test
     */
    public function doesNotMovePointerOfPassedIteratorAggregate()
    {
        $traversable = new IteratorAggregateEachExample();
        $traversable->getIterator()->next();
        each(isNotNull())->test($traversable);
        assert($traversable->getIterator()->current(), equals(313));
    }

    /**
     * @test
     */
    public function countReturnsCountOfWrappedPredicate()
    {
        assert(count(each(isGreaterThanOrEqualTo(4))), equals(2));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        expect(function() { assert(['foo'], each(isNull())); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        'Failed asserting that in Array &0 (
    0 => \'foo\'
) each value is null.'
        );
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformationWhenCombined()
    {
        expect(function() {
            assert([], isNotEmpty()->and(each(isNotNull())));
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that an array is not empty and each value is not null.'
        );
    }
}
