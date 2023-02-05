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
use Iterator;
use IteratorAggregate;
use Traversable;

/**
 * Applies a predicate to each value of an array or traversable.
 *
 * Please note that an empty array or traversable will result in a successful
 * test.
 *
 * @since 1.3.0
 */
abstract class IteratingPredicate extends Predicate
{
    protected Predicate $predicate;

    public function __construct(Predicate|callable $predicate)
    {
        $this->predicate = Predicate::castFrom($predicate);
    }

    /**
     * evaluates predicate against given value
     *
     * @throws InvalidArgumentException
     */
    public function test($value): bool
    {
        if (!is_array($value) && !($value instanceof Traversable)) {
            throw new InvalidArgumentException(
                'Given value is neither an array nor an instance of '
                . '\Traversable, but of type ' . gettype($value)
            );
        }

        $traversable = $this->traversable($value);
        $key         = $this->key($traversable);
        $result      = $this->doTest($traversable);
        if (null !== $key) {
            $this->rewind($traversable, $key);
        }

        return $result;
    }

    /**
     * actually tests the value
     */
    protected abstract function doTest(iterable $traversable): bool;

    /**
     * retrieve actual iterator
     */
    private function traversable(iterable $traversable): array|Iterator
    {
        if ($traversable instanceof IteratorAggregate) {
            return $this->traversable($traversable->getIterator());
        }

        return $traversable;
    }

    /**
     * retrieves current key of traversable
     */
    private function key(array|Iterator $traversable): int|string|null
    {
        if (is_array($traversable)) {
            return key($traversable);
        }

        return $traversable->key();
    }

    /**
     * rewinds traversable to given key to not change state of traversable
     */
    private function rewind(array|Iterator $traversable, int|string $key): void
    {
        if (is_array($traversable)) {
            foreach ($traversable as $currentKey => $value) {
                if ($currentKey === $key) {
                    break;
                }
            }
        } else {
            $traversable->rewind();
            while ($traversable->valid() && $key !== $traversable->key()) {
                $traversable->next();
            }
        }
    }

    /**
     * returns amount of checks done in this predicate
     */
    public function count(): int
    {
        return count($this->predicate);
    }
}
