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
 * Applies a predicate to each key of an array or traversable.
 *
 * Please note that an empty array or traversable will result in a successful
 * test.
 *
 * @since  1.3.0
 */
class EachKey extends IteratingPredicate
{
    /**
     * @type  int|string
     */
    private $violatingKey;

    /**
     * actually tests the value
     *
     * @param   array|\Traversable  $traversable
     * @return  bool
     */
    protected function doTest($traversable)
    {
        foreach ($traversable as $key => $entry) {
            if (!$this->predicate->test($key)) {
                $this->violatingKey = $key;
                return false;
            }
        }

        return true;
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString()
    {
        return (null === $this->violatingKey ? 'each key ' : '') . $this->predicate;
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
        return (null !== $this->violatingKey ? 'key ' . $this->violatingKey . ' ' : '')
         . 'in ' . $exporter->export($value);
    }
}
