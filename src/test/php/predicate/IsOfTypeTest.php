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
 * Tests for bovigo\assert\predicate\IsOfType.
 *
 * @group  predicate
 */
class IsOfTypeTest extends TestCase
{
    /**
     * @test
     */
    public function throwsInvalidArgumentExceptionWhenCreatedWithUnknownType(): void
    {

        expect(fn() => isOfType('nope'))
            ->throws(\InvalidArgumentException::class);
    }

    /** only here for test purpose */
    public static function dummy(): void {}

    public function validValuesAndTypes(): Generator
    {
        yield 'array'                   => ['array', []];
        yield 'boolean true'            => ['boolean', true];
        yield 'boolean false'           => ['boolean', false];
        yield 'bool true'               => ['bool', true];
        yield 'bool false'              => ['bool', false];
        yield 'double'                  => ['double', 3.03];
        yield 'float'                   => ['float', 3.03];
        yield 'integer'                 => ['integer', 303];
        yield 'int'                     => ['int', 303];
        yield 'numeric int'             => ['numeric', 303];
        yield 'numeric string'          => ['numeric', '303'];
        yield 'numeric float'           => ['numeric', 3.03];
        yield 'object'                  => ['object', new stdClass()];
        yield 'resource'                => ['resource', fopen(__FILE__, 'rb')];
        yield 'string'                  => ['string', 'foo'];
        yield 'scalar true'             => ['scalar', true];
        yield 'scalar false'            => ['scalar', false];
        yield 'scalar float'            => ['scalar', 3.03];
        yield 'scalar int'              => ['scalar', 303];
        yield 'scalar string'           => ['scalar', 'foo'];
        yield 'callable closure'        => ['callable', function() {}];
        yield 'callable arrow function' => ['callable', fn() => 303];
        yield 'callable function name'  => ['callable', 'strlen'];
        yield 'callable class method'   => ['callable', [$this, 'validValuesAndTypes']];
        yield 'callable static class method'  => ['callable', [__CLASS__, 'dummy']];
        yield 'iterable'                => ['iterable', [1, 2, 3]];
    }

    /**
     * @test
     * @dataProvider  validValuesAndTypes
     */
    public function evaluatesToTrueIfTypeOfValueEqualsExpectedType(
        string $expectedType,
        mixed $value
    ): void {
        assertTrue(isOfType($expectedType)->test($value));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfTypeOfValueDoesNotEqualExpectedType(): void
    {
        assertFalse(isOfType('int')->test('foo'));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat([], isOfType('int')))
            ->throws(AssertionFailure::class)
            ->withMessage('Failed asserting that an array is of type "int".');
    }
}
