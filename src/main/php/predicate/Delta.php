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
 * Interface for predicates which allow a delta between expected and actual value
 */
interface Delta
{
    /**
     * sets delta which is allowed between expected and actual value
     *
     * @param   float  $delta
     * @return  Predicate
     */
    public function withDelta(float $delta): Predicate;
}