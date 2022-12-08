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
 * Allows to wrap another predicate to decorate its messages.
 *
 * @internal
 * @since  1.6.0
 */
class Wrap extends Predicate
{
    public function __construct(private Predicate $predicate, private string $wrappedDesciption) { }

    /**
     * evaluates predicate against given value
     */
    public function test(mixed $value): bool
    {
        return $this->predicate->test($value);
    }

    /**
     * returns string representation of predicate
     */
    public function __toString(): string
    {
        return (string) $this->predicate;
    }

    /**
     * returns a textual description of given value
     */
    public function describeValue(Exporter $exporter, mixed $value): string
    {
        return sprintf(
                $this->wrappedDesciption,
                $this->predicate->describeValue($exporter, $value)
        );
    }
}
