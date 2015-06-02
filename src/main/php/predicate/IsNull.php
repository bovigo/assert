<?php
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
     * @param   scalar  $value
     * @return  bool    true if value is true, else false
     */
    public function test($value)
    {
        return null === $value;
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString()
    {
        return 'is null';
    }
}

