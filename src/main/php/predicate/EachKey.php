<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Applies a predicate to each key of an array or traversable.
 *
 * Please note that an empty array or traversable will result in a successful
 * test.
 *
 * @since  1.3.0
 */
class EachKey extends IteratingPredicate
{
    /**
     * actually tests the value
     *
     * @param   array|\Traversable  $traversable
     * @return  bool
     */
    protected function doTest($traversable)
    {
        foreach ($traversable as $key => $entry) {
            if (!$this->predicate->test($key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString()
    {
        return 'each key ' . $this->predicate;
    }
}
