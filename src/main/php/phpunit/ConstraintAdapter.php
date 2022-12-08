<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\phpunit;
use bovigo\assert\predicate\Predicate;
use PHPUnit\Framework\Constraint\Constraint;
use ReflectionMethod;
use SebastianBergmann\Exporter\Exporter;
/**
 * Predicate which allows to use constraints from PHPUnit as predicate.
 *
 * @internal
 */
class ConstraintAdapter extends Predicate
{
    public function __construct(private Constraint $constraint) { }

    /**
     * evaluates predicate against given value
     */
    public function test(mixed $value): bool
    {
        return $this->constraint->evaluate($value, '', true);
    }

    /**
     * returns amount of checks done in this predicate
     */
    public function count(): int
    {
        return count($this->constraint);
    }

    /**
     * returns string representation of predicate
     */
    public function __toString(): string
    {
        return '';
    }

    /**
     * returns a textual description of given value
     */
    public function describeValue(Exporter $exporter, mixed $value): string
    {
        $refMethod = new ReflectionMethod(get_class($this->constraint), 'failureDescription');
        $refMethod->setAccessible(true);
        return $refMethod->invoke($this->constraint, $value);
    }
}
