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
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 'bar' satisfies is_nan().
     */
    public function assertionFailureContainsMeaningfulInformationWithStringCallable()
    {
        assert('bar', 'is_nan');
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 'bar' is good enough for us.
     * @since  1.2.0
     */
    public function assertionFailureContainsNonDefaultDescriptionWhenPassed()
    {
        assert(
                'bar',
                new CallablePredicate(
                        [$this, 'isGoodEnough'],
                        'is good enough for us'
                )
        );
    }
}
