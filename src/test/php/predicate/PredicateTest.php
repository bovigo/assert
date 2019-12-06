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
use function bovigo\assert\assertTrue;
use function bovigo\assert\expect;
/**
 * Test for bovigo\assert\predicate\Predicate.
 *
 * @group  predicate
 */
class PredicateTest extends TestCase
{
    /**
     * @test
     */
    public function castFromWithPredicateReturnsInstance(): void
    {
        $predicate = new PredicateFooExample();
        assertThat($predicate, isSameAs(Predicate::castFrom($predicate)));
    }

    /**
     * @test
     */
    public function castFromWithCallableReturnsCallablePredicate(): void
    {
        assertThat(
                Predicate::castFrom(function($value) { return 'foo' === $value; }),
                isInstanceOf(CallablePredicate::class)
        );
    }

    /**
     * @test
     */
    public function predicateIsCallable(): void
    {
        $predicate = new PredicateFooExample();
        assertTrue($predicate('foo'));
    }

    /**
     * @test
     */
    public function andReturnsAndPredicate(): void
    {
        $predicate = new PredicateFooExample();
        assertThat(
                $predicate->and(function($value) { return 'foo' === $value; }),
                isInstanceOf(AndPredicate::class)
        );
    }

    /**
     * @test
     */
    public function orReturnsOrPredicate(): void
    {
        $predicate = new PredicateFooExample();
        assertThat(
                $predicate->or(function($value) { return 'foo' === $value; }),
                isInstanceOf(OrPredicate::class)
        );
    }

    /**
     * @test
     * @since  1.4.0
     */
    public function everyPredicateCanBeNegated(): void
    {
        $isNotFoo = not(new PredicateFooExample());
        assertThat('bar', $isNotFoo);
    }

    /**
     * @test
     */
    public function defaultCountOfPredicateIs1(): void
    {
        assertThat(count(new PredicateFooExample()), equals(1));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(function() { assertThat([], new PredicateFooExample()); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that an array is foo.");
    }

    /**
     * @test
     */
    public function assertionFailureNegatedContainsMeaningfulInformation(): void
    {
        expect(function() { assertThat('foo', not(new PredicateFooExample())); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that 'foo' is not foo.");
    }

    /**
     * @test
     */
    public function assertionFailureNegatedContainsMeaningfulInformationWithDescription(): void
    {
        expect(function() {
                assertThat([], new PredicateFooExample(), 'some useful description');
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that an array is foo.
some useful description'
        );
    }

    /**
     * @test
     */
    public function assertionFailureNegatedContainsMeaningfulInformationWithDescriptionAndExceptionMessage(): void
    {
        expect(function() {
                assertThat([], new PredicateThrowingExample(), 'some useful description');
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that an array is foo.
some useful description
exception message'
        );
    }

    /**
     * @test
     * @since  1.4.0
     */
    public function callToUndefinedMethodThrowsBadMethodCallException(): void
    {
        expect([new PredicateFooExample(), 'noWay'])
                ->throws(\BadMethodCallException::class);
    }
}
