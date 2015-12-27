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
 * Tests for bovigo\assert\predicate\IsLessThan.
 *
 * @group  predicate
 */
class IsLessThanTest extends \PHPUnit_Framework_TestCase
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
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 3 is less than 2.
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        assert(3, isLessThan(2));
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 3 is equal to 2 or is less than 2.
     */
    public function assertionFailureWhenCombinedWithEqualsContainsMeaningfulInformation()
    {
        assert(3, isLessThanOrEqualTo(2));
    }
}
