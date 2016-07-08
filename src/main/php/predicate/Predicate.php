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
 * Evaluates if a given value fulfills a criteria.
 *
 * @api
 * @method  \bovigo\assert\predicate\Predicate  and(\bovigo\assert\predicate\Predicate|callable $predicate)
 * @method  \bovigo\assert\predicate\Predicate  or(\bovigo\assert\predicate\Predicate|callable $predicate)
 */
abstract class Predicate implements \Countable
{
    /**
     * casts given predicate to a predicate instance
     *
     * @param   \bovigo\assert\predicate\Predicate|callable  $predicate
     * @return  \bovigo\assert\predicate\Predicate
     */
    public static function castFrom(callable $predicate): self
    {
        if ($predicate instanceof self) {
            return $predicate;
        }

        return new CallablePredicate($predicate);
    }

    /**
     * evaluates predicate against given value
     *
     * @param   mixed  $value
     * @return  bool
     */
    public abstract function test($value): bool;

    /**
     * evaluates predicate against given value
     *
     * @param   mixed  $value
     * @return  bool
     */
    public function __invoke($value): bool
    {
        return $this->test($value);
    }

    /**
     * combines this with another predicate to a predicate which requires both to be true
     *
     * @param   \bovigo\assert\predicate\Predicate|callable  $other
     * @return  \bovigo\assert\predicate\Predicate
     * @deprecated  since 1.4.0, use and($other) instead, will be removed with 2.0.0
     */
    public function asWellAs(callable $other): self
    {
        return new AndPredicate($this, self::castFrom($other));
    }

    /**
     * combines this with another predicate to a predicate which requires on of them to be true
     *
     * @param   \bovigo\assert\predicate\Predicate|callable  $other
     * @return  \bovigo\assert\predicate\Predicate
     * @deprecated  since 1.4.0, use or($other) instead, will be removed with 2.0.0
     */
    public function orElse(callable $other): self
    {
        return new OrPredicate($this, self::castFrom($other));
    }

    /**
     * returns a negated version of this predicate
     *
     * @return  \bovigo\assert\predicate\Predicate
     * @deprecated  since 1.4.0, use not($predicate) instead, will be removed with 2.0.0
     */
    public function negate(): self
    {
        return new NegatePredicate($this);
    }

    /**
     * provide utility methods "and" and "or" to combine predicates
     *
     * @param   string   $method
     * @param   mixed[]  $arguments
     * @return  \bovigo\assert\predicate\Predicate
     * @throws  \BadMethodCallException
     * @since   1.4.0
     */
    public function __call(string $method, $arguments): self
    {
        switch ($method) {
            case 'and':
                return $this->asWellAs(...$arguments);

            case 'or':
                return $this->orElse(...$arguments);

            default:
                throw new \BadMethodCallException(
                        'Call to undefined method '
                        . get_class($this) . '->' . $method . '()'
                );
        }
    }

    /**
     * returns amount of checks done in this predicate
     *
     * @return  int
     */
    public function count(): int
    {
        return 1;
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public abstract function __toString(): string;

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

        return $exporter->export($value);
    }
}
