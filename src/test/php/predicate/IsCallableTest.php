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
use function bovigo\assert\predicate\isCallable;
use function bovigo\assert\predicate\isNotCallable;

/**
 * Test for bovigo\assert\assert\predicate\isCallable() and bovigo\assert\assert\predicate\isNotCallable().
 *
 * @group  predicate
 */
class IsCallableTest extends TestCase
{
    public static function exampleStaticCallable() {}
    public function exampleCallable() {}

    public function validCallables(): array
    {
        return [
            'anonymous function' => [function() {}],
            'string callable'    => ['is_callable'],
            'static callable'    => [[__CLASS__, 'exampleStaticCallable']],
            'callable on object' => [[$this, 'exampleCallable']]
        ];
    }

    public function invalidCallables(): array
    {
        return [
            'array'  => [['foo']],
            'float'  => [30.3],
            'object' => [new \stdClass()]
        ];
    }

    /**
     * @param  callable  $value
     * @test
     * @dataProvider  validCallables
     */
    public function validCallablesAreRecognized(callable $value)
    {
        assertThat($value, isCallable());
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  invalidCallables
     */
    public function invalidCallablesAreRejected($value)
    {
        expect(function() use($value) { assertThat($value, isCallable()); })
            ->throws(AssertionFailure::class);
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  invalidCallables
     */
    public function invalidCallablesAreRecognizedOnNegation($value)
    {
        assertThat($value, isNotCallable());
    }

    /**
     * @param  callable  $value
     * @test
     * @dataProvider  validCallables
     */
    public function validCallablesAreRejectedOnNegation(callable $value)
    {
        expect(function() use($value) { assertThat($value, isNotCallable()); })
            ->throws(AssertionFailure::class);
    }
}