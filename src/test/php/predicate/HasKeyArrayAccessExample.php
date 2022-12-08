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
 * Helper class for the test.
 *
 * @implements \ArrayAccess<scalar,mixed>
 */
class HasKeyArrayAccessExample implements \ArrayAccess
{
    public function offsetExists(mixed $offset): bool
    {
        return 'foo' === $offset || 303 === $offset;
    }

    public function offsetGet(mixed $offset): mixed { }

    public function offsetSet(mixed $offset, mixed $value): void { }

    public function offsetUnset(mixed $offset): void { }

}