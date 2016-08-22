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
use SebastianBergmann\Exporter\Exporter;
/**
 * Predicate to check that a piece of code triggers an error.
 *
 * @since  2.1.0
 */
class ExpectedError extends Predicate
{
    /**
     * @type  int
     */
    private $expectedError;

    /**
     * constructor
     *
     * @param  int  $expectedError
     */
    public function __construct(int $expectedError)
    {
        $this->expectedError = $expectedError;
    }

    /**
     * tests that the given value contains expected key
     *
     * @param   mixed  $value
     * @return  bool
     * @throws  \InvalidArgumentException  in case given value isn't a catched error
     */
    public function test($value): bool
    {
        if (! $value instanceof CatchedError) {
            throw new \InvalidArgumentException('Given value is not an error');
        }

        return $value->level() === $this->expectedError;
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString(): string
    {
        return 'matches expected error "' . CatchedError::nameOf($this->expectedError) . '"';
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
        if (! $value instanceof CatchedError) {
            return parent::describeValue($exporter, $value);
        }

        return 'error of level "' . $value->name()
        . '" with message "' . $value->errstr() . '" triggered in '
        . $value->file() . ' on line ' . $value->line();
    }
}
