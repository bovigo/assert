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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

use function bovigo\assert\assertThat;
use function bovigo\assert\expect;
use function bovigo\assert\predicate\isObject;
use function bovigo\assert\predicate\isNotAnObject;
/**
 * Test for bovigo\assert\assert\predicate\isObject() and bovigo\assert\assert\predicate\isNotAnObject().
 *
 * @group predicate
 */
class IsObjectTest extends TestCase
{
    public static function validObjects(): Generator
    {
        $a = new class() {};
        yield 'stdclass'        => [new stdClass()];
        yield'anonymous class' => [$a];
    }

    public static function invalidObjects(): Generator
    {
        yield'string' => ['foo'];
        yield'float'  => [30.3];
        yield'array'  => [[new stdClass()]];
    }

    #[Test]
    #[DataProvider('validObjects')]
    public function validObjectsAreRecognized(object $value): void
    {
        assertThat($value, isObject());
    }

    #[Test]
    #[DataProvider('invalidObjects')]
    public function invalidObjectsAreRejected(mixed $value): void
    {
        expect(fn() => assertThat($value, isObject()))
            ->throws(AssertionFailure::class);
    }

    #[Test]
    #[DataProvider('invalidObjects')]
    public function invalidObjectsAreRecognizedOnNegation(mixed $value): void
    {
        assertThat($value, isNotAnObject());
    }

    #[Test]
    #[DataProvider('validObjects')]
    public function validObjectsAreRejectedOnNegation(object $value): void
    {
        expect(fn() => assertThat($value, isNotAnObject()))
            ->throws(AssertionFailure::class);
    }
}