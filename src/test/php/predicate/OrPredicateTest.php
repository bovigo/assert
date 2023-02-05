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
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
use function bovigo\assert\expect;
/**
 * Test for bovigo\assert\predicate\OrPredicate.
 *
 * @group predicate
 */
class OrPredicateTest extends TestCase
{
    private OrPredicate $orPredicate;

    protected function setUp(): void
    {
        $this->orPredicate = new OrPredicate(
                fn($value) => 'bar' === $value,
                fn($value) => 'foo' === $value
        );
    }

    #[Test]
    public function returnsTrueWhenOnePredicateReturnsTrue(): void
    {
        assertTrue($this->orPredicate->test('foo'));
    }

    #[Test]
    public function returnsFalseWhenBothPredicatesReturnsFalse(): void
    {
        assertFalse($this->orPredicate->test('baz'));
    }

    #[Test]
    public function returnsTrueWhenFirstPredicateThrowsExceptionButOtherSucceeds(): void
    {
        assertTrue(assertThat(null, matches('/^([a-z]{3})$/')->or(isNull())));
    }

    #[Test]
    public function doesNotSwallowExceptionFromFirstPredicateIfOtherFails(): void
    {
        expect(fn() => assertThat(303, matches('/^([a-z]{3})$/')->or(isNull())))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that 303 matches regular expression "/^([a-z]{3})$/" or is null.
Given value of type "integer" can not be matched against a regular expression.'
            );
    }

    #[Test]
    public function doesNotSwallowExceptionFromSecondPredicateIfFirstFails(): void
    {
        expect(fn() => assertThat(303, isNull()->or(matches('/^([a-z]{3})$/'))))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that 303 is null or matches regular expression "/^([a-z]{3})$/".
Given value of type "integer" can not be matched against a regular expression.'
            );
    }

    #[Test]
    public function doesNotSwallowBothExceptionsWhenBothPredicatesFail(): void
    {
        expect(fn() => assertThat(303, matches('/^([a-z]{3})$/')->or(contains('dummy'))))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that 303 matches regular expression "/^([a-z]{3})$/" or contains \'dummy\'.
Given value of type "integer" can not be matched against a regular expression.
Given value of type "integer" can not contain something.'
            );
    }

    #[Test]
    public function hasStringRepresentation(): void
    {
        assertThat(
            $this->orPredicate,
            equals('satisfies a lambda function or satisfies a lambda function')
        );
    }

    #[Test]
    public function countEqualsSumOfCountOfBothPredicates(): void
    {
        assertThat(
            count(new OrPredicate(
                new AndPredicate(
                    fn($value) => 'foo' === $value,
                    fn($value) => 'foo' === $value
                ),
                fn($value) => 'bar' === $value
            )),
            equals(3)
        );
    }
}
