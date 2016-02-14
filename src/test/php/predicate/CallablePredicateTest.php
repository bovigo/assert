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
     */
    public function assertionFailureContainsMeaningfulInformationWithClassCallable()
    {
        assert(
                function() { assert('bar', [__CLASS__, 'isGood']); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that 'bar' satisfies " . __CLASS__ . "::isGood()."
                )
        );
    }

    public function isGoodEnough()
    {
        return false;
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformationWithObjectCallable()
    {
        assert(
                function() { assert('bar', [$this, 'isGoodEnough']); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that 'bar' satisfies " . __CLASS__ . "->isGoodEnough()."
                )
        );
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformationWithStringCallable()
    {
        assert(
                function() { assert('bar', 'is_nan'); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that 'bar' satisfies is_nan()."
                )
        );
    }

    /**
     * @test
     * @since  1.2.0
     */
    public function assertionFailureContainsNonDefaultDescriptionWhenPassed()
    {
        assert(
                function()
                {
                    assert(
                            'bar',
                            new CallablePredicate(
                                    [$this, 'isGoodEnough'],
                                    'is good enough for us'
                            )
                    );
                },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that 'bar' is good enough for us."
                )
        );
    }
}
