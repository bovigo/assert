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
 * Applies a predicate to each value of an array or traversable.
 *
 * Please note that an empty array or traversable will result in a successful
 * test.
 *
 * @since 1.1.0
 */
class Each extends IteratingPredicate
{
    /** @var int|string */
    private $violatingKey;
    /** @var mixed */
    private $violatingValue;

    /**
     * actually tests the value
     *
     * @param iterable<mixed> $traversable
     */
    protected function doTest(iterable $traversable): bool
    {
        foreach ($traversable as $key => $entry) {
            if (!$this->predicate->test($entry)) {
                $this->violatingKey = $key;
                $this->violatingValue = $entry;
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
        return (null === $this->violatingKey ? 'each value ' : '') . $this->predicate;
    }

    /**
     * returns a textual description of given value
     */
    public function describeValue(Exporter $exporter, mixed $value): string
    {
        $valueDescription = '';
        if (null !== $this->violatingKey) {
            $valueDescription .= 'element '
             . $exporter->export($this->violatingValue)
             . ' at key ' . $this->violatingKey . ' ';
        }

        return $valueDescription .  'in ' . $exporter->export($value);
    }
}
