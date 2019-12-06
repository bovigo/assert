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
 * Tests for bovigo\assert\predicate\StringStartsWith.
 *
 * @group  predicate
 * @since  1.1.0
 */
class StringStartsWithTest extends TestCase
{
    /**
     * @test
     */
    public function nonStringValuesThrowInvalidArgumentException(): void
    {
        expect(function() { startsWith('foo')->test(303); })
                ->throws(\InvalidArgumentException::class);
    }

    /**
     * @return  array<string,array<string>>
     */
    public function trueValues(): array
    {
        return [
          'string which starts with and contains foo' => ['foobarfoobaz'],
          'string which starts with foo'              => ['foobarbaz']
        ];
    }

    /**
     * @param  string  $value
     * @test
     * @dataProvider  trueValues
     */
    public function evaluatesToTrueIfStringStartsWithPrefix($value): void
    {
        assertTrue(startsWith('foo')->test($value));
    }

    /**
     * @return  array<string,array<string>>
     */
    public function falseValues(): array
    {
        return [
          'string which contains foo'  => ['barfoobaz'],
          'string which ends with foo' => ['barbazfoo']
        ];
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  falseValues
     */
    public function evaluatesToFalseIfGivenValueIsFalse($value): void
    {
        assertFalse(startsWith('foo')->test($value));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(function() { assertThat('bar', startsWith('foo')); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that 'bar' starts with 'foo'.");
    }
}
