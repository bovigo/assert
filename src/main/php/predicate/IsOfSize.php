<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use SebastianBergmann\Exporter\Exporter;
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
        if (!$this->isCountable($value)) {
            throw new \InvalidArgumentException(
                    'Given value is neither a string, an array,'
                    . ' nor an instance of \Countable or \Traversable,'
                    . ' but of type ' . gettype($value)
            );
        }

        return $this->sizeOf($value) === $this->expectedSize;
    }

    /**
     * checks if given value is countable
     *
     * @param   mixed  $value
     * @return  bool
     */
    private function isCountable($value)
    {
        return is_string($value) || is_array($value) || $value instanceof \Countable || $value instanceof \Traversable;
    }

    /**
     * calculates the size of given value
     *
     * @param   string|array|\Countable|\Traversable  $value
     * @return  int
     */
    private function sizeOf($value)
    {
        if (is_string($value)) {
            return strlen($value);
        } elseif (is_array($value) || $value instanceof \Countable) {
            return count($value);
        } elseif ($value instanceof \Traversable) {
            return iterator_count($this->cloneIterator($value));
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
        return sprintf('matches expected size %d', $this->expectedSize);
    }

    /**
     * returns a textual description of given value
     *
     * @param   \SebastianBergmann\Exporter\Exporter  $exporter
     * @param   mixed                                 $value
     * @return  string
     */
    public function describeValue(Exporter $exporter, $value)
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
