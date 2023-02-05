<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;

use BadMethodCallException;
use SebastianBergmann\Exporter\Exporter;
/**
 * Evaluates if a given value fulfills a criteria.
 *
 * @api
 * @method Predicate and(Predicate|callable $predicate)
 * @method Predicate or(Predicate|callable $predicate)
 */
abstract class Predicate implements \Countable
{
    /**
     * casts given predicate to a predicate instance
     */
    public static function castFrom(Predicate|callable $predicate): self
    {
        if ($predicate instanceof self) {
            return $predicate;
        }

        return new CallablePredicate($predicate);
    }

    /**
     * evaluates predicate against given value
     */
    public abstract function test(mixed $value): bool;

    /**
     * evaluates predicate against given value
     */
    public function __invoke(mixed $value): bool
    {
        return $this->test($value);
    }

    /**
     * provide utility methods "and" and "or" to combine predicates
     *
     * @param  string   $method
     * @param  mixed[]  $arguments
     * @return Predicate
     * @throws BadMethodCallException
     * @since  1.4.0
     */
    public function __call(string $method, $arguments): self
    {
        return match ($method) {
            'and' => new AndPredicate($this, ...$arguments),
            'or' => new OrPredicate($this, ...$arguments),
            default => throw new BadMethodCallException(
                'Call to undefined method '
                . get_class($this) . '->' . $method . '()'
            )
        };
    }

    /**
     * returns amount of checks done in this predicate
     */
    public function count(): int
    {
        return 1;
    }

    /**
     * returns string representation of predicate
     */
    public abstract function __toString(): string;

    /**
     * returns a textual description of given value
     */
    public function describeValue(Exporter $exporter, mixed $value): string
    {
        if (is_array($value)) {
            return 'an array';
        }

        return $exporter->export($value);
    }
}
