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
 * Tests for bovigo\assert\predicate\IsOfType.
 *
 * @group  predicate
 */
class IsOfTypeTest extends TestCase
{
    /**
     * @test
     */
    public function throwsInvalidArgumentExceptionWhenCreatedWithUnknownType()
    {

        expect(function() { isOfType('nope'); })
                ->throws(\InvalidArgumentException::class);
    }

    /**
     * @return  array
     */
    public function validValuesAndTypes(): array
    {
        return [
            'array'                  => ['array', []],
            'boolean true'           => ['boolean', true],
            'boolean false'          => ['boolean', false],
            'bool true'              => ['bool', true],
            'bool false'             => ['bool', false],
            'double'                 => ['double', 3.03],
            'float'                  => ['float', 3.03],
            'integer'                => ['integer', 303],
            'int'                    => ['int', 303],
            'numeric int'            => ['numeric', 303],
            'numeric string'         => ['numeric', '303'],
            'numeric float'          => ['numeric', 3.03],
            'object'                 => ['object', new \stdClass()],
            'resource'               => ['resource', fopen(__FILE__, 'rb')],
            'string'                 => ['string', 'foo'],
            'scalar true'            => ['scalar', true],
            'scalar false'           => ['scalar', false],
            'scalar float'           => ['scalar', 3.03],
            'scalar int'             => ['scalar', 303],
            'scalar string'          => ['scalar', 'foo'],
            'callable closure'       => ['callable', function() {}],
            'callable function name' => ['callable', 'strlen'],
            'callable class method'  => ['callable', [__CLASS__, 'validValuesAndTypes']]
        ];
    }

    /**
     * @param  string  $expectedType
     * @param  mixed   $value
     * @test
     * @dataProvider  validValuesAndTypes
     */
    public function evaluatesToTrueIfTypeOfValueEqualsExpectedType($expectedType, $value)
    {
        assertTrue(isOfType($expectedType)->test($value));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfTypeOfValueDoesNotEqualExpectedType()
    {
        assertFalse(isOfType('int')->test('foo'));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        expect(function() { assertThat([], isOfType('int')); })
                ->throws(AssertionFailure::class)
                ->withMessage('Failed asserting that an array is of type "int".');
    }
}
