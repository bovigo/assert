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
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
use function bovigo\assert\expect;
/**
 * Test for bovigo\assert\predicate\Predicate.
 */
#[Group('predicate')]
class PredicateTest extends TestCase
{
    #[Test]
    public function castFromWithPredicateReturnsInstance(): void
    {
        $predicate = new PredicateFooExample();
        assertThat($predicate, isSameAs(Predicate::castFrom($predicate)));
    }

    #[Test]
    public function castFromWithCallableReturnsCallablePredicate(): void
    {
        assertThat(
            Predicate::castFrom(function($value) { return 'foo' === $value; }),
            isInstanceOf(CallablePredicate::class)
        );
    }

    #[Test]
    public function predicateIsCallable(): void
    {
        $predicate = new PredicateFooExample();
        assertTrue($predicate('foo'));
    }

    #[Test]
    public function andReturnsAndPredicate(): void
    {
        $predicate = new PredicateFooExample();
        assertThat(
            $predicate->and(function($value) { return 'foo' === $value; }),
            isInstanceOf(AndPredicate::class)
        );
    }

    #[Test]
    public function orReturnsOrPredicate(): void
    {
        $predicate = new PredicateFooExample();
        assertThat(
            $predicate->or(function($value) { return 'foo' === $value; }),
            isInstanceOf(OrPredicate::class)
        );
    }

    /**
     * @since 1.4.0
     */
    #[Test]
    public function everyPredicateCanBeNegated(): void
    {
        $isNotFoo = not(new PredicateFooExample());
        assertThat('bar', $isNotFoo);
    }

    #[Test]
    public function defaultCountOfPredicateIs1(): void
    {
        assertThat(count(new PredicateFooExample()), equals(1));
    }

    #[Test]
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat([], new PredicateFooExample()))
            ->throws(AssertionFailure::class)
            ->withMessage("Failed asserting that an array is foo.");
    }

    #[Test]
    public function assertionFailureNegatedContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat('foo', not(new PredicateFooExample())))
            ->throws(AssertionFailure::class)
            ->withMessage("Failed asserting that 'foo' is not foo.");
    }

    #[Test]
    public function assertionFailureNegatedContainsMeaningfulInformationWithDescription(): void
    {
        expect(fn() => assertThat([], new PredicateFooExample(), 'some useful description'))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that an array is foo.
some useful description'
            );
    }

    #[Test]
    public function assertionFailureNegatedContainsMeaningfulInformationWithDescriptionAndExceptionMessage(): void
    {
        expect(fn() => assertThat([], new PredicateThrowingExample(), 'some useful description'))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that an array is foo.
some useful description
exception message'
            );
    }

    /**
     * @since 1.4.0
     */
    #[Test]
    public function callToUndefinedMethodThrowsBadMethodCallException(): void
    {
        expect([new PredicateFooExample(), 'noWay'])
            ->throws(\BadMethodCallException::class);
    }
}
