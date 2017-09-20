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

use function bovigo\assert\assert;
use function bovigo\assert\expect;
/**
 * Helper class for the test.
 */
class FooPredicate extends Predicate
{
    /**
     * evaluates predicate against given value
     *
     * @param   mixed  $value
     * @return  bool
     */
    public function test($value): bool
    {
        return 'foo' === $value;
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString(): string
    {
        return 'is foo';
    }
}
/**
 * Helper class for the test.
 */
class ThrowingPredicate extends FooPredicate
{
    public function test($value): bool
    {
        throw new \InvalidArgumentException('exception message');
    }
}
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
    public function castFromWithPredicateReturnsInstance()
    {
        $predicate = new FooPredicate();
        assert($predicate, isSameAs(Predicate::castFrom($predicate)));
    }

    /**
     * @test
     */
    public function castFromWithCallableReturnsCallablePredicate()
    {
        assert(
                Predicate::castFrom(function($value) { return 'foo' === $value; }),
                isInstanceOf(CallablePredicate::class)
        );
    }

    /**
     * @test
     */
    public function predicateIsCallable()
    {
        $predicate = new FooPredicate();
        assert($predicate('foo'), isTrue());
    }

    /**
     * @test
     */
    public function andReturnsAndPredicate()
    {
        $predicate = new FooPredicate();
        assert(
                $predicate->and(function($value) { return 'foo' === $value; }),
                isInstanceOf(AndPredicate::class)
        );
    }

    /**
     * @test
     */
    public function orReturnsOrPredicate()
    {
        $predicate = new FooPredicate();
        assert(
                $predicate->or(function($value) { return 'foo' === $value; }),
                isInstanceOf(OrPredicate::class)
        );
    }

    /**
     * @test
     * @since  1.4.0
     */
    public function everyPredicateCanBeNegated()
    {
        $isNotFoo = not(new FooPredicate());
        assert('bar', $isNotFoo);
    }

    /**
     * @test
     */
    public function defaultCountOfPredicateIs1()
    {
        assert(count(new FooPredicate()), equals(1));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        expect(function() { assert([], new FooPredicate()); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that an array is foo.");
    }

    /**
     * @test
     */
    public function assertionFailureNegatedContainsMeaningfulInformation()
    {
        expect(function() { assert('foo', not(new FooPredicate())); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that 'foo' is not foo.");
    }

    /**
     * @test
     */
    public function assertionFailureNegatedContainsMeaningfulInformationWithDescription()
    {
        expect(function() {
                assert([], new FooPredicate(), 'some useful description');
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
    public function assertionFailureNegatedContainsMeaningfulInformationWithDescriptionAndExceptionMessage()
    {
        expect(function() {
                assert([], new ThrowingPredicate(), 'some useful description');
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
    public function callToUndefinedMethodThrowsBadMethodCallException()
    {
        expect([new FooPredicate(), 'noWay'])
                ->throws(\BadMethodCallException::class);
    }
}
