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

use function bovigo\assert\assertThat;
use function bovigo\assert\expect;
use function bovigo\assert\predicate\isString;
use function bovigo\assert\predicate\isNotAString;

/**
 * Test for bovigo\assert\assert\predicate\isString() and bovigo\assert\assert\predicate\isNotAString().
 *
 * @group  predicate
 */
class IsStringTest extends TestCase
{
    /**
     * @return  array<string,array<string>>
     */
    public function validStrings(): array
    {
        return [
            'empty string'  => [''],
            'normal string' => ['example']
        ];
    }

    /**
     * @return  array<string,array<mixed>>
     */
    public function invalidStrings(): array
    {
        return [
            'array'  => [['foo']],
            'float'  => [30.3],
            'object' => [new \stdClass()]
        ];
    }

    /**
     * @param  string  $value
     * @test
     * @dataProvider  validStrings
     */
    public function validStringsAreRecognized(string $value): void
    {
        assertThat($value, isString());
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  invalidStrings
     */
    public function invalidStringsAreRejected($value): void
    {
        expect(function() use($value) { assertThat($value, isString()); })
            ->throws(AssertionFailure::class);
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  invalidStrings
     */
    public function invalidStringsAreRecognizedOnNegation($value): void
    {
        assertThat($value, isNotAString());
    }

    /**
     * @param  string  $value
     * @test
     * @dataProvider  validStrings
     */
    public function validStringsAreRejectedOnNegation(string $value): void
    {
        expect(function() use($value) { assertThat($value, isNotAString()); })
            ->throws(AssertionFailure::class);
    }
}