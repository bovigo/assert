<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use function bovigo\assert\assert;
/**
 * Test for bovigo\assert\predicate\NegatePredicate.
 *
 * @group  predicate
 */
class NegatePredicateTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @type  \bovigo\assert\predicate\NegatePredicate
     */
    private $negatePredicate;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->negatePredicate = not(
                function($value) { return 'foo' === $value; }
        );
    }
    /**
     * @test
     */
    public function negatesWrappedPredicate()
    {
        assert($this->negatePredicate->test('bar'), isTrue());
    }

    public function predicates(): array
    {
        return [
            [not(function($value) { return 'foo' === $value; }), 'does not satisfy a lambda function'],
            [not(equals(5)->or(isLessThan(5))), 'not (is equal to 5 or is less than 5)'],
            [not(equals(5)->and(isLessThan(5))), 'not (is equal to 5 and is less than 5)'],
            [not(not(equals(5))), 'not (is not equal to 5)']
        ];
    }

    /**
     * @test
     * @dataProvider  predicates
     */
    public function hasStringRepresentation(NegatePredicate $negatePredicate, $expected)
    {
        assert((string) $negatePredicate, equals($expected));
    }

    /**
     * @test
     */
    public function countEqualsCountOfNegatedPredicate()
    {
        assert(
                count(not(new AndPredicate(function() {}, function() {}))),
                equals(2)
        );
    }
}
