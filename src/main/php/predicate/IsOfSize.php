<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;

use Countable;
use InvalidArgumentException;
use Iterator;
use IteratorAggregate;
use SebastianBergmann\Exporter\Exporter;
use Traversable;

/**
 * Predicate to test that something is of a certain size.
 */
class IsOfSize extends Predicate
{
    public function __construct(private int $expectedSize) { }

    /**
     * test that the given value is of a certain size
     *
     * @throws  InvalidArgumentException in case given value is not countable
     */
    public function test(mixed $value): bool
    {
        if (!$this->isCountable($value)) {
            throw new InvalidArgumentException(
                'Given value is neither a string, an array,'
                . ' nor an instance of \Countable or \Traversable,'
                . ' but of type ' . gettype($value)
            );
        }

        return $this->sizeOf($value) === $this->expectedSize;
    }

    /**
     * checks if given value is countable
     */
    private function isCountable(mixed $value): bool
    {
        return is_string($value) || is_array($value) || $value instanceof Countable || $value instanceof Traversable;
    }

    /**
     * calculates the size of given value
     */
    private function sizeOf(string|array|Countable|Iterator|IteratorAggregate $value): int
    {
        if (is_string($value)) {
            return strlen($value);
        } elseif (is_array($value) || $value instanceof \Countable) {
            return count($value);
        }

        $traversable = $this->traversable($value);
        $key         = $traversable->key();
        $count       = iterator_count($traversable);
        // rewinds traversable to previous key to not change state of traversable
        // because iterator_count() changes the pointer
        $traversable->rewind();
        while ($traversable->valid() && $key !== $traversable->key()) {
            $traversable->next();
        }

        return $count;
    }

    /**
     * retrieve actual iterator
     */
    private function traversable(Iterator|IteratorAggregate $traversable): Iterator
    {
        if ($traversable instanceof \IteratorAggregate) {
            return $this->traversable($traversable->getIterator());
        }

        return $traversable;
    }

    /**
     * returns string representation of predicate
     */
    public function __toString(): string
    {
        return sprintf('matches expected size %d', $this->expectedSize);
    }

    /**
     * returns a textual description of given value
     */
    public function describeValue(Exporter $exporter, mixed $value): string
    {
        if ($this->isCountable($value)) {
            if (is_string($value)) {
                $type = 'string';
            } elseif (is_array($value)) {
                $type = 'array';
            } else {
                $type = 'instance of type ' . get_class($value);
            }

            return $type . ' with actual size ' . $this->sizeOf($value);
        }

        return parent::describeValue($exporter, $value);
    }
}
