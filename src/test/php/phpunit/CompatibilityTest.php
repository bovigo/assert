<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\phpunit;
use bovigo\assert\AssertionFailure;

use function bovigo\assert\expect;
use function bovigo\assert\predicate\contains;
/**
 * Test for bovigo\assert\assert\predicate\AndPredicate.
 *
 * @group  phpunit
 */
class CompatibilityTest extends TestCase
{
    /**
     * @test
     */
    public function testAssertArrayHasKeySuccess()
    {
        $this->assertArrayHasKey('foo', ['foo' => 303]);
    }

    /**
     * @test
     */
    public function testAssertArrayHasKeyFailure()
    {
        expect(function() { $this->assertArrayHasKey('bar', ['foo' => 303]); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that an array has the key 'bar'.");
    }

    /**
     * @test
     */
    public function testAssertArrayNotHasKeySuccess()
    {
        $this->assertArrayNotHasKey('bar', ['foo' => 303]);
    }

    /**
     * @test
     */
    public function testAssertArrayNotHasKeyFailure()
    {
        expect(function() {
            $this->assertArrayNotHasKey('foo', ['foo' => 303]);
        })
        ->throws(AssertionFailure::class)
        ->withMessage("Failed asserting that an array does not have the key 'foo'.");
    }

    /**
     * @test
     */
    public function testAssertContainsSuccess()
    {
        $this->assertContains(303, ['foo' => 303]);
    }

    /**
     * @test
     */
    public function testAssertContainsFailure()
    {
        expect(function() { $this->assertContains(313, ['foo' => 303]); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that an array contains 313.");
    }

    /**
     * @test
     */
    public function testAssertNotContainsSuccess()
    {
        $this->assertNotContains(313, ['foo' => 303]);
    }

    /**
     * @test
     */
    public function testAssertNotContainsFailure()
    {
        expect(function() { $this->assertNotContains(303, ['foo' => 303]); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that an array does not contain 303.");
    }

    /**
     * @test
     * @since  1.1.0
     */
    public function testAssertContainsOnlySuccessForNativeTypes()
    {
        $this->assertContainsOnly('int', [303, 313]);
    }

    /**
     * @test
     * @since  1.1.0
     */
    public function testAssertContainsOnlySuccessForNonNativeTypes()
    {
        $this->assertContainsOnly(
                'stdClass',
                [new \stdClass(), new \stdClass()]
        );
    }

    /**
     * @test
     * @since  1.1.0
     */
    public function testAssertContainsOnlyFailure()
    {
        expect(function() { $this->assertContainsOnly('int', [303, 'foo']); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        'Failed asserting that element \'foo\' at key 1 in Array &0 (
    0 => 303
    1 => \'foo\'
) is of type "int".'
        );
    }

    /**
     * @test
     * @since  1.1.0
     */
    public function testAssertContainsOnlyInstancesOfSuccess()
    {
        $this->assertContainsOnlyInstancesOf(
                'stdClass',
                [new \stdClass(), new \stdClass()]
        );
    }

    /**
     * @test
     * @since  1.1.0
     */
    public function testAssertContainsOnlyInstancesOfFailure()
    {
        expect(function() {
                $this->assertContainsOnlyInstancesOf('stdClass', [303, 'foo']);
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that element 303 at key 0 in Array &0 (
    0 => 303
    1 => \'foo\'
) is an instance of class "stdClass".'
        );
    }

    /**
     * @test
     * @since  1.1.0
     */
    public function testAssertNotContainsOnlySuccessForNativeTypes()
    {
        $this->assertNotContainsOnly('int', ['foo', 'bar']);
    }

    /**
     * @test
     * @since  1.1.0
     */
    public function testAssertNotContainsOnlySuccessForNonNativeTypes()
    {
        $this->assertNotContainsOnly('stdClass', ['foo', 'bar']);
    }

    /**
     * @test
     * @since  1.1.0
     */
    public function testAssertNotContainsOnlyFailure()
    {
        expect(function() {
                $this->assertNotContainsOnly('int', [303, 'foo']);
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that element 303 at key 0 in Array &0 (
    0 => 303
    1 => \'foo\'
) is not of type "int".'
        );
    }

    /**
     * @test
     */
    public function testAssertCountSuccess()
    {
        $this->assertCount(1, ['foo' => 303]);
    }

    /**
     * @test
     */
    public function testAssertCountFailure()
    {
        expect(function() { $this->assertCount(2, ['foo' => 303]); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        'Failed asserting that array with actual size 1 matches expected size 2.'
        );
    }

    /**
     * @test
     */
    public function testAssertNotCountSuccess()
    {
        $this->assertNotCount(2, ['foo' => 303]);
    }

    /**
     * @test
     */
    public function testAssertNotCountFailure()
    {
        expect(function() { $this->assertNotCount(1, ['foo' => 303]); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        'Failed asserting that array with actual size 1 does not match expected size 1.'
        );
    }

    /**
     * @test
     */
    public function testAssertEqualsSuccess()
    {
        $this->assertEquals(303, 303);
    }

    /**
     * @test
     */
    public function testAssertEqualsFailure()
    {
        expect(function() { $this->assertEquals(313, 303); })
                ->throws(AssertionFailure::class)
                ->withMessage('Failed asserting that 303 is equal to 313.');
    }

    /**
     * @test
     */
    public function testAssertNotEqualsSuccess()
    {
        $this->assertNotEquals(313, 303);
    }

    /**
     * @test
     */
    public function testAssertNotEqualsFailure()
    {
        expect(function() { $this->assertNotEquals(303, 303); })
                ->throws(AssertionFailure::class)
                ->withMessage('Failed asserting that 303 is not equal to 303.');
    }

    /**
     * @test
     */
    public function testAssertEmptySuccess()
    {
        $this->assertEmpty('');
    }

    /**
     * @test
     */
    public function testAssertEmptyFailure()
    {
        expect(function() { $this->assertEmpty('not empty'); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that 'not empty' is empty.");
    }

    /**
     * @test
     */
    public function testAssertNotEmptySuccess()
    {
        $this->assertNotEmpty('not empty');
    }

    /**
     * @test
     */
    public function testAssertNotEmptyFailure()
    {
        expect(function() { $this->assertNotEmpty(''); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that '' is not empty.");
    }

    /**
     * @test
     */
    public function testAssertGreaterThanSuccess()
    {
        $this->assertGreaterThan(3, 4);
    }

    /**
     * @test
     */
    public function testAssertGreaterThanFailure()
    {
        expect(function() { $this->assertGreaterThan(3, 2); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that 2 is greater than 3.");
    }

    /**
     * @test
     */
    public function testAssertGreaterThanOrEqualSuccess()
    {
        $this->assertGreaterThanOrEqual(3, 4);
    }

    /**
     * @test
     */
    public function testAssertGreaterThanOrEqualFailure()
    {
        expect(function() { $this->assertGreaterThanOrEqual(3, 2); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        "Failed asserting that 2 is equal to 3 or is greater than 3."
        );
    }

    /**
     * @test
     */
    public function testAssertLessThanSuccess()
    {
        $this->assertLessThan(4, 3);
    }

    /**
     * @test
     */
    public function testAssertLessThanFailure()
    {
        expect(function() { $this->assertLessThan(2, 3); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that 3 is less than 2.");
    }

    /**
     * @test
     */
    public function testAssertLessThanOrEqualSuccess()
    {
        $this->assertLessThanOrEqual(4, 3);
    }

    /**
     * @test
     */
    public function testAssertLessThanOrEqualFailure()
    {
        expect(function() { $this->assertLessThanOrEqual(2, 3); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        "Failed asserting that 3 is equal to 2 or is less than 2."
        );
    }

    /**
     * @test
     */
    public function testAssertFileExistsSuccess()
    {
        $this->assertFileExists(__FILE__);
    }

    /**
     * @test
     */
    public function testAssertFileExistsFailure()
    {
        expect(function() { $this->assertFileExists(__FILE__ . '.bak'); })
                ->throws(AssertionFailure::class)
                ->message(contains("is a existing file"));
    }

    /**
     * @test
     */
    public function testAssertFileNotExistsSuccess()
    {
        $this->assertFileNotExists(__FILE__ . '.bak');
    }

    /**
     * @test
     */
    public function testAssertFileNotExistsFailure()
    {
        expect(function() { $this->assertFileNotExists(__FILE__); })
                ->throws(AssertionFailure::class)
                ->Message(contains("is not a existing file"));
    }

    /**
     * @test
     */
    public function testAssertTrueSuccess()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function testAssertTrueFailure()
    {
        expect(function() { $this->assertTrue(false); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that false is true.");
    }

    /**
     * @test
     */
    public function testAssertFalseSuccess()
    {
        $this->assertFalse(false);
    }

    /**
     * @test
     */
    public function testAssertFalseFailure()
    {
        expect(function() { $this->assertFalse(true); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that true is false.");
    }

    /**
     * @test
     */
    public function testAssertNotNullSuccess()
    {
        $this->assertNotNull(303);
    }

    /**
     * @test
     */
    public function testAssertNotNullFailure()
    {
        expect(function() { $this->assertNotNull(null); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that null is not null.");
    }

    /**
     * @test
     */
    public function testAssertNullSuccess()
    {
        $this->assertNull(null);
    }

    /**
     * @test
     */
    public function testAssertNullFailure()
    {
        expect(function() { $this->assertNull(303); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that 303 is null.");
    }

    /**
     * @test
     * @since  1.1.0
     */
    public function testAssertFiniteSuccess()
    {
        $this->assertFinite(1.0);
    }

    /**
     * @test
     * @since  1.1.0
     */
    public function testAssertFiniteFailure()
    {
        expect(function() { $this->assertFinite(INF); })
                ->throws(AssertionFailure::class)
                ->message(contains("satisfies is_finite()."));
    }

    /**
     * @test
     * @since  1.1.0
     */
    public function testAssertInfiniteSuccess()
    {
        $this->assertInfinite(INF);
    }

    /**
     * @test
     * @since  1.1.0
     */
    public function testAssertInfiniteFailure()
    {
        expect(function() { $this->assertInfinite(1.0); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that 1.0 satisfies is_infinite().");
    }

    /**
     * @test
     * @since  1.1.0
     */
    public function testAssertNanSuccess()
    {
        $this->assertNan(NAN);
    }

    /**
     * @test
     * @since  1.1.0
     */
    public function testAssertNanFailure()
    {
        expect(function() { $this->assertNan(1.0); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that 1.0 satisfies is_nan().");
    }

    /**
     * @test
     */
    public function testAssertSameSuccess()
    {
        $foo = new \stdClass();
        $this->assertSame($foo, $foo);
    }

    /**
     * @test
     */
    public function testAssertSameFailure()
    {
        expect(function() {
            $this->assertSame(new \stdClass(), new \stdClass());
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that object of type "stdClass" is identical to object of type "stdClass".'
        );
    }

    /**
     * @test
     */
    public function testAssertNotSameSuccess()
    {
        $this->assertNotSame(new \stdClass(), new \stdClass());
    }

    /**
     * @test
     */
    public function testAssertNotSameFailure()
    {
        expect(function() {
                $foo = new \stdClass();
                $this->assertNotSame($foo, $foo);
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that object of type "stdClass" is not identical to object of type "stdClass".'
        );
    }

    /**
     * @test
     */
    public function testAssertInstanceOfSuccess()
    {
        $this->assertInstanceOf(\stdClass::class, new \stdClass());
    }

    /**
     * @test
     */
    public function testAsserInstanceOfFailure()
    {
        expect(function() {
            $this->assertInstanceOf(__CLASS__, new \stdClass());
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that stdClass Object () is an instance of class "' . __CLASS__ . '".'
        );
    }

    /**
     * @test
     */
    public function testAssertNotInstanceOfSuccess()
    {
        $this->assertNotInstanceOf(__CLASS__, new \stdClass());
    }

    /**
     * @test
     */
    public function testAssertNotInstanceOfFailure()
    {
        expect(function() {
            $this->assertNotInstanceOf(\stdClass::class, new \stdClass());
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that stdClass Object () is not an instance of class "stdClass".'
        );
    }

    /**
     * @test
     */
    public function testAssertInternalTypeSuccess()
    {
        $this->assertInternalType('string', 'foo');
    }

    /**
     * @test
     */
    public function testAssertInternalTypeFailure()
    {
        expect(function() { $this->assertInternalType('string', 303); })
                ->throws(AssertionFailure::class)
                ->withMessage('Failed asserting that 303 is of type "string".');
    }

    /**
     * @test
     */
    public function testAssertNotInternalTypeSuccess()
    {
        $this->assertNotInternalType('string', 303);
    }

    /**
     * @test
     */
    public function testAssertNotInternalTypeFailure()
    {
        expect(function() { $this->assertNotInternalType('string', 'foo'); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        'Failed asserting that \'foo\' is not of type "string".'
        );
    }

    /**
     * @test
     */
    public function testAssertRegExpSuccess()
    {
        $this->assertRegExp('/^([a-z]{3})$/', 'foo');
    }

    /**
     * @test
     */
    public function testAssertRegExpFailure()
    {
        expect(function() { $this->assertRegExp('/^([a-z]{3})$/', 'dummy'); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        'Failed asserting that \'dummy\' matches regular expression "/^([a-z]{3})$/".'
        );
    }

    /**
     * @test
     */
    public function testAssertNotRegExpSuccess()
    {
        $this->assertNotRegExp('/^([a-z]{3})$/', 'dummy');
    }

    /**
     * @test
     */
    public function testAssertNotRegExNotpFailure()
    {
        expect(function() { $this->assertNotRegExp('/^([a-z]{3})$/', 'foo'); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        'Failed asserting that \'foo\' does not match regular expression "/^([a-z]{3})$/".'
        );
    }

    /**
     * @test
     * @since  1.1.0
     */
    public function testAssertStringStartsWithSuccess()
    {
        $this->assertStringStartsWith('foo', 'foobarbaz');
    }

    /**
     * @test
     * @since  1.1.0
     */
    public function testAssertStringStartsWithFailure()
    {
        expect(function() { $this->assertStringStartsWith('foo', 'barbazfoo');})
                ->throws(AssertionFailure::class)
                ->withMessage(
                        "Failed asserting that 'barbazfoo' starts with 'foo'."
        );
    }

    /**
     * @test
     * @since  1.1.0
     */
    public function testAssertStringStartsNotWithSuccess()
    {
        $this->assertStringStartsNotWith('foo', 'barbazfoo');
    }

    /**
     * @test
     * @since  1.1.0
     */
    public function testAssertStringStartsNotWithFailure()
    {
        expect(function() {
            $this->assertStringStartsNotWith('foo', 'foobarbaz');
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                "Failed asserting that 'foobarbaz' does not start with 'foo'."
        );
    }

    /**
     * @test
     * @since  1.1.0
     */
    public function testAssertStringEndsWithSuccess()
    {
        $this->assertStringEndsWith('foo', 'barbazfoo');
    }

    /**
     * @test
     * @since  1.1.0
     */
    public function testAssertStringEndsWithFailure()
    {
        expect(function() { $this->assertStringEndsWith('foo', 'foobarbaz'); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that 'foobarbaz' ends with 'foo'.");
    }

    /**
     * @test
     * @since  1.1.0
     */
    public function testAssertStringEndsNotWithSuccess()
    {
        $this->assertStringEndsNotWith('foo', 'foobarbaz');
    }

    /**
     * @test
     * @since  1.1.0
     */
    public function testAssertStringEndsNotWithFailure()
    {
        expect(function() { $this->assertStringEndsNotWith('foo', 'barbazfoo'); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        "Failed asserting that 'barbazfoo' does not end with 'foo'."
        );
    }

    /**
     * @test
     * @since 2.0.0
     */
    public function testAssertThat()
    {
        $this->assertThat('foo', new \PHPUnit\Framework\Constraint\IsEqual('foo'));
    }

    /**
     * @test
     * @since 2.0.0
     */
    public function testAssertThatWithFailure()
    {
        expect(function() {
                $this->assertThat('foo', new \PHPUnit\Framework\Constraint\IsEqual('bar'));
        })->throws(AssertionFailure::class);
    }
}
