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
use SebastianBergmann\Comparator\Factory;

use function bovigo\assert\export;
/**
 * Predicate to test that something is equal.
 */
class Equals extends Predicate implements Delta
{
    /**
     * @var  double
     */
    private $delta;
    /**
     * @var  string
     */
    private $lastFailureDiff;

    /**
     * constructor
     *
     * @deprecated  param  $delta is deprecated, use withDelta() on instance instead, will be removed with 7.0
     * @param  mixed  $expected  value to which test values must be equal
     * @param  float  $delta     allowed numerical distance between two values to consider them equal
     */
    public function __construct(private mixed $expected, float $delta = 0.0)
    {
        $this->delta    = $delta;
    }

    /**
     * sets delta which is allowed between expected and actual value
     *
     * @param   float  $delta
     * @return  Predicate
     */
    public function withDelta(float $delta): Predicate
    {
        $this->delta = $delta;
        return $this;
    }

    /**
     * test that the given value is eqal in content and type to the expected value
     */
    public function test(mixed $value): bool
    {
        $this->lastFailureDiff = ''; // reset in case predicate is used more than once
        if ($this->expected === $value) {
            return true;
        }

        try {
            $comparator = Factory::getInstance()->getComparatorFor(
                $this->expected,
                $value
            );
            $comparator->assertEquals($this->expected, $value, $this->delta);
            return true;
        } catch (ComparisonFailure $cf) {
            // This is a hack, but the only way to contain the usage of
            // SebastianBergmann\Comparator inside this class.
            $this->lastFailureDiff = $cf->getDiff();
            return false;
        }
    }

    /**
     * returns string representation of predicate
     */
    public function __toString(): string
    {
        $result = '';
        if (is_string($this->expected)) {
            if (strpos($this->expected, "\n") !== false) {
                $result = 'is equal to <text>';
            } elseif ('' === $this->expected) {
                $result = 'is an empty string';
            } else {
                $result = sprintf('is equal to <string:%s>', $this->expected);
            }
        } elseif (is_array($this->expected) && empty($this->expected)) {
            $result = 'is an empty array';
        } else {
            $result = sprintf(
                'is equal to %s%s',
                export($this->expected),
                $this->delta != 0 ? sprintf(' with delta <%F>', $this->delta) : ''
            );
        }

        if ($this->hasDiffForLastFailure()) {
            return $result . '.' . $this->diffForLastFailure();
        }

        return $result;
    }

    /**
     * checks if a diff is available for the last failure
     *
     * @since   1.7.0
     */
    public function hasDiffForLastFailure(): bool
    {
        return !empty($this->lastFailureDiff);
    }

    /**
     * returns diff for last failure
     *
     * @since   1.7.0
     */
    public function diffForLastFailure(): string
    {
        return $this->lastFailureDiff;
    }
}
