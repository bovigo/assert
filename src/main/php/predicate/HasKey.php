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
 * Predicate to test that an array has a key.
 */
class HasKey extends Predicate
{
    /**
     * key which must be in array
     *
     * @type  int|string
     */
    private $key;

    /**
     * constructor
     *
     * @param   int|string  $key  key which must be in array
     * @throws  \InvalidArgumentException
     */
    public function __construct($key)
    {
        if (!is_int($key) && !is_string($key)) {
            throw new \InvalidArgumentException(
                    'Key must be integer or string, '
                    . gettype($key) . ' given'
            );
        }

        $this->key = $key;
    }

    /**
     * tests that the given value contains expected key
     *
     * @param   mixed  $value
     * @return  bool
     * @throws  \InvalidArgumentException  in case given value can't have a key
     */
    public function test($value)
    {
        if (is_array($value)) {
            return array_key_exists($this->key, $value);
        } elseif ($value instanceof \ArrayAccess) {
            return $value->offsetExists($this->key);
        }

        throw new \InvalidArgumentException(
                'Given value of type ' . gettype($value)
                . ' can not have a key'
        );
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString()
    {
        return sprintf(
            'has the key "%s"',
            export($this->key)
        );
    }
}
