<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Tests that a string starts with a given prefix.
 *
 * @since  1.1.0
 */
class StringStartsWith extends Predicate
{
    /**
     * @type  string
     */
    private $prefix;

    /**
     * constructor
     *
     * @param   string  $prefix
     * @throws  \InvalidArgumentException
     */
    public function __construct($prefix)
    {
        if (!is_string($prefix)) {
            throw new \InvalidArgumentException(
                    'Prefix must be a string, "' . gettype($prefix) . '" given.'
            );
        }

        $this->prefix = $prefix;
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
                $this->prefix,
                0,
                strlen($this->prefix)
        );
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString()
    {
        return 'starts with \'' . $this->prefix . '\'';
    }
}
