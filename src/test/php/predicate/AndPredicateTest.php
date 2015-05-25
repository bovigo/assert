<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use bovigo\assert;
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
        assertTrue($this->andPredicate->test('foo'));
    }

    /**
     * @test
     */
    public function returnsFalseWhenOnePredicateReturnsFalse()
    {
        assertFalse($this->andPredicate->test('baz'));
    }
}
