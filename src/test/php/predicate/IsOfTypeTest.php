<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use function bovigo\assert\assert;
/**
 * Tests for bovigo\assert\predicate\IsOfType.
 *
 * @group  predicate
 */
class IsOfTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException  InvalidArgumentException
     */
    public function throwsInvalidArgumentExceptionWhenCreatedWithUnknownType()
    {
        new IsOfType('nope');
    }

    /**
     * @return  array
     */
    public function validValuesAndTypes()
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
        assert(isOfType($expectedType)->test($value), isTrue());
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfTypeOfValueDoesNotEqualExpectedType()
    {
        assert(isOfType('int')->test('foo'), isFalse());
    }
}
