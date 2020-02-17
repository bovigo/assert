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

use function bovigo\assert\{
    assertThat,
    assertFalse,
    assertTrue,
    expect
};
/**
 * Tests for bovigo\assert\predicate\EachKey.
 *
 * @group  predicate
 * @since  1.3.0
 */
class EachKeyTest extends TestCase
{
    /**
     * @test
     */
    public function testNonIterableValueThrowsInvalidArgumentException(): void
    {
        expect(function() { eachKey(isNotOfType('int'))->test(303); })
                ->throws(\InvalidArgumentException::class);
    }

    /**
     * @test
     */
    function canBeUsedWithCallable(): void
    {
        assertThat([303, 313], eachKey('is_int'));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfArrayIsEmpty(): void
    {
        assertTrue(eachKey(isNotOfType('int'))->test([]));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfTraversableIsEmpty(): void
    {
        assertTrue(eachKey(isNotOfType('int'))->test(new \ArrayIterator([])));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfEachKeyInArrayFulfillsPredicate(): void
    {
        assertTrue(eachKey(isNotOfType('int'))->test(['a' => 303, 'b' => 'foo']));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfEachKeyInTraversableFulfillsPredicate(): void
    {
        assertTrue(
                eachKey(isNotOfType('int'))
                        ->test(new \ArrayIterator(['a' => 303, 'b' => 'foo']))
        );
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfSingleKeyInArrayDoesNotFulfillPredicate(): void
    {
        assertFalse(eachKey(isNotOfType('int'))->test(['a' => 303, 'foo']));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfSingleValueInTraversableDoesNotFulfillPredicate(): void
    {
        assertFalse(
                eachKey(isNotOfType('int'))
                        ->test(new \ArrayIterator(['a' =>303, 'foo']))
        );
    }

    /**
     * @test
     */
    public function doesNotMovePointerOfPassedArray(): void
    {
        $array = [303, 313, 'foo'];
        next($array);
        eachKey(isOfType('int'))->test($array);
        assertThat(current($array), equals(313));
    }

    /**
     * @test
     */
    public function doesNotMovePointerOfPassedTraversable(): void
    {
        $traversable = new \ArrayIterator([303, 313, 'foo']);
        $traversable->next();
        eachKey(isOfType('int'))->test($traversable);
        assertThat($traversable->current(), equals(313));
    }

    /**
     * @test
     */
    public function doesNotMovePointerOfPassedIteratorAggregate(): void
    {
        $traversable = new EachKeyIteratorAggregateExample();
        $traversable->getIterator()->next();
        eachKey(isOfType('int'))->test($traversable);
        assertThat($traversable->getIterator()->current(), equals(313));
    }

    /**
     * @test
     */
    public function countReturnsCountOfWrappedPredicate(): void
    {
        assertThat(count(eachKey(isGreaterThanOrEqualTo(4))), equals(2));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(function() { assertThat(['foo'], eachKey(isNotOfType('int'))); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        'Failed asserting that key 0 in Array &0 ('
                        . PHP_EOL
                        . '    0 => \'foo\''
                        . PHP_EOL
                        . ') is not of type "int".'
        );
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformationWhenCombined(): void
    {
        expect(function() {
            assertThat([], isNotEmpty()->and(eachKey(isNotOfType('int'))));
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that an array is not empty and each key is not of type "int".'
        );
    }
}
