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
/**
 * Predicate to check that a piece of code throws an exception.
 *
 * @since  1.6.0
 */
class ExpectedException extends Predicate
{
    /**
     * @type  string
     */
    private $expectedType;

    /**
     * constructor
     *
     * @param  string  $expectedType
     */
    public function __construct($expectedType)
    {
        $this->expectedType = $expectedType;
    }

    /**
     * tests that the given value contains expected key
     *
     * @param   mixed  $value
     * @return  bool
     * @throws  \InvalidArgumentException  in case given value can't have a key
     */
    public function test($value): bool
    {
        if (! $value instanceof \Throwable) {
            throw new \InvalidArgumentException('Given value is not an exception');
        }

        return $value instanceof $this->expectedType;
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString(): string
    {
        return 'matches expected exception "' . $this->expectedType . '"';
    }

    /**
     * returns a textual description of given value
     *
     * @param   \SebastianBergmann\Exporter\Exporter  $exporter
     * @param   mixed                                 $value
     * @return  string
     * @throws  \InvalidArgumentException  in case given value can't have a key
     */
    public function describeValue(Exporter $exporter, $value): string
    {
        if (! $value instanceof \Throwable) {
            return parent::describeValue($exporter, $value);
        }

        return 'exception of type "' . get_class($value)
        . '" with message "' . $value->getMessage() . '" thrown in '
        . $value->getFile() . ' on line ' . $value->getLine();
    }
}
