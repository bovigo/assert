<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertTrue;
use function bovigo\assert\assertThat;
/**
 * Test for bovigo\assert\assert\predicate\AndPredicate.
 */
#[Group('predicate')]
class AndPredicateTest extends TestCase
{
    private AndPredicate $andPredicate;

    protected function setUp(): void
    {
        $this->andPredicate = new AndPredicate(
            fn($value) => 'foo' === $value,
            fn($value) => 'foo' === $value
        );
    }

    #[Test]
    public function returnsTrueWhenBothPredicatesReturnsTrue(): void
    {
        assertTrue($this->andPredicate->test('foo'));
    }

    #[Test]
    public function returnsFalseWhenOnePredicateReturnsFalse(): void
    {
        assertFalse($this->andPredicate->test('baz'));
    }

    #[Test]
    public function hasStringRepresentation(): void
    {
        assertThat(
            $this->andPredicate,
            equals('satisfies a lambda function and satisfies a lambda function')
        );
    }

    #[Test]
    public function countEqualsSumOfCountOfBothPredicates(): void
    {
        assertThat(
            count(new AndPredicate(
                new AndPredicate(
                    fn($value) => 'foo' === $value,
                    fn($value) => 'foo' === $value
                ),
                fn($value) => 'foo' === $value
            )),
            equals(3)
        );
    }
}
