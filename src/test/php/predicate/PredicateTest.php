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
    public function test($value)
    {
        return 'foo' === $value;
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString()
    {
        return 'is foo';
    }
}
/**
 * Helper class for the test.
 */
class ThrowingPredicate extends FooPredicate
{
    public function test($value)
    {
        throw new \InvalidArgumentException('exception message');
    }
}
/**
 * Test for bovigo\assert\predicate\Predicate.
 *
 * @group  predicate
 */
class PredicateTest extends \PHPUnit_Framework_TestCase
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
     * @expectedException  InvalidArgumentException
     */
    public function castFromWithOtherValueThrowsIllegalArgumentException()
    {
        Predicate::castFrom(new \stdClass());
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
    public function asWellAsReturnsAndPredicate()
    {
        $predicate = new FooPredicate();
        assert(
                $predicate->asWellAs(function($value) { return 'foo' === $value; }),
                isInstanceOf(AndPredicate::class)
        );
    }

    /**
     * @test
     */
    public function orElseReturnsOrPredicate()
    {
        $predicate = new FooPredicate();
        assert(
                $predicate->orElse(function($value) { return 'foo' === $value; }),
                isInstanceOf(OrPredicate::class)
        );
    }

    /**
     * @test
     */
    public function negateReturnsNegatePredicate()
    {
        $predicate = new FooPredicate();
        assert($predicate->negate(), isInstanceOf(NegatePredicate::class));
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
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that an array is foo.
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        assert([], new FooPredicate());
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 'foo' is not foo.
     */
    public function assertionFailureNegatedContainsMeaningfulInformation()
    {
        assert('foo', (new FooPredicate())->negate());
    }

    /**
     * @test
     */
    public function assertionFailureNegatedContainsMeaningfulInformationWithDescription()
    {
        try {
            assert([], new FooPredicate(), 'some useful description');
            $this->fail('Expected ' . AssertionFailure::class . ', got none');
        } catch (AssertionFailure $af) {
            assert(
                    $af->getMessage(),
                    equals('Failed asserting that an array is foo.
some useful description')
            );
        }
    }

    /**
     * @test
     */
    public function assertionFailureNegatedContainsMeaningfulInformationWithDescriptionAndExceptionMessage()
    {
        try {
            assert([], new ThrowingPredicate(), 'some useful description');
            $this->fail('Expected ' . AssertionFailure::class . ', got none');
        } catch (AssertionFailure $af) {
            assert(
                    $af->getMessage(),
                    equals('Failed asserting that an array is foo.
some useful description
exception message')
            );
        }
    }
}
