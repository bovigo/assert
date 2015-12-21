<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Predicate to test that an array has a key.
 */
class IsEmpty extends Predicate
{
    use ReusablePredicate;

    /**
     * tests that the given value is empty
     *
     * @param   mixed  $value
     * @return  bool
     */
    public function test($value)
    {
        if ($value instanceof \Countable) {
            return count($value) === 0;
        }

        return empty($value);
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString()
    {
        return 'is empty';
    }
}
