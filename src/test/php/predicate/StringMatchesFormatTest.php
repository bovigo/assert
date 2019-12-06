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
 * Tests for bovigo\assert\predicate\StringMatchesFormat.
 *
 * @group  predicate
 * @group  issue_8
 */
class StringMatchesFormatTest extends TestCase
{
    /**
     * @test
     * @dataProvider validValues
     */
    public function validValueEvaluatesToTrue(string $format, string $value): void
    {
        assertTrue(matchesFormat($format)->test($value));
    }

    /**
     * @return  array<string,array<string>>
     */
    public function validValues(): array
    {
        return [
            'Simple %e' => ['%e', DIRECTORY_SEPARATOR],
            'Simple %s' => ['%s', 'string'],
            'Simple %S' => ['%S', 'string'],
            'Simple %a' => ['%a', 'string'],
            'Simple %A' => ['%A', 'string'],
            'Simple %w' => ['%w', ' '],
            'Simple %i' => ['%i', '-10'],
            'Simple %d' => ['%d', '1'],
            'Simple %x' => ['%x', '0123456789abcdefABCDEF'],
            'Simple %f' => ['%f', '-1.2e-10'],
            'Simple %c' => ['%c', 'a'],
            'Escaped %' => [
                'Escaped %%e %%s %%S %%a %%A %%w %%i %%d %%x %%f %%c',
                'Escaped %e %s %S %a %A %w %i %d %x %f %c'
            ]
        ];
    }

    /**
     * @test
     * @dataProvider invalidValues
     */
    public function invalidValueEvaluatesToFalse(string $format, string $value): void
    {
        assertFalse(matchesFormat($format)->test($value));
    }

    /**
     * @return  array<string,array<string>>
     */
    public function invalidValues(): array
    {
        return [
            'Negative %e' => ['%e', 'a'],
            'Negative %s' => ['%s', "\n"],
            'Negative %S' => ['%S', "1\n2\n2"],
            'Negative %a' => ['%a', ''],
            // Negative %A is not possible - it will match pretty much anything.
            'Negative %w' => ['%w', 'nowhitespace'],
            'Negative %i' => ['%i', 'abc'],
            'Negative %d' => ['%d', 'abc'],
            'Negative %x' => ['%x', '_'],
            'Negative %f' => ['%f', 'foo'],
            'Negative %c' => ['%c', 'abc']
        ];
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(function() { assertThat('foo', matchesFormat('%w')); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        "Failed asserting that 'foo' matches format \"%w\"."
        );
    }
}
