<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\phpunit;
use bovigo\assert\AssertionFailure;
use bovigo\assert\predicate\Predicate;
use PHPUnit\Framework\Constraint\Constraint;
use SebastianBergmann\Exporter\Exporter;
/**
 * Predicate which allows to use constraints from PHPUnit as predicate.
 *
 * @internal
 */
class ConstraintAdapter extends Predicate
{
    /**
     * @type  \PHPUnit\Framework\Constraint
     */
    private $constraint;

    /**
     * constructor
     *
     * @param  \PHPUnit\Framework\Constraint\Constraint  $constraint
     */
    public function __construct(Constraint $constraint)
    {
        $this->constraint = $constraint;
    }

    /**
     * evaluates predicate against given value
     *
     * @param   mixed  $value
     * @return  bool
     */
    public function test($value): bool
    {
        return $this->constraint->evaluate($value, '', true);
    }

    /**
     * returns amount of checks done in this predicate
     *
     * @return  int
     */
    public function count(): int
    {
        return count($this->constraint);
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString(): string
    {
        return '';
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
        $refMethod = new \ReflectionMethod(get_class($this->constraint), 'failureDescription');
        $refMethod->setAccessible(true);
        return $refMethod->invoke($this->constraint, $value);
    }
}
