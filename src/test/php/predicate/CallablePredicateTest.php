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

use function bovigo\assert\assertThat;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\CallablePredicate.
 *
 * @group  predicate
 */
class CallablePredicateTest extends TestCase
{
    /**
     * helper method for the test
     *
     * @return  bool
     */
    public static function isGood(): bool
    {
        return false;
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformationWithClassCallable()
    {
        expect(function() { assertThat('bar', [__CLASS__, 'isGood']); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        "Failed asserting that 'bar' satisfies "
                        . __CLASS__ . "::isGood()."
        );
    }

    public function isGoodEnough(): bool
    {
        return false;
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformationWithObjectCallable()
    {
        expect(function() { assertThat('bar', [$this, 'isGoodEnough']); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        "Failed asserting that 'bar' satisfies "
                        . __CLASS__ . "->isGoodEnough()."
        );
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformationWithStringCallable()
    {
        expect(function() { assertThat('bar', 'is_int'); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that 'bar' satisfies is_int().");
    }

    /**
     * @test
     * @since  1.2.0
     */
    public function assertionFailureContainsNonDefaultDescriptionWhenPassed()
    {
        expect(function() {
            assertThat(
                    'bar',
                    new CallablePredicate(
                            [$this, 'isGoodEnough'],
                            'is good enough for us'
                    )
            );
        })
        ->throws(AssertionFailure::class)
        ->withMessage("Failed asserting that 'bar' is good enough for us.");
    }
}
