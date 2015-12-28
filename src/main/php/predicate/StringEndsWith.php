<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Tests that a string ends with a given suffix.
 *
 * @since  1.1.0
 */
class StringEndsWith extends Predicate
{
    /**
     * @type  string
     */
    private $suffix;

    /**
     * constructor
     *
     * @param   string  $suffix
     * @throws  \InvalidArgumentException
     */
    public function __construct($suffix)
    {
        if (!is_string($suffix)) {
            throw new \InvalidArgumentException(
                    'Suffix must be a string, "' . gettype($suffix) . '" given.'
            );
        }

        $this->suffix = $suffix;
    }

    /**
     * evaluates predicate against given value
     *
     * @param   mixed  $value
     * @return  bool
     */
    public function test($value)
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException(
                    'Given value is not a string, but of type "'
                    . gettype($value) . '"'
            );
        }

        return 0 === substr_compare(
                $value,
                $this->suffix,
                -strlen($this->suffix)
        );
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString()
    {
        return 'ends with \'' . $this->suffix . '\'';
    }
}
