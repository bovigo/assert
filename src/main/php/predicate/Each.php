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
    public function test($value)
    {
        if (!is_array($value) && !($value instanceof \Traversable)) {
            throw new \InvalidArgumentException(
                    'Given value is neither an array nor an instance of '
                    . '\Traversable, but of type ' . gettype($value)
            );
        }

        $traversable = $this->traversable($value);
        $key         = $this->key($traversable);
        $result      = true;
        foreach ($traversable as $entry) {
            if (!$this->predicate->test($entry)) {
                $result = false;
            }
        }

        $this->rewind($traversable, $key);
        return $result;
    }

    /**
     * retrieve actual iterator
     *
     * @param   array|\Traversable  $traversable
     * @return  array|\Traversable
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
     * @param   array|\Traversable  $traversable
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
     * @param  array|\Traversable  $traversable
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
