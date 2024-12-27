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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

use function bovigo\assert\assertThat;
use function bovigo\assert\expect;
use function bovigo\assert\predicate\isObject;
use function bovigo\assert\predicate\isNotAnObject;
/**
 * Test for bovigo\assert\assert\predicate\isObject() and bovigo\assert\assert\predicate\isNotAnObject().
 */
#[Group('predicate')]
class IsObjectTest extends TestCase
{
    public static function provideValidObjects(): iterable
    {
        $a = new class() {};
        yield 'stdclass'        => [new stdClass()];
        yield'anonymous class' => [$a];
    }

    public static function provideInvalidObjects(): iterable
    {
        yield'string' => ['foo'];
        yield'float'  => [30.3];
        yield'array'  => [[new stdClass()]];
    }

    #[Test]
    #[DataProvider('provideValidObjects')]
    public function validObjectsAreRecognized(object $value): void
    {
        assertThat($value, isObject());
    }

    #[Test]
    #[DataProvider('provideInvalidObjects')]
    public function invalidObjectsAreRejected(mixed $value): void
    {
        expect(fn() => assertThat($value, isObject()))
            ->throws(AssertionFailure::class);
    }

    #[Test]
    #[DataProvider('provideInvalidObjects')]
    public function invalidObjectsAreRecognizedOnNegation(mixed $value): void
    {
        assertThat($value, isNotAnObject());
    }

    #[Test]
    #[DataProvider('provideValidObjects')]
    public function validObjectsAreRejectedOnNegation(object $value): void
    {
        expect(fn() => assertThat($value, isNotAnObject()))
            ->throws(AssertionFailure::class);
    }
}