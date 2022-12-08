<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;

use ArrayAccess;
use InvalidArgumentException;
use SebastianBergmann\Exporter\Exporter;

use function bovigo\assert\export;
/**
 * Predicate to test that an array has a key.
 */
class HasKey extends Predicate
{
    public function __construct(private int|string $key) { }

    /**
     * tests that the given value contains expected key
     *
     * @throws  InvalidArgumentException  in case given value can't have a key
     */
    public function test(mixed $value): bool
    {
        if (is_array($value)) {
            return array_key_exists($this->key, $value);
        }

        if ($value instanceof ArrayAccess) {
            return $value->offsetExists($this->key);
        }

        throw new InvalidArgumentException(
                'Given value of type ' . gettype($value)
                . ' can not have a key'
        );
    }

    /**
     * returns string representation of predicate
     */
    public function __toString(): string
    {
        return 'has the key ' . export($this->key);
    }

    /**
     * returns a textual description of given value
     */
    public function describeValue(Exporter $exporter, mixed $value): string
    {
        if ($value instanceof ArrayAccess) {
            return get_class($value) . ' implementing \ArrayAccess';
        }

        return parent::describeValue($exporter, $value);
    }
}
