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
class IsEmptyCountableExample implements \Countable
{
    private $count;
    public function __construct(int $count)
    {
        $this->count = $count;
    }
    public function count(): int
    {
        return $this->count;
    }
}