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
}
