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

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\IsOfSize.
 *
 * @group  predicate
 */
class IsOfSizeTest extends TestCase
{
    /**
     * @test
     */
    public function evaluatesToTrueIfStringLengthMatchesExpectedSize(): void
    {
        assertTrue(isOfSize(3)->test('foo'));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfStringLengthDoesNotMatchExpectedSize(): void
    {
        assertFalse(isOfSize(4)->test('foo'));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfArraySizeMatchesExpectedSize(): void
    {
        assertTrue(isOfSize(3)->test([1, 2, 3]));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfArraySizeDoesNotMatchExpectedSize(): void
    {
        assertFalse(isOfSize(4)->test([1, 2, 3]));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfCountableSizeMatchesExpectedSize(): void
    {
        assertTrue(isOfSize(3)->test(new IsOfSizeCountableExample()));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfCountableSizeDoesNotMatchExpectedSize(): void
    {
        assertFalse(isOfSize(4)->test(new IsOfSizeCountableExample()));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfTraversableSizeMatchesExpectedSize(): void
    {
        assertTrue(isOfSize(3)->test(new IsOfSizeTraversableExample()));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfTraversableSizeDoesNotMatchExpectedSize(): void
    {
        assertFalse(isOfSize(4)->test(new IsOfSizeTraversableExample()));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfIteratorAggregateSizeMatchesExpectedSize(): void
    {
        assertTrue(isOfSize(3)->test(new IsOfSizeIteratorAggregateExample()));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfIteratorAggregateSizeDoesNotMatchExpectedSize(): void
    {
        assertFalse(isOfSize(4)->test(new IsOfSizeIteratorAggregateExample()));
    }

    /**
     * @test
     */
    public function iteratorPointerIsNotChangedByEvaluationForTraversable(): void
    {
        $example = new IsOfSizeTraversableExample();
        $example->next();
        isOfSize(3)->test($example);
        assertThat($example->current(), equals(1));
    }

    /**
     * @test
     */
    public function throwsInvalidArgumentExceptionWhenValueIsNotTestableForSize(): void
    {
        expect(function() { isOfSize(3)->test(true); })
                ->throws(\InvalidArgumentException::class);
    }

    /**
     * @test
     */
    public function assertionFailureWithStringContainsMeaningfulInformation(): void
    {
        expect(function() { assertThat('foo', isOfSize(4)); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        "Failed asserting that string with actual size 3 matches expected size 4."
        );
    }

    /**
     * @test
     */
    public function assertionFailureWithArrayContainsMeaningfulInformation(): void
    {
        expect(function() { assertThat([], isOfSize(4)); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        "Failed asserting that array with actual size 0 matches expected size 4."
        );
    }

    /**
     * @test
     */
    public function assertionFailureWithCountableContainsMeaningfulInformation(): void
    {
        expect(function() { assertThat(new IsOfSizeCountableExample(), isOfSize(4)); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        "Failed asserting that instance of type "
                        . IsOfSizeCountableExample::class
                        . " with actual size 3 matches expected size 4."
        );
    }

    /**
     * @test
     */
    public function assertionFailureWithTraversableContainsMeaningfulInformation(): void
    {
        expect(function() { assertThat(new IsOfSizeTraversableExample(), isOfSize(4)); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        "Failed asserting that instance of type "
                        . IsOfSizeTraversableExample::class
                        . " with actual size 3 matches expected size 4."
        );
    }

    /**
     * @test
     */
    public function assertionFailureWithIteratorAggregateContainsMeaningfulInformation(): void
    {
        expect(function() {
            assertThat(new IsOfSizeIteratorAggregateExample(), isOfSize(4));
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                "Failed asserting that instance of type "
                . IsOfSizeIteratorAggregateExample::class
                . " with actual size 3 matches expected size 4."
        );
    }
}
