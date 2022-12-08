<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;

use Generator;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
/**
 * Test for bovigo\assert\predicate\NegatePredicate.
 *
 * @group  predicate
 */
class NegatePredicateTest extends TestCase
{
    /**
     * @var  NegatePredicate
     */
    private $negatePredicate;

    protected function setUp(): void
    {
        $this->negatePredicate = not(fn($value) => 'foo' === $value);
    }
    /**
     * @test
     */
    public function negatesWrappedPredicate(): void
    {
        assertTrue($this->negatePredicate->test('bar'));
    }

    public function predicates(): Generator
    {
        yield [not(fn($value) => 'foo' === $value), 'does not satisfy a lambda function'];
        yield [not(equals(5)->or(isLessThan(5))), 'not (is equal to 5 or is less than 5)'];
        yield [not(equals(5)->and(isLessThan(5))), 'not (is equal to 5 and is less than 5)'];
        yield [not(not(equals(5))), 'not (is not equal to 5)'];
    }

    /**
     * @test
     * @dataProvider  predicates
     */
    public function hasStringRepresentation(NegatePredicate $negatePredicate, string $expected): void
    {
        assertThat((string) $negatePredicate, equals($expected));
    }

    /**
     * @test
     */
    public function countEqualsCountOfNegatedPredicate(): void
    {
        assertThat(
            count(not(new AndPredicate(function() {}, function() {}))),
            equals(2)
        );
    }
}
