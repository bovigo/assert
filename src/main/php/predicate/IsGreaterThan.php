<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use function bovigo\assert\export;
/**
 * Predicate to test that something is greater than an expected value.
 */
class IsGreaterThan extends Predicate
{
    public function __construct(private int|float $expected) { }

    /**
     * test that the given value is greater than the expected value
     */
    public function test(mixed $value): bool
    {
        return $value > $this->expected;
    }

    /**
     * returns string representation of predicate
     */
    public function __toString(): string
    {
        return 'is greater than ' . export($this->expected);
    }
}
