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
 * Predicate to test that something is an instance of the expected type.
 */
class IsInstanceOf extends Predicate
{
    /**
     * @var  string
     */
    private $expectedType;

    /**
     * constructor
     *
     * @param   string  $expectedType  name of expected type
     * @throws  \InvalidArgumentException  in case given type name is not an existing class or interface
     */
    public function __construct(string $expectedType)
    {
        if (!class_exists($expectedType) && !interface_exists($expectedType)) {
            throw new \InvalidArgumentException(
                    'Given expected type must be a string representing an'
                    . ' existing class or interface'
            );
        }

        $this->expectedType = $expectedType;
    }

    /**
     * test that the given value is true
     *
     * @param   mixed  $value
     * @return  bool   true if value is instance of expected type
     */
    public function test($value): bool
    {
        return $value instanceof $this->expectedType;
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
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
     *
     * @param   \SebastianBergmann\Exporter\Exporter  $exporter
     * @param   mixed                                 $value
     * @return  string
     */
    public function describeValue(Exporter $exporter, $value): string
    {
        if (is_array($value)) {
            return 'an array';
        }

        return $exporter->shortenedExport($value);
    }
}
