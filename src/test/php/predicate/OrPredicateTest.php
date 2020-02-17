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

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
use function bovigo\assert\expect;
/**
 * Test for bovigo\assert\predicate\OrPredicate.
 *
 * @group  predicate
 */
class OrPredicateTest extends TestCase
{
    /**
     * instance to test
     *
     * @var  OrPredicate
     */
    private $orPredicate;

    protected function setUp(): void
    {
        $this->orPredicate = new OrPredicate(
                function($value) { return 'bar' === $value; },
                function($value) { return 'foo' === $value; }
        );
    }

    /**
     * @test
     */
    public function returnsTrueWhenOnePredicateReturnsTrue(): void
    {
        assertTrue($this->orPredicate->test('foo'));
    }

    /**
     * @test
     */
    public function returnsFalseWhenBothPredicatesReturnsFalse(): void
    {
        assertFalse($this->orPredicate->test('baz'));
    }

    /**
     * @test
     */
    public function returnsTrueWhenFirstPredicateThrowsExceptionButOtherSucceeds(): void
    {
        assertTrue(assertThat(null, matches('/^([a-z]{3})$/')->or(isNull())));
    }

    /**
     * @test
     */
    public function doesNotSwallowExceptionFromFirstPredicateIfOtherFails(): void
    {
        expect(function() {
                assertThat(303, matches('/^([a-z]{3})$/')->or(isNull()));
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that 303 matches regular expression "/^([a-z]{3})$/" or is null.'
                . PHP_EOL
                . 'Given value of type "integer" can not be matched against a regular expression.'
        );
    }

    /**
     * @test
     */
    public function doesNotSwallowExceptionFromSecondPredicateIfFirstFails(): void
    {
        expect(function() {
                assertThat(303, isNull()->or(matches('/^([a-z]{3})$/')));
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that 303 is null or matches regular expression "/^([a-z]{3})$/".'
                . PHP_EOL
                . 'Given value of type "integer" can not be matched against a regular expression.'
        );
    }

    /**
     * @test
     */
    public function doesNotSwallowBothExceptionsWhenBothPredicatesFail(): void
    {
        expect(function() {
                assertThat(303, matches('/^([a-z]{3})$/')->or(contains('dummy')));
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that 303 matches regular expression "/^([a-z]{3})$/" or contains \'dummy\'.'
                . PHP_EOL
                . 'Given value of type "integer" can not be matched against a regular expression.'
                . PHP_EOL
                . 'Given value of type "integer" can not contain something.'
        );
    }

    /**
     * @test
     */
    public function hasStringRepresentation(): void
    {
        assertThat(
                $this->orPredicate,
                equals('satisfies a lambda function or satisfies a lambda function')
        );
    }

    /**
     * @test
     */
    public function countEqualsSumOfCountOfBothPredicates(): void
    {
        assertThat(
                count(new OrPredicate(
                        new AndPredicate(
                            function($value) { return 'foo' === $value; },
                            function($value) { return 'foo' === $value; }
                        ),
                        function($value) { return 'bar' === $value; }
                )),
                equals(3)
        );
    }
}
