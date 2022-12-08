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
use stdClass;

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\IsIdentical.
 *
 * @group  predicate
 */
class IsIdenticalTest extends TestCase
{
    public function identicalValues(): Generator
    {
        yield 'boolean true'  => [true];
        yield 'boolean false' => [false];
        yield 'string'        => ['foo'];
        yield 'number'        => [303];
        yield 'object'        => [new \stdClass()];
        yield 'float'         => [3.03];
    }

    /**
     * @test
     * @dataProvider  identicalValues
     */
    public function evaluatesToTrueIfGivenValueIsIdentical(mixed $value): void
    {
        assertTrue(isSameAs($value)->test($value));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfGivenValueIsNotIdentical(): void
    {
        assertFalse(isSameAs(3.03)->test(3.02));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat(true, isSameAs(false)))
            ->throws(AssertionFailure::class)
            ->withMessage("Failed asserting that true is identical to false.");
    }

    /**
     * @test
     */
    public function assertionFailureWithObjectsContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat(new \stdClass(), isSameAs(new stdClass())))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that object of type "stdClass" is identical to object of type "stdClass".'
            );
    }

    /**
     * @test
     */
    public function assertionFailureWithObjectAndOtherContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat(new \stdClass(), isSameAs('foo')))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that object of type "stdClass" is identical to \'foo\'.'
            );
    }
}
