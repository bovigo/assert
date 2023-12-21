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
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\{
    assertThat,
    assertFalse,
    assertTrue,
    expect
};
/**
 * Tests for bovigo\assert\predicate\EachKey.
 *
 * @since 1.3.0
 */
#[Group('predicate')]
class EachKeyTest extends TestCase
{
    #[Test]
    public function testNonIterableValueThrowsInvalidArgumentException(): void
    {
        expect(fn() => eachKey(isNotOfType('int'))->test(303))
            ->throws(InvalidArgumentException::class);
    }

    #[Test]
    function canBeUsedWithCallable(): void
    {
        assertThat([303, 313], eachKey('is_int'));
    }

    #[Test]
    public function evaluatesToTrueIfArrayIsEmpty(): void
    {
        assertTrue(eachKey(isNotOfType('int'))->test([]));
    }

    #[Test]
    public function evaluatesToTrueIfTraversableIsEmpty(): void
    {
        assertTrue(eachKey(isNotOfType('int'))->test(new ArrayIterator([])));
    }

    #[Test]
    public function evaluatesToTrueIfEachKeyInArrayFulfillsPredicate(): void
    {
        assertTrue(eachKey(isNotOfType('int'))->test(['a' => 303, 'b' => 'foo']));
    }

    #[Test]
    public function evaluatesToTrueIfEachKeyInTraversableFulfillsPredicate(): void
    {
        assertTrue(
            eachKey(isNotOfType('int'))
                ->test(new ArrayIterator(['a' => 303, 'b' => 'foo']))
        );
    }

    #[Test]
    public function evaluatesToFalseIfSingleKeyInArrayDoesNotFulfillPredicate(): void
    {
        assertFalse(eachKey(isNotOfType('int'))->test(['a' => 303, 'foo']));
    }

    #[Test]
    public function evaluatesToFalseIfSingleValueInTraversableDoesNotFulfillPredicate(): void
    {
        assertFalse(
            eachKey(isNotOfType('int'))
                ->test(new \ArrayIterator(['a' =>303, 'foo']))
        );
    }

    #[Test]
    public function doesNotMovePointerOfPassedArray(): void
    {
        $array = [303, 313, 'foo'];
        next($array);
        eachKey(isOfType('int'))->test($array);
        assertThat(current($array), equals(313));
    }

    #[Test]
    public function doesNotMovePointerOfPassedTraversable(): void
    {
        $traversable = new ArrayIterator([303, 313, 'foo']);
        $traversable->next();
        eachKey(isOfType('int'))->test($traversable);
        assertThat($traversable->current(), equals(313));
    }

    #[Test]
    public function doesNotMovePointerOfPassedIteratorAggregate(): void
    {
        $traversable = new EachKeyIteratorAggregateExample();
        $traversable->getIterator()->next();
        eachKey(isOfType('int'))->test($traversable);
        assertThat($traversable->getIterator()->current(), equals(313));
    }

    #[Test]
    public function countReturnsCountOfWrappedPredicate(): void
    {
        assertThat(count(eachKey(isGreaterThanOrEqualTo(4))), equals(2));
    }

    #[Test]
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat(['foo'], eachKey(isNotOfType('int'))))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that key 0 in Array &0 [
    0 => \'foo\',
] is not of type "int".'
            );
    }

    #[Test]
    public function assertionFailureContainsMeaningfulInformationWhenCombined(): void
    {
        expect(fn() => assertThat([], isNotEmpty()->and(eachKey(isNotOfType('int')))))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that an array is not empty and each key is not of type "int".'
            );
    }
}
