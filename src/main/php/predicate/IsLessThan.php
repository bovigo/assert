<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use function bovigo\assert\export;
/**
 * Predicate to test that something is smaller than an expected value.
 */
class IsLessThan extends Predicate
{
    /**
     * expected maximum value
     *
     * @type  numeric
     */
    private $expected;

    /**
     * constructor
     *
     * @param  numeric  $expected  expected maximum value
     */
    public function __construct($expected)
    {
        $this->expected = $expected;
    }

    /**
     * test that the given value is smaller than the expected value
     *
     * @param   mixed  $value
     * @return  bool
     */
    public function test($value)
    {
        return $value < $this->expected;
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString()
    {
        return 'is less than ' . export($this->expected);
    }
}
