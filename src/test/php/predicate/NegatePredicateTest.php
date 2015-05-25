<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
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
        assertTrue($this->negatePredicate->test('bar'));
    }

    /**
     * @test
     */
    public function hasStringRepresentation()
    {
        assertEquals(
                'not (callable<lambda>)',
                $this->negatePredicate
        );
    }
}
