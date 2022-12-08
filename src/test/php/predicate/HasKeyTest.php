<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;

use ArrayAccess;
use bovigo\assert\AssertionFailure;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\HasKey.
 *
 * @group  predicate
 */
class HasKeyTest extends TestCase
{
    /**
     * @return  array<array<mixed>>
     */
    public function tuplesEvaluatingToTrue(): array
    {
        return [
                [0, ['foo']],
                ['bar', ['bar' => 'foo5']],
                [303, new HasKeyArrayAccessExample()],
                ['foo', new HasKeyArrayAccessExample()],
        ];
    }

    /**
     * @test
     * @dataProvider  tuplesEvaluatingToTrue
     */
    public function evaluatesToTrue(int|string $key, array|ArrayAccess $array): void
    {
        assertTrue(hasKey($key)->test($array));
    }

    /**
     * @return  array<array<mixed>>
     */
    public function tuplesEvaluatingToFalse(): array
    {
        return [
                [5, []],
                ['foo', []],
                [313, new HasKeyArrayAccessExample()],
                ['bar', new HasKeyArrayAccessExample()]
        ];
    }

    /**
     * @test
     * @dataProvider  tuplesEvaluatingToFalse
     */
    public function evaluatesToFalse(int|string $key, array|ArrayAccess $array): void
    {
        assertFalse(hasKey($key)->test($array));
    }

    /**
     * @test
     */
    public function throwsInvalidArgumentExceptionWhenValueCanNotHaveKey(): void
    {
        expect(fn() => hasKey('foo')->test(303))
            ->throws(\InvalidArgumentException::class);
    }

    /**
     * @test
     */
    public function assertionFailureWithArrayContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat([], hasKey('bar')))
            ->throws(AssertionFailure::class)
            ->withMessage("Failed asserting that an array has the key 'bar'.");
    }

    /**
     * @test
     */
    public function assertionFailureWithArrayAccessContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat(new HasKeyArrayAccessExample(), hasKey('bar')))
            ->throws(AssertionFailure::class)
            ->withMessage(
                "Failed asserting that " . HasKeyArrayAccessExample::class
                . " implementing \ArrayAccess has the key 'bar'."
            );
    }
}
