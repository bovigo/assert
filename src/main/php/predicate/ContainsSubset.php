<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;

use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Exporter\Exporter;

/**
 * Predicate to test that an array contains another array
 *
 * @since 6.2.0
 */
class ContainsSubset extends Predicate
{
    /**  @var array<mixed> */
    private $other;
    /** @var ComparisonFailure|null */
    private $diff;

    /**
     * @param array<mixed> $other
     */
    public function __construct(array $other)
    {
        $this->other = $other;
    }

    public function test($value): bool
    {
        if (!is_iterable($value)) {
            return false;
        }

        // type cast $this->other & $value as an array to allow support in
        // standard array functions.
        $other = $this->toArray($this->other);
        $value = $this->toArray($value);

        $patched = array_replace_recursive($other, $value);

        if ($other == $patched) {
            return true;
        }

        $this->diff = new ComparisonFailure(
            $patched,
            $other,
            var_export($patched, true),
            var_export($other, true)
        );

        return false;
    }

    public function __toString(): string
    {
        if ($this->diff) {
            return $this->diff->getDiff();
        }

        return '';
    }

    public function describeValue(Exporter $exporter, $value): string
    {
        return 'an array has the subset '.$exporter->export($value);
    }

    /**
     * @param iterable<mixed> $other
     * @return array<mixed>
     */
    private function toArray(iterable $other): array
    {
        if (\is_array($other)) {
            return $other;
        }
        if ($other instanceof \ArrayObject) {
            return $other->getArrayCopy();
        }

        return iterator_to_array($other);
    }
}
