<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Wraps a predicate evaluation into a callable.
 */
class CallablePredicate extends Predicate
{
    /** @var callable */
    private $predicate;

    /**
     * constructor
     *
     * The description will be used instead of the default description in the
     * string representation of this predicate.
     */
    public function __construct(
        callable $predicate,
        private ?string $description = null
    ) {
        $this->predicate = $predicate;
    }

    /**
     * evaluates predicate against given value
     */
    public function test(mixed $value): bool
    {
        $predicate = $this->predicate;
        return $predicate($value);
    }

    /**
     * returns string representation of predicate
     */
    public function __toString(): string
    {
        if (empty($this->description)) {
            return 'satisfies ' . $this->predicateName();
        }

        return $this->description;
    }

    /**
     * tries to determine a name for the callable
     */
    private function predicateName(): string
    {
        if (is_array($this->predicate)) {
            if (is_string($this->predicate[0])) {
                return $this->predicate[0] . '::' . $this->predicate[1] . '()';
            }

            return get_class($this->predicate[0]) . '->' . $this->predicate[1] . '()';
        } elseif (is_string($this->predicate)) {
            return $this->predicate . '()';
        }

        return 'a lambda function';
    }
}
