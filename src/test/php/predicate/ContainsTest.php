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
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Traversable;

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\Contains.
 */
#[Group('predicate')]
class ContainsTest extends TestCase
{
    public static function tuplesEvaluatingToTrue(): Generator
    {
        yield [null, null];
        yield [5, 'foo5'];
        yield [5, 'fo5o'];
        yield ['foo', 'foobar'];
        yield ['foo', 'foo'];
        yield ['foo', ['foo', 'bar', 'baz']];
        yield [null, ['foo', null, 'baz']];
    }

    /**
     * @param string|array<mixed>|Traversable<mixed>|null $haystack
     */
    #[Test]
    #[DataProvider('tuplesEvaluatingToTrue')]
    public function evaluatesToTrue(mixed $needle, string|array|Traversable|null $haystack): void
    {
        assertTrue(contains($needle)->test($haystack));
    }

    public static function tuplesEvaluatingToFalse(): Generator
    {
        yield [5, 'foo'];
        yield [true, 'blub'];
        yield ['dummy', 'bar'];
        yield ['nope', ['foo', 'bar', 'baz']];
    }

    /**
     * @param string|array<mixed>|Traversable<mixed> $haystack
     */
    #[Test]
    #[DataProvider('tuplesEvaluatingToFalse')]
    public function evaluatesToFalse(mixed $needle, string|array|Traversable $haystack): void
    {
        assertFalse(contains($needle)->test($haystack));
    }

    #[Test]
    public function throwsInvalidArgumentExceptionWhenValueCanNotContainAnything(): void
    {
        expect(fn() => contains('foo')->test(303))
            ->throws(\InvalidArgumentException::class)
            ->withMessage('Given value of type "integer" can not contain something.');
    }

    #[Test]
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat([], contains('foo')))
            ->throws(AssertionFailure::class)
            ->withMessage("Failed asserting that an array contains 'foo'.");
    }
}
