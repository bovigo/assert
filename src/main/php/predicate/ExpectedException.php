<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;

use InvalidArgumentException;
use SebastianBergmann\Exporter\Exporter;
use Throwable;

/**
 * Predicate to check that a piece of code throws an exception.
 *
 * @since  1.6.0
 */
class ExpectedException extends Predicate
{
    public function __construct(private string $expectedType) { }

    /**
     * tests that the given value contains expected key
     *
     * @throws  InvalidArgumentException  in case given value isn't an exception
     */
    public function test(mixed $value): bool
    {
        if (!$value instanceof Throwable) {
            throw new InvalidArgumentException('Given value is not an exception');
        }

        return $value instanceof $this->expectedType;
    }

    /**
     * returns string representation of predicate
     */
    public function __toString(): string
    {
        return 'matches expected exception "' . $this->expectedType . '"';
    }

    /**
     * returns a textual description of given value
     */
    public function describeValue(Exporter $exporter, mixed $value): string
    {
        if (! $value instanceof \Throwable) {
            return parent::describeValue($exporter, $value);
        }

        return 'exception of type "' . get_class($value)
        . '" with message "' . $value->getMessage() . '" thrown in '
        . $value->getFile() . ' on line ' . $value->getLine();
    }
}
