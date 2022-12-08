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
use SebastianBergmann\Exporter\Exporter;
/**
 * Predicate to test that an array has a key.
 */
class IsEmpty extends Predicate
{
    use ReusablePredicate;

    /**
     * tests that the given value is empty
     */
    public function test(mixed $value): bool
    {
        if ($value instanceof Countable) {
            return count($value) === 0;
        }

        return empty($value);
    }

    /**
     * returns string representation of predicate
     */
    public function __toString(): string
    {
        return 'is empty';
    }

    /**
     * returns a textual description of given value
     */
    public function describeValue(Exporter $exporter, mixed $value): string
    {
        if (is_object($value) && $value instanceof Countable) {
            return get_class($value) . ' implementing \Countable';
        }

        return parent::describeValue($exporter, $value);
    }
}
