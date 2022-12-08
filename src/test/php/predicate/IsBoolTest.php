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
use PHPUnit\Framework\TestCase;

use function bovigo\assert\assertThat;
use function bovigo\assert\expect;
use function bovigo\assert\predicate\isBool;
use function bovigo\assert\predicate\isNotBool;
/**
 * Test for bovigo\assert\assert\predicate\isBool() and bovigo\assert\assert\predicate\isNotBool().
 *
 * @group  predicate
 */
class IsBoolTest extends TestCase
{
    public function validBooleans(): Generator
    {
        yield 'true'  => [true];
        yield 'false' => [false];
    }

    public function invalidBooleans(): Generator
    {
        yield 'string' => ['foo'];
        yield 'number' => [303];
        yield 'object' => [new \stdClass()];
    }

    /**
     * @test
     * @dataProvider  validBooleans
     */
    public function validBooleansAreRecognized(bool $value): void
    {
        assertThat($value, isBool());
    }

    /**
     * @test
     * @dataProvider  invalidBooleans
     */
    public function invalidBooleansAreRejected(mixed $value): void
    {
        expect(fn() => assertThat($value, isBool()))
            ->throws(AssertionFailure::class);
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  invalidBooleans
     */
    public function invalidBooleansAreRecognizedOnNegation($value): void
    {
        assertThat($value, isNotBool());
    }

    /**
     * @param  bool  $value
     * @test
     * @dataProvider  validBooleans
     */
    public function validBooleansAreRejectedOnNegation(bool $value): void
    {
        expect(fn() => assertThat($value, isNotBool()))
            ->throws(AssertionFailure::class);
    }
}