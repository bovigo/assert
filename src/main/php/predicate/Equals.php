<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use bovigo\assert;
/**
 * Predicate to test that something is equal.
 *
 * This class can compare any scalar value with an expected value. The
 * value to test has to be of the same type and should have the same
 * content as the expected value.
 */
class Equals extends Predicate
{
    /**
     * the expected value
     *
     * @type  string
     */
    private $expected = null;

    /**
     * constructor
     *
     * @param   scalar|null  $expected
     * @throws  \InvalidArgumentException
     */
    public function __construct($expected)
    {
        if (!is_scalar($expected) && null != $expected) {
            throw new \InvalidArgumentException(
                    'Can only compare scalar values and null.'
            );
        }

        $this->expected = $expected;
    }

    /**
     * test that the given value is eqal in content and type to the expected value
     *
     * @param   scalar|null  $value
     * @return  bool         true if value is equal to expected value, else false
     */
    public function test($value)
    {
        return $this->expected === $value;
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString()
    {
        if (is_string($this->expected)) {
            if (strpos($this->expected, "\n") !== false) {
                return 'is equal to <text>';
            }

            return sprintf('is equal to <string:%s>', $this->expected);
        }

        return sprintf(
                'is equal to %s%s',
                assert\exporter()->export($this->expected),
                $this->delta != 0 ? sprintf(' with delta <%F>', $this->delta) : ''
        );
    }
}
