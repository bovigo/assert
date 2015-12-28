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
 * Test for bovigo\assert\assert\predicate\AndPredicate.
 *
 * @group  predicate
 */
class AndPredicateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @type  AndPredicate
     */
    private $andPredicate;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->andPredicate = new AndPredicate(
                function($value) { return 'foo' === $value; },
                function($value) { return 'foo' === $value; }
        );
    }

    /**
     * @test
     */
    public function returnsTrueWhenBothPredicatesReturnsTrue()
    {
        assert($this->andPredicate->test('foo'), isTrue());
    }

    /**
     * @test
     */
    public function returnsFalseWhenOnePredicateReturnsFalse()
    {
        assert($this->andPredicate->test('baz'), isFalse());
    }

    /**
     * @test
     */
    public function hasStringRepresentation()
    {
        assert(
                $this->andPredicate,
                equals('satisfies a lambda function and satisfies a lambda function')
        );
    }

    /**
     * @test
     */
    public function countEqualsSumOfCountOfBothPredicates()
    {
        assert(
                count(new AndPredicate(
                        new AndPredicate(
                            function($value) { return 'foo' === $value; },
                            function($value) { return 'foo' === $value; }
                        ),
                        function($value) { return 'foo' === $value; }
                )),
                equals(3)
        );
    }
}
