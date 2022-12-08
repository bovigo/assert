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
use function bovigo\assert\export;

/**
 * Predicate to test that a value contains something.
 */
class Contains extends Predicate
{
    /**
     * @param  mixed  $needle  value that must be contained
     */
    public function __construct(private mixed $needle) { }

    /**
     * tests that the given value contains expected value
     *
     * @throws  InvalidArgumentException  in case given value can't contain another value
     */
    public function test(mixed $value): bool
    {
        if (null === $value) {
            return is_null($this->needle);
        }

        if (is_string($value)) {
            return false !== strpos($value, (string) $this->needle);
        }

        if (is_iterable($value)) {
            foreach ($value as $element) {
                if ($element === $this->needle) {
                    return true;
                }
            }

            return false;
        }

        throw new InvalidArgumentException(
            'Given value of type "' . gettype($value) . '" can not contain something.'
        );
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString(): string
    {
        return 'contains ' . export($this->needle);
    }
}
