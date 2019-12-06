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
use function bovigo\assert\predicate\isObject;
use function bovigo\assert\predicate\isNotAnObject;

/**
 * Test for bovigo\assert\assert\predicate\isObject() and bovigo\assert\assert\predicate\isNotAnObject().
 *
 * @group  predicate
 */
class IsObjectTest extends TestCase
{
    /**
     * @return  array<string,array<object>>
     */
    public function validObjects(): array
    {
        return [
            'stdclass'        => [new \stdClass()],
            'anonymous class' => [new class() {}]
        ];
    }

    /**
     * @return  array<string,array<mixed>>
     */
    public function invalidObjects(): array
    {
        return [
            'string' => ['foo'],
            'float'  => [30.3],
            'array'  => [[new \stdClass()]]
        ];
    }

    /**
     * @param  object  $value
     * @test
     * @dataProvider  validObjects
     */
    public function validObjectsAreRecognized(object $value): void
    {
        assertThat($value, isObject());
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  invalidObjects
     */
    public function invalidObjectsAreRejected($value): void
    {
        expect(function() use($value) { assertThat($value, isObject()); })
            ->throws(AssertionFailure::class);
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  invalidObjects
     */
    public function invalidObjectsAreRecognizedOnNegation($value): void
    {
        assertThat($value, isNotAnObject());
    }

    /**
     * @param  object  $value
     * @test
     * @dataProvider  validObjects
     */
    public function validObjectsAreRejectedOnNegation(object $value): void
    {
        expect(function() use($value) { assertThat($value, isNotAnObject()); })
            ->throws(AssertionFailure::class);
    }
}