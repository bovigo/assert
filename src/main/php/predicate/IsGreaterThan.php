<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use function bovigo\assert\export;
/**
 * Predicate to test that something is greater than an expected value.
 */
class IsGreaterThan extends Predicate
{
    /**
     * expected minimum value
     *
     * @var  int|float
     */
    private $expected;

    /**
     * constructor
     *
     * @param  int|float  $expected  expected minimum value
     */
    public function __construct($expected)
    {
        $this->expected = $expected;
    }

    /**
     * test that the given value is greater than the expected value
     *
     * @param   mixed  $value
     * @return  bool
     */
    public function test($value): bool
    {
        return $value > $this->expected;
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString(): string
    {
        return 'is greater than ' . export($this->expected);
    }
}
