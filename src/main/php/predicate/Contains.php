<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use function bovigo\assert\export;
/**
 * Predicate to test that a value contains something.
 */
class Contains extends Predicate
{
    /**
     * the value to be contained in value to validate
     *
     * @type  mixed
     */
    private $needle;

    /**
     * constructor
     *
     * @param  mixed  $needle  value that must be contained
     */
    public function __construct($needle)
    {
        $this->needle = $needle;
    }

    /**
     * tests that the given value contains expected value
     *
     * @param   mixed  $value
     * @return  bool
     * @throws  \InvalidArgumentException  in case given value can't contain another value
     */
    public function test($value): bool
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

        throw new \InvalidArgumentException(
                'Given value of type "' . gettype($value)
                . '" can not contain something.'
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
