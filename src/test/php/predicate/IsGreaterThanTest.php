<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
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
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 1 is greater than 2.
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        assert(1, isGreaterThan(2));
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 1 is equal to 2 or is greater than 2.
     */
    public function assertionFailureWhenCombinedWithEqualsContainsMeaningfulInformation()
    {
        assert(1, isGreaterThanOrEqualTo(2));
    }
}
