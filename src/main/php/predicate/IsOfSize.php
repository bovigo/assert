<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Predicate to test that something is of a certain size.
 */
class IsOfSize extends Predicate
{
    /**
     * the expected size
     *
     * @type  int
     */
    private $expectedSize;

    /**
     * constructor
     *
     * @param  int   $expectedSize  value to which test size must be equal
     */
    public function __construct($expectedSize)
    {
        $this->expectedSize = $expectedSize;
    }

    /**
     * test that the given value is of a certain size
     *
     * @param   string|array|\Countable|\Traversable  $value
     * @return  bool   true if size of value is equal to expected size, else false
     * @throws  \InvalidArgumentException
     */
    public function test($value)
    {
        if (is_string($value)) {
            return strlen($value) === $this->expectedSize;
        } elseif (is_array($value) || $value instanceof \Countable) {
            return count($value) === $this->expectedSize;
        } elseif ($value instanceof \Traversable) {
            return iterator_count($this->cloneIterator($value)) === $this->expectedSize;
        } else {
            throw new \InvalidArgumentException(
                    'Given value is neither a string, an array,'
                    . ' nor an instance of \Countable or \Traversable,'
                    . ' but of type ' . gettype($value)
            );
        }
    }

    /**
     * clones given traversable
     *
     * We need to use a clone because iterator_count() moves the pointer of the
     * iterator, but we don't want to change the pointer position of a passed
     * iterator instance as this would violate functional principles.
     *
     * @param   \Traversable  $traversable
     * @return  \Iterator
     */
    private function cloneIterator(\Traversable $traversable)
    {
        if ($traversable instanceof \IteratorAggregate) {
            return clone $traversable->getIterator();
        }

        return clone $traversable;
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString()
    {
        return sprintf('size matches %d', $this->expectedSize);
    }
}
