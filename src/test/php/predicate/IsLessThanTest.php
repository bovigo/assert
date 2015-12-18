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
}
