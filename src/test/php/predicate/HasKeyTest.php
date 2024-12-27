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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\HasKey.
 */
#[Group('predicate')]
class HasKeyTest extends TestCase
{
    public static function provideTuplesEvaluatingToTrue(): iterable
    {
        yield [0, ['foo']];
        yield ['bar', ['bar' => 'foo5']];
        yield [303, new HasKeyArrayAccessExample()];
        yield ['foo', new HasKeyArrayAccessExample()];;
    }

    #[Test]
    #[DataProvider('provideTuplesEvaluatingToTrue')]
    public function evaluatesToTrue(int|string $key, array|ArrayAccess $array): void
    {
        assertTrue(hasKey($key)->test($array));
    }

    public static function provideTuplesEvaluatingToFalse(): iterable
    {
        yield [5, []];
        yield ['foo', []];
        yield [313, new HasKeyArrayAccessExample()];
        yield ['bar', new HasKeyArrayAccessExample()];
    }

    #[Test]
    #[DataProvider('provideTuplesEvaluatingToFalse')]
    public function evaluatesToFalse(int|string $key, array|ArrayAccess $array): void
    {
        assertFalse(hasKey($key)->test($array));
    }

    #[Test]
    public function throwsInvalidArgumentExceptionWhenValueCanNotHaveKey(): void
    {
        expect(fn() => hasKey('foo')->test(303))
            ->throws(\InvalidArgumentException::class);
    }

    #[Test]
    public function assertionFailureWithArrayContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat([], hasKey('bar')))
            ->throws(AssertionFailure::class)
            ->withMessage("Failed asserting that an array has the key 'bar'.");
    }

    #[Test]
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
