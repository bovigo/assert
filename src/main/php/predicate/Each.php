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
 * Applies a predicate to each value of an array or traversable.
 *
 * Please note that an empty array or traversable will result in a successful
 * test.
 *
 * @since  1.1.0
 */
class Each extends Predicate
{
    /**
     * @type  \bovigo\assert\predicate\Predicate
     */
    private $predicate;

    /**
     * constructor
     *
     * @param  \bovigo\assert\predicate\Predicate  $predicate
     */
    public function __construct(Predicate $predicate)
    {
        $this->predicate = $predicate;
    }

    /**
     * evaluates predicate against given value
     *
     * @param   mixed  $value
     * @return  bool
     * @throws  \InvalidArgumentException
     */
    public function test($value)
    {
        if (!is_array($value) && !($value instanceof \Traversable)) {
            throw new \InvalidArgumentException(
                    'Given value is neither an array nor an instance of '
                    . '\Traversable, but of type ' . gettype($value)
            );
        }

        foreach ($this->cloneIterable($value) as $entry) {
            if (!$this->predicate->test($entry)) {
                return false;
            }
        }

        return true;
    }

    /**
     * clones given traversable
     *
     * We need to use a clone because the test moves the pointer of the array or
     * traversable, but we don't want to change the pointer position of a passed
     * value as this would violate functional principles.
     *
     * @param   array|\Traversable  $traversable
     * @return  array|\Traversable
     */
    private function cloneIterable($traversable)
    {
        if (is_array($traversable)) {
            return $traversable;
        }

        if ($traversable instanceof \IteratorAggregate) {
            return clone $traversable->getIterator();
        }

        return clone $traversable;
    }

    /**
     * returns amount of checks done in this predicate
     *
     * @return  int
     */
    public function count()
    {
        return count($this->predicate);
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString()
    {
        return 'each value ' . $this->predicate;
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
        return 'in ' . $exporter->export($value);
    }
}
