<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Helper class for the test.
 */
class PredicateFooExample extends Predicate
{
    /**
     * evaluates predicate against given value
     */
    public function test(mixed $value): bool
    {
        return 'foo' === $value;
    }

    /**
     * returns string representation of predicate
     */
    public function __toString(): string
    {
        return 'is foo';
    }
}