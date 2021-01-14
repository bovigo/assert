<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertTrue;
/**
 * Tests for bovigo\assert\predicate\containsSubset.
 *
 * @group predicate
 * @group issue_22
 * @since 6.2.0
 */
class ContainsSubsetTest extends TestCase
{
    /**
     * @return  array<array<mixed>>
     */
    public function tuplesEvaluatingToTrue(): array
    {
        return [
            [range('a', 'e'), range('a', 'e')],
            [range('a', 'e'), range('a', 'c')],
            [range('a', 'e'), []],
            [['a' => 'b', 'c' => 'd'], ['a' => 'b', 'c' => 'd']],
            [['a' => 'b', 'c' => 'd'], ['a' => 'b']],
            [['a' => 'b', 'c' => 'd'], []],
        ];
    }

    /**
     * @param array<mixed> $subset
     * @param array<mixed> $other
     * @test
     * @dataProvider  tuplesEvaluatingToTrue
     */
    public function evaluatesToTrue($other, $subset): void
    {
        assertTrue(containsSubset($other)->test($subset));
    }

    /**
     * @return  array<array<mixed>>
     */
    public function tuplesEvaluatingToFalse(): array
    {
        return [
            [range('a', 'e'), range('o', 't')],
            [['a' => 'b', 'c' => 'd'], ['o' => 'p', 'q' => 'r']],
        ];
    }

    /**
     * @param array<mixed> $subset
     * @param array<mixed> $other
     * @test
     * @dataProvider  tuplesEvaluatingToFalse
     */
    public function evaluatesToFalse($other, $subset): void
    {
        assertFalse(containsSubset($other)->test($subset));
    }
}
