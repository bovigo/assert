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
 * Negates evaluation of wrapped predicate.
 */
class NegatePredicate extends Predicate
{
    /**
     * @var  Predicate
     */
    private $predicate;

    /**
     * constructor
     *
     * @param  \bovigo\assert\predicate\Predicate|callable  $predicate
     */
    public function __construct($predicate)
    {
        $this->predicate = Predicate::castFrom($predicate);
    }

    /**
     * evaluates predicate against given value
     *
     * @param   mixed  $value
     * @return  bool
     */
    public function test($value): bool
    {
        return !$this->predicate->test($value);
    }

    /**
     * returns amount of checks done in this predicate
     *
     * @return  int
     */
    public function count(): int
    {
        return count($this->predicate);
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString(): string
    {
        switch (get_class($this->predicate)) {
            case AndPredicate::class:
            case NegatePredicate::class:
            case OrPredicate::class:
                return 'not (' . $this->predicate . ')';

            default:
                return $this->reverse((string) $this->predicate);

        }
    }

    /**
     * returns a negation of the given string
     *
     * @param   string  $string
     * @return  string
     */
    private function reverse(string $string): string
    {
        return str_replace(
                [
                    'contains ',
                    'has ',
                    'is ',
                    'are ',
                    'matches ',
                    'satisfies ',
                    'starts with ',
                    'ends with ',
                    'not not '
                ],
                [
                    'does not contain ',
                    'does not have ',
                    'is not ',
                    'are not ',
                    'does not match ',
                    'does not satisfy ',
                    'does not start with ',
                    'does not end with ',
                    'not '
                ],
                $string
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
        return $this->predicate->describeValue($exporter, $value);
    }
}
