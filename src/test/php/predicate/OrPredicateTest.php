<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Test for bovigo\assert\predicate\OrPredicate.
 *
 * @group  predicate
 */
class OrPredicateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @type  OrPredicate
     */
    private $orPredicate;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->orPredicate = new OrPredicate(
                function($value) { return 'bar' === $value; },
                function($value) { return 'foo' === $value; }
        );
    }

    /**
     * @test
     */
    public function returnsTrueWhenOnePredicateReturnsTrue()
    {
        assertTrue($this->orPredicate->test('foo'));
    }

    /**
     * @test
     */
    public function returnsFalseWhenBothPredicatesReturnsFalse()
    {
        assertFalse($this->orPredicate->test('baz'));
    }
}
