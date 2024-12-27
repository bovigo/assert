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

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\IsIdentical.
 */
#[Group('predicate')]
class IsIdenticalTest extends TestCase
{
    public static function provideIdenticalValues(): iterable
    {
        yield 'boolean true'  => [true];
        yield 'boolean false' => [false];
        yield 'string'        => ['foo'];
        yield 'number'        => [303];
        yield 'object'        => [new \stdClass()];
        yield 'float'         => [3.03];
    }

    #[Test]
    #[DataProvider('provideIdenticalValues')]
    public function evaluatesToTrueIfGivenValueIsIdentical(mixed $value): void
    {
        assertTrue(isSameAs($value)->test($value));
    }

    #[Test]
    public function evaluatesToFalseIfGivenValueIsNotIdentical(): void
    {
        assertFalse(isSameAs(3.03)->test(3.02));
    }

    #[Test]
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat(true, isSameAs(false)))
            ->throws(AssertionFailure::class)
            ->withMessage("Failed asserting that true is identical to false.");
    }

    #[Test]
    public function assertionFailureWithObjectsContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat(new stdClass(), isSameAs(new stdClass())))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that object of type "stdClass" is identical to object of type "stdClass".'
            );
    }

    #[Test]
    public function assertionFailureWithObjectAndOtherContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat(new stdClass(), isSameAs('foo')))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that object of type "stdClass" is identical to \'foo\'.'
            );
    }
}
