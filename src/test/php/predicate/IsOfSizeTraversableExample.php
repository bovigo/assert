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
 */
class IsOfSizeTraversableExample implements \Iterator
{
    private $current = 0;

    public function current()
    {
        return $this->current;
    }

    public function key()
    {
        return $this->current;
    }

    public function next()
    {
        $this->current++;
    }

    public function rewind()
    {
        $this->current = 0;
    }

    public function valid()
    {
        return 3 > $this->current;
    }
}