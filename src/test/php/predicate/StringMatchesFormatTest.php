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

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\StringMatchesFormat.
 */
#[Group('predicate')]
#[Group('issue_8')]
class StringMatchesFormatTest extends TestCase
{
    #[Test]
    #[DataProvider('validValues')]
    public function validValueEvaluatesToTrue(string $format, string $value): void
    {
        assertTrue(matchesFormat($format)->test($value));
    }

    public static function validValues(): Generator
    {
        yield 'Simple %e' => ['%e', DIRECTORY_SEPARATOR];
        yield 'Simple %s' => ['%s', 'string'];
        yield 'Simple %S' => ['%S', 'string'];
        yield 'Simple %a' => ['%a', 'string'];
        yield 'Simple %A' => ['%A', 'string'];
        yield 'Simple %w' => ['%w', ' '];
        yield 'Simple %i' => ['%i', '-10'];
        yield 'Simple %d' => ['%d', '1'];
        yield 'Simple %x' => ['%x', '0123456789abcdefABCDEF'];
        yield 'Simple %f' => ['%f', '-1.2e-10'];
        yield 'Simple %c' => ['%c', 'a'];
        yield 'Escaped %' => [
                'Escaped %%e %%s %%S %%a %%A %%w %%i %%d %%x %%f %%c',
                'Escaped %e %s %S %a %A %w %i %d %x %f %c'
            ];
    }

    #[Test]
    #[DataProvider('invalidValues')]
    public function invalidValueEvaluatesToFalse(string $format, string $value): void
    {
        assertFalse(matchesFormat($format)->test($value));
    }

    public static function invalidValues(): Generator
    {
        yield 'Negative %e' => ['%e', 'a'];
        yield 'Negative %s' => ['%s', "\n"];
        yield 'Negative %S' => ['%S', "1\n2\n2"];
        yield 'Negative %a' => ['%a', ''];
        // Negative %A is not possible - it will match pretty much anything.
        yield 'Negative %w' => ['%w', 'nowhitespace'];
        yield 'Negative %i' => ['%i', 'abc'];
        yield 'Negative %d' => ['%d', 'abc'];
        yield 'Negative %x' => ['%x', '_'];
        yield 'Negative %f' => ['%f', 'foo'];
        yield 'Negative %c' => ['%c', 'abc'];
    }

    #[Test]
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat('foo', matchesFormat('%w')))
            ->throws(AssertionFailure::class)
            ->withMessage(
                "Failed asserting that 'foo' matches format \"%w\"."
            );
    }
}
