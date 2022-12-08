<?php
declare(strict_types=1);
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
     * @var  int|string
     */
    private $violatingKey;

    /**
     * actually tests the value
     *
     * @param   iterable<mixed>  $traversable
     * @return  bool
     */
    protected function doTest(iterable $traversable): bool
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
     */
    public function __toString(): string
    {
        return (null === $this->violatingKey ? 'each key ' : '') . $this->predicate;
    }

    /**
     * returns a textual description of given value
     */
    public function describeValue(Exporter $exporter, mixed $value): string
    {
        return (null !== $this->violatingKey ? 'key ' . $this->violatingKey . ' ' : '')
         . 'in ' . $exporter->export($value);
    }
}
