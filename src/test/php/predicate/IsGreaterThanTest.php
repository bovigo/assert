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
 * Tests for bovigo\assert\predicate\IsGreaterThan.
 *
 * @group  predicate
 */
class IsGreaterThanTest extends TestCase
{
    /**
     * @test
     */
    public function evaluatesToTrueIfGivenValueIsGreater(): void
    {
        assertTrue(isGreaterThan(3)->test(4));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfGivenValueIsEqual(): void
    {
        assertFalse(isGreaterThan(3)->test(3));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfGivenValueIsLesser(): void
    {
        assertFalse(isGreaterThan(3)->test(2));
    }

    /**
     * @test
     */
    public function evaluatesToTrueWhenCombinedWithEqualsAndGivenValueIsEqual(): void
    {
        assertTrue(isGreaterThanOrEqualTo(3)->test(3));
    }

    /**
     * @test
     */
    public function evaluatesToTrueWhenCombinedWithEqualsIfGivenValueIsGreater(): void
    {
        assertTrue(isGreaterThanOrEqualTo(3)->test(4));
    }

    /**
     * @test
     */
    public function evaluatesToFalseWhenCombinedWithEqualsIfGivenValueIsLesser(): void
    {
        assertFalse(isGreaterThanOrEqualTo(3)->test(2));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat(1, isGreaterThan(2)))
            ->throws(AssertionFailure::class)
            ->withMessage("Failed asserting that 1 is greater than 2.");
    }

    /**
     * @test
     */
    public function assertionFailureWhenCombinedWithEqualsContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat(1, isGreaterThanOrEqualTo(2)))
            ->throws(AssertionFailure::class)
            ->withMessage("Failed asserting that 1 is equal to 2 or is greater than 2.");
    }
}
