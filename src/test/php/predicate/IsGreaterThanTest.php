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
/**
 * Tests for bovigo\assert\predicate\IsGreaterThan.
 *
 * @group  predicate
 */
class IsGreaterThanTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function evaluatesToTrueIfGivenValueIsGreater()
    {
        assert(isGreaterThan(3)->test(4), isTrue());
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfGivenValueIsEqual()
    {
        assert(isGreaterThan(3)->test(3), isFalse());
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfGivenValueIsLesser()
    {
        assert(isGreaterThan(3)->test(2), isFalse());
    }

    /**
     * @test
     */
    public function evaluatesToTrueWhenCombinedWithEqualsAndGivenValueIsEqual()
    {
        assert(isGreaterThanOrEqualTo(3)->test(3), isTrue());
    }

    /**
     * @test
     */
    public function evaluatesToTrueWhenCombinedWithEqualsIfGivenValueIsGreater()
    {
        assert(isGreaterThanOrEqualTo(3)->test(4), isTrue());
    }

    /**
     * @test
     */
    public function evaluatesToFalseWhenCombinedWithEqualsIfGivenValueIsLesser()
    {
        assert(isGreaterThanOrEqualTo(3)->test(2), isFalse());
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        assert(
                function() { assert(1, isGreaterThan(2)); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that 1 is greater than 2."
                )
        );
    }

    /**
     * @test
     */
    public function assertionFailureWhenCombinedWithEqualsContainsMeaningfulInformation()
    {
        assert(
                function() { assert(1, isGreaterThanOrEqualTo(2)); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that 1 is equal to 2 or is greater than 2."
                )
        );
    }
}
