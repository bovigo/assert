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
 * Tests for bovigo\assert\predicate\CallablePredicate.
 *
 * @group  predicate
 */
class CallablePredicateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * helper method for the test
     *
     * @return  bool
     */
    public static function isGood()
    {
        return false;
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 'bar' satisfies bovigo\assert\predicate\CallablePredicateTest::isGood().
     */
    public function assertionFailureContainsMeaningfulInformationWithClassCallable()
    {
        assert('bar', [__CLASS__, 'isGood']);
    }

    public function isGoodEnough()
    {
        return false;
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 'bar' satisfies bovigo\assert\predicate\CallablePredicateTest->isGoodEnough().
     */
    public function assertionFailureContainsMeaningfulInformationWithObjectCallable()
    {
        assert('bar', [$this, 'isGoodEnough']);
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformationWithStringCallable()
    {
        try {
            assert('bar', isNan());
        } catch (AssertionFailure $af) {
            assert(
                    $af->getMessage(),
                    equals('Failed asserting that \'bar\' satisfies is_nan().
is_nan() expects parameter 1 to be float, string given')
            );
        }
    }

    /**
     * @test
     */
    public function isNanUsesCallable()
    {
        assert(isNan()->test(NAN), isTrue());
    }

    /**
     * @test
     */
    public function isFiniteUsesCallable()
    {
        assert(isFinite()->test(1), isTrue());
    }

    /**
     * @test
     */
    public function isInfiniteUsesCallable()
    {
        assert(isInfinite()->test(INF), isTrue());
    }
}
