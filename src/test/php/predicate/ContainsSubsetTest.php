<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertTrue;
/**
 * Tests for bovigo\assert\predicate\containsSubset.
 *
 * @since 6.2.0
 */
#[Group('predicate')]
#[Group('issue_22')]
class ContainsSubsetTest extends TestCase
{
    public static function tuplesEvaluatingToTrue(): Generator
    {
        yield [range('a', 'e'), range('a', 'e')];
        yield [range('a', 'e'), range('a', 'c')];
        yield [range('a', 'e'), []];
        yield [['a' => 'b', 'c' => 'd'], ['a' => 'b', 'c' => 'd']];
        yield [['a' => 'b', 'c' => 'd'], ['a' => 'b']];
        yield [['a' => 'b', 'c' => 'd'], []];
    }

    /**
     * @param array<mixed> $subset
     * @param array<mixed> $other
     */
    #[Test]
    #[DataProvider('tuplesEvaluatingToTrue')]
    public function evaluatesToTrue(array $other, array $subset): void
    {
        assertTrue(containsSubset($other)->test($subset));
    }

    public static function tuplesEvaluatingToFalse(): Generator
    {
        yield [range('a', 'e'), range('o', 't')];
        yield [['a' => 'b', 'c' => 'd'], ['o' => 'p', 'q' => 'r']];
    }

    /**
     * @param array<mixed> $subset
     * @param array<mixed> $other
     */
    #[Test]
    #[DataProvider('tuplesEvaluatingToFalse')]
    public function evaluatesToFalse(array $other, array $subset): void
    {
        assertFalse(containsSubset($other)->test($subset));
    }
}
