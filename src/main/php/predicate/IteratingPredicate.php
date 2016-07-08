<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Applies a predicate to each value of an array or traversable.
 *
 * Please note that an empty array or traversable will result in a successful
 * test.
 *
 * @since  1.3.0
 */
abstract class IteratingPredicate extends Predicate
{
    /**
     * @type  \bovigo\assert\predicate\Predicate
     */
    protected $predicate;

    /**
     * constructor
     *
     * @param  callable|\bovigo\assert\predicate\Predicate  $predicate
     */
    public function __construct($predicate)
    {
        $this->predicate = Predicate::castFrom($predicate);
    }

    /**
     * evaluates predicate against given value
     *
     * @param   array|\Traversable  $value
     * @return  bool
     * @throws  \InvalidArgumentException
     */
    public function test($value): bool
    {
        if (!is_array($value) && !($value instanceof \Traversable)) {
            throw new \InvalidArgumentException(
                    'Given value is neither an array nor an instance of '
                    . '\Traversable, but of type ' . gettype($value)
            );
        }

        $traversable = $this->traversable($value);
        $key         = $this->key($traversable);
        $result      = $this->doTest($traversable);
        $this->rewind($traversable, $key);
        return $result;
    }

    /**
     * actually tests the value
     *
     * @param   array|\Traversable  $traversable
     * @return  bool
     */
    protected abstract function doTest($traversable): bool;

    /**
     * retrieve actual iterator
     *
     * @param   array|\Traversable  $traversable
     * @return  array|\Iterator
     */
    private function traversable($traversable)
    {
        if ($traversable instanceof \IteratorAggregate) {
            return $traversable->getIterator();
        }

        return $traversable;
    }

    /**
     * retrieves current key of traversable
     *
     * @param   array|\Iterator  $traversable
     * @return  int|string
     */
    private function key($traversable)
    {
        if (is_array($traversable)) {
            return key($traversable);
        }

        return $traversable->key();
    }

    /**
     * rewinds traversable to given key to not change state of traversable
     *
     * @param  array|\Iterator  $traversable
     * @param  int|string          $key
     */
    private function rewind($traversable, $key)
    {
        if ($key === null) {
            return;
        }

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
     *
     * @return  int
     */
    public function count(): int
    {
        return count($this->predicate);
    }
}
