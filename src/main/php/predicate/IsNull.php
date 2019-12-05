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
 * Predicate to test that something is null.
 */
class IsNull extends Predicate
{
    use ReusablePredicate;

    /**
     * test that the given value is true
     *
     * @param   mixed  $value
     * @return  bool   true if value is null
     */
    public function test($value): bool
    {
        return null === $value;
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString(): string
    {
        return 'is null';
    }
}
