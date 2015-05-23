<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Predicate to ensure a value complies to a given regular expression.
 *
 * The predicate uses preg_match() and checks if the value occurs exactly
 * one time. Please make sure that the supplied regular expression contains
 * correct delimiters, they will not be applied automatically. The test()
 * method throws a \RuntimeException in case the regular expression is invalid.
 */
class Regex extends Predicate
{
    /**
     * the regular expression to use for validation
     *
     * @type  string
     */
    private $regex;

    /**
     * constructor
     *
     * @param  string  $regex  regular expression to use for validation
     */
    public function __construct($regex)
    {
        $this->regex = $regex;
    }

    /**
     * test that the given value complies with the regular expression
     *
     * @param   mixed  $value
     * @return  bool
     * @throws  \RuntimeException  in case the used regular expresion is invalid
     */
    public function test($value)
    {
        $check = @preg_match($this->regex, $value);
        if (false === $check) {
            // TODO make use of preg_last_error()
            throw new \RuntimeException('Invalid regular expression ' . $this->regex);
        }

        return ((1 != $check) ? (false) : (true));
    }
}
