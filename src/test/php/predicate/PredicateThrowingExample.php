<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;

use InvalidArgumentException;

/**
 * Helper class for the test.
 */
class PredicateThrowingExample extends PredicateFooExample
{
    public function test(mixed $value): bool
    {
        throw new InvalidArgumentException('exception message');
    }
}