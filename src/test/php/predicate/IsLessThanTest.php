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
 * Tests for bovigo\assert\predicate\IsLessThan.
 *
 * @group  predicate
 */
class IsLessThanTest extends TestCase
{
    /**
     * @test
     */
    public function evaluatesToTrueIfGivenValueIsSmaller()
    {
        assert(isLessThan(3)->test(2), isTrue());
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfGivenValueIsEqual()
    {
        assert(isLessThan(3)->test(3), isFalse());
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfGivenValueIsGreater()
    {
        assert(isLessThan(3)->test(4), isFalse());
    }

    /**
     * @test
     */
    public function evaluatesToTrueWhenCombinedWithEqualsIfGivenValueIsSmaller()
    {
        assert(isLessThanOrEqualTo(3)->test(2), isTrue());
    }

    /**
     * @test
     */
    public function evaluatesToFalseWhenCombinedWithEqualsIfGivenValueIsEqual()
    {
        assert(isLessThanOrEqualTo(3)->test(3), isTrue());
    }

    /**
     * @test
     */
    public function evaluatesToFalseWhenCombinedWithEqualsIfGivenValueIsGreater()
    {
        assert(isLessThanOrEqualTo(3)->test(4), isFalse());
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        expect(function() { assert(3, isLessThan(2)); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that 3 is less than 2.");
    }

    /**
     * @test
     */
    public function assertionFailureWhenCombinedWithEqualsContainsMeaningfulInformation()
    {
        expect(function() { assert(3, isLessThanOrEqualTo(2)); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        "Failed asserting that 3 is equal to 2 or is less than 2."
        );
    }
}
