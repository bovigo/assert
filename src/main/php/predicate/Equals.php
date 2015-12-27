<?php
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
class Equals extends Predicate
{
    /**
     * the expected value
     *
     * @type  mixed
     */
    private $expected;
    /**
     * @type  double
     */
    private $delta;
    /**
     * @type  \SebastianBergmann\Comparator\ComparisonFailure
     */
    private $lastFailure;

    /**
     * constructor
     *
     * @param  mixed   $expected  value to which test values must be equal
     * @param  double  $delta     allowed numerical distance between two values to consider them equal
     */
    public function __construct($expected, $delta = 0.0)
    {
        $this->expected = $expected;
        $this->delta    = $delta;
    }

    /**
     * test that the given value is eqal in content and type to the expected value
     *
     * @param   mixed  $value
     * @return  bool   true if value is equal to expected value, else false
     */
    public function test($value)
    {
        $this->lastFailure = null; // reset in case predicate is used more than once
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
            $this->lastFailure = $cf;
            return false;
        }
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString()
    {
        $result = '';
        if (is_string($this->expected)) {
            if (strpos($this->expected, "\n") !== false) {
                $result = 'is equal to <text>';
            } else {
                $result = sprintf('is equal to <string:%s>', $this->expected);
            }
        } else {

            $result = sprintf(
                    'is equal to %s%s',
                    export($this->expected),
                    $this->delta != 0 ? sprintf(' with delta <%F>', $this->delta) : ''
            );
        }

        if (null !== $this->lastFailure) {
            $diff = $this->lastFailure->getDiff();
            if (!empty($diff)) {
                return $result . '.' . $diff;
            }
        }

        return $result;
    }
}
