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
use function bovigo\assert\predicate\isScalar;
use function bovigo\assert\predicate\isNotScalar;

/**
 * Test for bovigo\assert\assert\predicate\isScalar() and bovigo\assert\assert\predicate\isNotScalar().
 *
 * @group  predicate
 */
class IsScalarTest extends TestCase
{
    public function validScalars(): array
    {
        return [
            'empty string'  => [''],
            'normal string' => ['example'],
            'int'           => [303],
            'float'         => [3.13]
        ];
    }

    public function invalidScalars(): array
    {
        return [
            'array'  => [['foo']],
            'object' => [new \stdClass()]
        ];
    }

    /**
     * @param  scalar  $value
     * @test
     * @dataProvider  validScalars
     */
    public function validScalarsAreRecognized($value): void
    {
        assertThat($value, isScalar());
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  invalidScalars
     */
    public function invalidScalarsAreRejected($value): void
    {
        expect(function() use($value) { assertThat($value, isScalar()); })
            ->throws(AssertionFailure::class);
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  invalidScalars
     */
    public function invalidScalarsAreRecognizedOnNegation($value): void
    {
        assertThat($value, isNotScalar());
    }

    /**
     * @param  scalar  $value
     * @test
     * @dataProvider  validScalars
     */
    public function validScalarsAreRejectedOnNegation($value): void
    {
        expect(function() use($value) { assertThat($value, isNotScalar()); })
            ->throws(AssertionFailure::class);
    }
}