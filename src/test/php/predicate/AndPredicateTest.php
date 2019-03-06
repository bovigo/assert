<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertTrue;
use function bovigo\assert\assertThat;
/**
 * Test for bovigo\assert\assert\predicate\AndPredicate.
 *
 * @group  predicate
 */
class AndPredicateTest extends TestCase
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
    public function setUp(): void
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

    /**
     * @test
     */
    public function hasStringRepresentation()
    {
        assertThat(
                $this->andPredicate,
                equals('satisfies a lambda function and satisfies a lambda function')
        );
    }

    /**
     * @test
     */
    public function countEqualsSumOfCountOfBothPredicates()
    {
        assertThat(
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
