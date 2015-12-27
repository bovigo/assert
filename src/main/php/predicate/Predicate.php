<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use SebastianBergmann\Exporter\Exporter;
/**
 * Evaluates if a given value fulfills a criteria.
 */
abstract class Predicate implements \Countable
{
    /**
     * casts given predicate to a predicate instance
     *
     * @param   \bovigo\assert\predicate\Predicate|callable  $predicate
     * @return  \bovigo\assert\predicate\Predicate
     * @throws  \InvalidArgumentException
     */
    public static function castFrom($predicate)
    {
        if ($predicate instanceof self) {
            return $predicate;
        } elseif (is_callable($predicate)) {
            return new CallablePredicate($predicate);
        }

        throw new \InvalidArgumentException(
                'Given predicate is neither a callable nor an instance of ' . __CLASS__
        );
    }

    /**
     * evaluates predicate against given value
     *
     * @param   mixed  $value
     * @return  bool
     */
    public abstract function test($value);

    /**
     * evaluates predicate against given value
     *
     * @param   mixed  $value
     * @return  bool
     */
    public function __invoke($value)
    {
        return $this->test($value);
    }

    /**
     * combines this with another predicate to a predicate which requires both to be true
     *
     * @param   \bovigo\assert\predicate\Predicate|callable  $other
     * @return  \bovigo\assert\predicate\Predicate
     */
    public function asWellAs($other)
    {
        return new AndPredicate($this, self::castFrom($other));
    }

    /**
     * combines this with another predicate to a predicate which requires on of them to be true
     *
     * @param   \bovigo\assert\predicate\Predicate|callable  $other
     * @return  \bovigo\assert\predicate\Predicate
     */
    public function orElse($other)
    {
        return new OrPredicate($this, self::castFrom($other));
    }

    /**
     * returns a negated version of this predicate
     *
     * @return  \bovigo\assert\predicate\Predicate
     */
    public function negate()
    {
        return new NegatePredicate($this);
    }

    /**
     * returns amount of checks done in this predicate
     *
     * @return  int
     */
    public function count()
    {
        return 1;
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public abstract function __toString();

    /**
     * returns a textual description of given value
     *
     * @param   \SebastianBergmann\Exporter\Exporter  $exporter
     * @param   mixed                                 $value
     * @return  string
     */
    public function describeValue(Exporter $exporter, $value)
    {
        if (is_array($value)) {
            return 'an array';
        }

        return $exporter->export($value);
    }
}
