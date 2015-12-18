<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use function bovigo\assert\exporter;
/**
 * Predicate to test that something is identical.
 */
class IsIdentical extends Predicate
{
    /**
     * @type float
     */
    const EPSILON = 0.0000000001;
    /**
     *
     * @type  mixed
     */
    private $value;

    /**
     * constructor
     *
     * @param  mixed  $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * test that the given value is eqal in content and type to the expected value
     *
     * @param   scalar|null  $value
     * @return  bool         true if value is equal to expected value, else false
     */
    public function test($value)
    {
        if (is_double($this->value) && is_double($value) &&
            !is_infinite($this->value) && !is_infinite($value) &&
            !is_nan($this->value) && !is_nan($value)) {
            return abs($this->value - $value) < self::EPSILON;
        }

        return $this->value === $value;
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString()
    {
        if (is_object($this->value)) {
            return sprintf(
                    'is identical to an object of class "%s"',
                    get_class($this->value)
            );
        }

        return 'is identical to ' . exporter()->export($this->value);
    }
}
