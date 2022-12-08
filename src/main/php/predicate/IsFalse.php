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
 * Predicate to test that something is false.
 */
class IsFalse extends Predicate
{
    use ReusablePredicate;

    /**
     * test that the given value is false
     */
    public function test(mixed $value): bool
    {
        return false === $value;
    }

    /**
     * returns string representation of predicate
     */
    public function __toString(): string
    {
        return 'is false';
    }
}
