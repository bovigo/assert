<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;

use InvalidArgumentException;
use SebastianBergmann\Exporter\Exporter;
/**
 * Predicate to test that something is an instance of the expected type.
 */
class IsInstanceOf extends Predicate
{
    /**
     * @throws InvalidArgumentException in case given type name is not an existing class or interface
     */
    public function __construct(private string $expectedType)
    {
        if (!class_exists($this->expectedType) && !interface_exists($this->expectedType)) {
            throw new InvalidArgumentException(
                'Given expected type must be a string representing an'
                . ' existing class or interface'
            );
        }
    }

    public function test(mixed $value): bool
    {
        return $value instanceof $this->expectedType;
    }

    /**
     * returns string representation of predicate
     */
    public function __toString(): string
    {
        return sprintf(
            'is an instance of %s "%s"',
            interface_exists($this->expectedType) ? 'interface' : 'class',
            $this->expectedType
        );
    }

    /**
     * returns a textual description of given value
     */
    public function describeValue(Exporter $exporter, mixed $value): string
    {
        if (is_array($value)) {
            return 'an array';
        }

        return $exporter->shortenedExport($value);
    }
}
