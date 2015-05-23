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
     * @test
     */
    public function negatesWrappedPredicate()
    {
        $negatePredicate = new NegatePredicate(function($value) { return 'foo' === $value; });
        assertTrue($negatePredicate('bar'));
    }
}
