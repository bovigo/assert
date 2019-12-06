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
use function bovigo\assert\predicate\isBool;
use function bovigo\assert\predicate\isNotBool;

/**
 * Test for bovigo\assert\assert\predicate\isBool() and bovigo\assert\assert\predicate\isNotBool().
 *
 * @group  predicate
 */
class IsBoolTest extends TestCase
{
    public function validBooleans(): array
    {
        return [
            'true'  => [true],
            'false' => [false]
        ];
    }

    public function invalidBooleans(): array
    {
        return [
            'string' => ['foo'],
            'number' => [303],
            'object' => [new \stdClass()]
        ];
    }

    /**
     * @param  bool  $value
     * @test
     * @dataProvider  validBooleans
     */
    public function validBooleansAreRecognized(bool $value): void
    {
        assertThat($value, isBool());
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  invalidBooleans
     */
    public function invalidBooleansAreRejected($value): void
    {
        expect(function() use($value) { assertThat($value, isBool()); })
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
        expect(function() use($value) { assertThat($value, isNotBool()); })
            ->throws(AssertionFailure::class);
    }
}