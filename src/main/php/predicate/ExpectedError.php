<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;

use bovigo\assert\CatchedError;
use bovigo\assert\TriggeredError;
use InvalidArgumentException;
use SebastianBergmann\Exporter\Exporter;
/**
 * Predicate to check that a piece of code triggers an error.
 *
 * @since 2.1.0
 */
class ExpectedError extends Predicate
{
    public function __construct(private int $expectedError) { }

    /**
     * tests that the given value contains expected key
     *
     * @throws InvalidArgumentException in case given value isn't a catched error
     */
    public function test($value): bool
    {
        if (!$value instanceof CatchedError) {
            throw new InvalidArgumentException('Given value is not an error');
        }

        return $value->level() === $this->expectedError;
    }

    /**
     * returns string representation of predicate
     */
    public function __toString(): string
    {
        return 'matches expected error "' . TriggeredError::nameOf($this->expectedError) . '"';
    }

    /**
     * returns a textual description of given value
     */
    public function describeValue(Exporter $exporter, mixed $value): string
    {
        if (!$value instanceof CatchedError) {
            return parent::describeValue($exporter, $value);
        }

        return 'error of level "' . $value->name()
            . '" with message "' . $value->errstr() . '" triggered in '
            . $value->file() . ' on line ' . $value->line();
    }
}
