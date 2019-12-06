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
    public function offsetExists($offset)
    {
        return 'foo' === $offset || 303 === $offset;
    }

    public function offsetGet($offset) { }

    public function offsetSet($offset, $value) { }

    public function offsetUnset($offset) { }

}