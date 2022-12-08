<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;

use ArrayIterator;
use bovigo\assert\AssertionFailure;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\Each.
 *
 * @group  predicate
 * @since  1.1.0
 */
class EachTest extends TestCase
{
    /**
     * @test
     */
    public function testNonIterableValueThrowsInvalidArgumentException(): void
    {
        expect(fn() => each(isNull())->test(303))
            ->throws(InvalidArgumentException::class);
    }

    /**
     * @test
     */
    function canBeUsedWithCallable(): void
    {
        assertThat([3.03, 3.13], each('is_finite'));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfArrayIsEmpty(): void
    {
        assertTrue(each(isNotNull())->test([]));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfTraversableIsEmpty(): void
    {
        assertTrue(each(isNotNull())->test(new ArrayIterator([])));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfEachValueInArrayFulfillsPredicate(): void
    {
        assertTrue(each(isNotNull())->test([303, 'foo']));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfEachValueInTraversableFulfillsPredicate(): void
    {
        assertTrue(each(isNotNull())->test(new ArrayIterator([303, 'foo'])));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfSingleValueInArrayDoesNotFulfillPredicate(): void
    {
        assertFalse(each(isNotNull())->test([303, null, 'foo']));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfSingleValueInTraversableDoesNotFulfillPredicate(): void
    {
        assertFalse(each(isNotNull())->test(new ArrayIterator([303, null, 'foo'])));
    }

    /**
     * @test
     */
    public function doesNotMovePointerOfPassedArray(): void
    {
        $array = [303, 313, 'foo'];
        next($array);
        each(isNotNull())->test($array);
        assertThat(current($array), equals(313));
    }

    /**
     * @test
     */
    public function doesNotMovePointerOfPassedTraversable(): void
    {
        $traversable = new ArrayIterator([303, 313, 'foo']);
        $traversable->next();
        each(isNotNull())->test($traversable);
        assertThat($traversable->current(), equals(313));
    }

    /**
     * @test
     */
    public function doesNotMovePointerOfPassedIteratorAggregate(): void
    {
        $traversable = new EachIteratorAggregateExample();
        $traversable->getIterator()->next();
        each(isNotNull())->test($traversable);
        assertThat($traversable->getIterator()->current(), equals(313));
    }

    /**
     * @test
     */
    public function countReturnsCountOfWrappedPredicate(): void
    {
        assertThat(count(each(isGreaterThanOrEqualTo(4))), equals(2));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat(['foo'], each(isNull())))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that element \'foo\' at key 0 in Array &0 (
    0 => \'foo\'
) is null.'
            );
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformationWhenCombined(): void
    {
        expect(fn() => assertThat([], isNotEmpty()->and(each(isNotNull()))))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that an array is not empty and each value is not null.'
            );
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformationOnWhichElementFailed(): void
    {
        expect(fn() => assertThat(['foo', 'bar', null, 'baz'], each(isNotNull())))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that element null at key 2 in Array &0 (
    0 => \'foo\'
    1 => \'bar\'
    2 => null
    3 => \'baz\'
) is not null.'
            );
    }
}
