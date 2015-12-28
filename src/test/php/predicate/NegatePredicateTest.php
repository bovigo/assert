<?php
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
        $this->negatePredicate = new NegatePredicate(
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

    /**
     * @test
     */
    public function hasStringRepresentation()
    {
        assert((string) $this->negatePredicate, equals('does not satisfy a lambda function'));
    }

    /**
     * @test
     */
    public function countEqualsCountOfNegatedPredicate()
    {
        assert(
                count(new NegatePredicate(
                        new AndPredicate(function() {}, function() {})
                )),
                equals(2)
        );
    }
}
