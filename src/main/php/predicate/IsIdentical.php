<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use SebastianBergmann\Exporter\Exporter;

use function bovigo\assert\export;
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
    private $expected;

    /**
     * constructor
     *
     * @param  mixed  $value
     */
    public function __construct($value)
    {
        $this->expected = $value;
    }

    /**
     * test that the given value is eqal in content and type to the expected value
     *
     * @param   scalar|null  $value
     * @return  bool         true if value is equal to expected value, else false
     */
    public function test($value): bool
    {
        if (is_double($this->expected) && is_double($value) &&
            !is_infinite($this->expected) && !is_infinite($value) &&
            !is_nan($this->expected) && !is_nan($value)) {
            return abs($this->expected - $value) < self::EPSILON;
        }

        return $this->expected === $value;
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString(): string
    {
        $result = 'is identical to ';
        if (is_object($this->expected)) {
            return $result . 'object of type "' . get_class($this->expected) . '"';
        }

        return $result . export($this->expected);
    }

    /**
     * returns a textual description of given value
     *
     * @param   \SebastianBergmann\Exporter\Exporter  $exporter
     * @param   mixed                                 $value
     * @return  string
     */
    public function describeValue(Exporter $exporter, $value): string
    {
        if (is_object($value)) {
            return 'object of type "' . get_class($value) . '"';
        }

        return parent::describeValue($exporter, $value);
    }
}
