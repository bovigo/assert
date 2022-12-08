<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
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
     * @param  int|string          $key
     * @param  array<mixed>|\ArrayAccess<mixed,mixed>  $array
     * @test
     * @dataProvider  tuplesEvaluatingToTrue
     */
    public function evaluatesToTrue($key, $array): void
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
     * @param  mixed                      $key
     * @param  string|array<mixed>|\Traversable<mixed>  $array
     * @test
     * @dataProvider  tuplesEvaluatingToFalse
     */
    public function evaluatesToFalse($key, $array): void
    {
        assertFalse(hasKey($key)->test($array));
    }

    /**
     * @test
     */
    public function throwsInvalidArgumentExceptionWhenValueCanNotHaveKey(): void
    {
        expect(function() { hasKey('foo')->test(303); })
                ->throws(\InvalidArgumentException::class);
    }

    /**
     * @test
     */
    public function assertionFailureWithArrayContainsMeaningfulInformation(): void
    {
        expect(function() { assertThat([], hasKey('bar')); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that an array has the key 'bar'.");
    }

    /**
     * @test
     */
    public function assertionFailureWithArrayAccessContainsMeaningfulInformation(): void
    {
        expect(function() { assertThat(new HasKeyArrayAccessExample(), hasKey('bar')); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        "Failed asserting that " . HasKeyArrayAccessExample::class
                        . " implementing \ArrayAccess has the key 'bar'."
        );
    }
}
