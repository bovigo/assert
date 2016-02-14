<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\phpunit;
use bovigo\assert\AssertionFailure;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\throws;
/**
 * Test for bovigo\assert\assert\predicate\AndPredicate.
 *
 * @group  phpunit
 */
class CompatibilityTest extends PHPUnit_Framework_TestCase
{
    public function testAssertArrayHasKeySuccess()
    {
        $this->assertArrayHasKey('foo', ['foo' => 303]);
    }

    public function testAssertArrayHasKeyFailure()
    {
        assert(
                function() { $this->assertArrayHasKey('bar', ['foo' => 303]); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that an array has the key 'bar'."
                )
        );
    }

    public function testAssertArrayNotHasKeySuccess()
    {
        $this->assertArrayNotHasKey('bar', ['foo' => 303]);
    }

    public function testAssertArrayNotHasKeyFailure()
    {
        assert(
                function() { $this->assertArrayNotHasKey('foo', ['foo' => 303]); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that an array does not have the key 'foo'."
                )
        );
    }

    public function testAssertContainsSuccess()
    {
        $this->assertContains(303, ['foo' => 303]);
    }

    public function testAssertContainsFailure()
    {
        assert(
                function() { $this->assertContains(313, ['foo' => 303]); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that an array contains 313."
                )
        );
    }

    public function testAssertNotContainsSuccess()
    {
        $this->assertNotContains(313, ['foo' => 303]);
    }

    public function testAssertNotContainsFailure()
    {
        assert(
                function() { $this->assertNotContains(303, ['foo' => 303]); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that an array does not contain 303."
                )
        );
    }

    /**
     * @since  1.1.0
     */
    public function testAssertContainsOnlySuccessForNativeTypes()
    {
        $this->assertContainsOnly('int', [303, 313]);
    }

    /**
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
     * @since  1.1.0
     */
    public function testAssertContainsOnlyFailure()
    {
        assert(
                function() { $this->assertContainsOnly('int', [303, 'foo']); },
                throws(AssertionFailure::class)->withMessage(
                        'Failed asserting that in Array &0 (
    0 => 303
    1 => \'foo\'
) each value is of type "int".'
                )
        );
    }

    /**
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
     * @since  1.1.0
     */
    public function testAssertContainsOnlyInstancesOfFailure()
    {
        assert(
                function()
                {
                    $this->assertContainsOnlyInstancesOf('stdClass', [303, 'foo']);
                },
                throws(AssertionFailure::class)->withMessage(
                        'Failed asserting that in Array &0 (
    0 => 303
    1 => \'foo\'
) each value is an instance of class "stdClass".'
                )
        );
    }

    /**
     * @since  1.1.0
     */
    public function testAssertNotContainsOnlySuccessForNativeTypes()
    {
        $this->assertNotContainsOnly('int', ['foo', 'bar']);
    }

    /**
     * @since  1.1.0
     */
    public function testAssertNotContainsOnlySuccessForNonNativeTypes()
    {
        $this->assertNotContainsOnly('stdClass', ['foo', 'bar']);
    }

    /**
     * @since  1.1.0
     */
    public function testAssertNotContainsOnlyFailure()
    {
        assert(
                function()
                {
                    $this->assertNotContainsOnly('int', [303, 'foo']);
                },
                throws(AssertionFailure::class)->withMessage(
                        'Failed asserting that in Array &0 (
    0 => 303
    1 => \'foo\'
) each value is not of type "int".'
                )
        );
    }

    public function testAssertCountSuccess()
    {
        $this->assertCount(1, ['foo' => 303]);
    }

    public function testAssertCountFailure()
    {
        assert(
                function() { $this->assertCount(2, ['foo' => 303]); },
                throws(AssertionFailure::class)->withMessage(
                        'Failed asserting that array with actual size 1 matches expected size 2.'
                )
        );
    }

    public function testAssertNotCountSuccess()
    {
        $this->assertNotCount(2, ['foo' => 303]);
    }

    public function testAssertNotCountFailure()
    {
        assert(
                function() { $this->assertNotCount(1, ['foo' => 303]); },
                throws(AssertionFailure::class)->withMessage(
                        'Failed asserting that array with actual size 1 does not match expected size 1.'
                )
        );
    }

    public function testAssertEqualsSuccess()
    {
        $this->assertEquals(303, 303);
    }

    public function testAssertEqualsFailure()
    {
        assert(
                function() { $this->assertEquals(313, 303); },
                throws(AssertionFailure::class)->withMessage(
                        'Failed asserting that 303 is equal to 313.'
                )
        );
    }

    public function testAssertNotEqualsSuccess()
    {
        $this->assertNotEquals(313, 303);
    }

    public function testAssertNotEqualsFailure()
    {
        assert(
                function() { $this->assertNotEquals(303, 303); },
                throws(AssertionFailure::class)->withMessage(
                        'Failed asserting that 303 is not equal to 303.'
                )
        );
    }

    public function testAssertEmptySuccess()
    {
        $this->assertEmpty('');
    }

    public function testAssertEmptyFailure()
    {
        assert(
                function() { $this->assertEmpty('not empty'); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that 'not empty' is empty."
                )
        );
    }

    public function testAssertNotEmptySuccess()
    {
        $this->assertNotEmpty('not empty');
    }

    public function testAssertNotEmptyFailure()
    {
        assert(
                function() { $this->assertNotEmpty(''); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that '' is not empty."
                )
        );
    }

    public function testAssertGreaterThanSuccess()
    {
        $this->assertGreaterThan(3, 4);
    }

    public function testAssertGreaterThanFailure()
    {
        assert(
                function() { $this->assertGreaterThan(3, 2); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that 2 is greater than 3."
                )
        );
    }

    public function testAssertGreaterThanOrEqualSuccess()
    {
        $this->assertGreaterThanOrEqual(3, 4);
    }

    public function testAssertGreaterThanOrEqualFailure()
    {
        assert(
                function() { $this->assertGreaterThanOrEqual(3, 2); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that 2 is equal to 3 or is greater than 3."
                )
        );
    }

    public function testAssertLessThanSuccess()
    {
        $this->assertLessThan(4, 3);
    }

    public function testAssertLessThanFailure()
    {
        assert(
                function() { $this->assertLessThan(2, 3); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that 3 is less than 2."
                )
        );
    }

    public function testAssertLessThanOrEqualSuccess()
    {
        $this->assertLessThanOrEqual(4, 3);
    }

    public function testAssertLessThanOrEqualFailure()
    {
        assert(
                function() { $this->assertLessThanOrEqual(2, 3); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that 3 is equal to 2 or is less than 2."
                )
        );
    }

    public function testAssertFileExistsSuccess()
    {
        $this->assertFileExists(__FILE__);
    }

    public function testAssertFileExistsFailure()
    {
        assert(
                function() { $this->assertFileExists(__FILE__ . '.bak'); },
                throws(AssertionFailure::class)->withMessage(
                        "is a existing file"
                )
        );
    }

    public function testAssertFileNotExistsSuccess()
    {
        $this->assertFileNotExists(__FILE__ . '.bak');
    }

    public function testAssertFileNotExistsFailure()
    {
        assert(
                function() { $this->assertFileNotExists(__FILE__); },
                throws(AssertionFailure::class)->withMessage(
                        "is not a existing file"
                )
        );
    }

    public function testAssertTrueSuccess()
    {
        $this->assertTrue(true);
    }

    public function testAssertTrueFailure()
    {
        assert(
                function() { $this->assertTrue(false); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that false is true."
                )
        );
    }

    public function testAssertFalseSuccess()
    {
        $this->assertFalse(false);
    }

    public function testAssertFalseFailure()
    {
        assert(
                function() { $this->assertFalse(true); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that true is false."
                )
        );
    }

    public function testAssertNotNullSuccess()
    {
        $this->assertNotNull(303);
    }

    public function testAssertNotNullFailure()
    {
        assert(
                function() { $this->assertNotNull(null); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that null is not null."
                )
        );
    }

    public function testAssertNullSuccess()
    {
        $this->assertNull(null);
    }

    public function testAssertNullFailure()
    {
        assert(
                function() { $this->assertNull(303); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that 303 is null."
                )
        );
    }

    /**
     * @since  1.1.0
     */
    public function testAssertFiniteSuccess()
    {
        $this->assertFinite(1);
    }

    /**
     * @since  1.1.0
     */
    public function testAssertFiniteFailure()
    {
        assert(
                function() { $this->assertFinite(INF); },
                throws(AssertionFailure::class)->withMessage(
                        "satisfies is_finite()."
                )
        );
    }

    /**
     * @since  1.1.0
     */
    public function testAssertInfiniteSuccess()
    {
        $this->assertInfinite(INF);
    }

    /**
     * @since  1.1.0
     */
    public function testAssertInfiniteFailure()
    {
        assert(
                function() { $this->assertInfinite(1); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that 1 satisfies is_infinite()."
                )
        );
    }

    /**
     * @since  1.1.0
     */
    public function testAssertNanSuccess()
    {
        $this->assertNan(NAN);
    }

    /**
     * @since  1.1.0
     */
    public function testAssertNanFailure()
    {
        assert(
                function() { $this->assertNan(1); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that 1 satisfies is_nan()."
                )
        );
    }

    public function testAssertSameSuccess()
    {
        $foo = new \stdClass();
        $this->assertSame($foo, $foo);
    }

    public function testAssertSameFailure()
    {
        assert(
                function() { $this->assertSame(new \stdClass(), new \stdClass()); },
                throws(AssertionFailure::class)->withMessage(
                        'Failed asserting that object of type "stdClass" is identical to object of type "stdClass".'
                )
        );
    }

    public function testAssertNotSameSuccess()
    {
        $this->assertNotSame(new \stdClass(), new \stdClass());
    }

    public function testAssertNotSameFailure()
    {
        assert(
                function()
                {
                    $foo = new \stdClass();
                    $this->assertNotSame($foo, $foo);
                },
                throws(AssertionFailure::class)->withMessage(
                        'Failed asserting that object of type "stdClass" is not identical to object of type "stdClass".'
                )
        );
    }

    public function testAssertInstanceOfSuccess()
    {
        $this->assertInstanceOf(\stdClass::class, new \stdClass());
    }

    public function testAsserInstanceOfFailure()
    {
        assert(
                function() { $this->assertInstanceOf(__CLASS__, new \stdClass()); },
                throws(AssertionFailure::class)->withMessage(
                        'Failed asserting that stdClass Object () is an instance of class "' . __CLASS__ . '".'
                )
        );
    }


    public function testAssertNotInstanceOfSuccess()
    {
        $this->assertNotInstanceOf(__CLASS__, new \stdClass());
    }

    public function testAssertNotInstanceOfFailure()
    {
        assert(
                function() { $this->assertNotInstanceOf(\stdClass::class, new \stdClass()); },
                throws(AssertionFailure::class)->withMessage(
                        'Failed asserting that stdClass Object () is not an instance of class "stdClass".'
                )
        );
    }

    public function testAssertInternalTypeSuccess()
    {
        $this->assertInternalType('string', 'foo');
    }

    public function testAssertInternalTypeFailure()
    {
        assert(
                function() { $this->assertInternalType('string', 303); },
                throws(AssertionFailure::class)->withMessage(
                        'Failed asserting that 303 is of type "string".'
                )
        );
    }

    public function testAssertNotInternalTypeSuccess()
    {
        $this->assertNotInternalType('string', 303);
    }

    public function testAssertNotInternalTypeFailure()
    {
        assert(
                function() { $this->assertNotInternalType('string', 'foo'); },
                throws(AssertionFailure::class)->withMessage(
                        'Failed asserting that \'foo\' is not of type "string".'
                )
        );
    }

    public function testAssertRegExpSuccess()
    {
        $this->assertRegExp('/^([a-z]{3})$/', 'foo');
    }

    public function testAssertRegExpFailure()
    {
        assert(
                function() { $this->assertRegExp('/^([a-z]{3})$/', 'dummy'); },
                throws(AssertionFailure::class)->withMessage(
                        'Failed asserting that \'dummy\' matches regular expression "/^([a-z]{3})$/".'
                )
        );
    }

    public function testAssertNotRegExpSuccess()
    {
        $this->assertNotRegExp('/^([a-z]{3})$/', 'dummy');
    }

    public function testAssertNotRegExNotpFailure()
    {
        assert(
                function() { $this->assertNotRegExp('/^([a-z]{3})$/', 'foo'); },
                throws(AssertionFailure::class)->withMessage(
                        'Failed asserting that \'foo\' does not match regular expression "/^([a-z]{3})$/".'
                )
        );
    }

    /**
     * @since  1.1.0
     */
    public function testAssertStringStartsWithSuccess()
    {
        $this->assertStringStartsWith('foo', 'foobarbaz');
    }

    /**
     * @since  1.1.0
     */
    public function testAssertStringStartsWithFailure()
    {
        assert(
                function() { $this->assertStringStartsWith('foo', 'barbazfoo'); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that 'barbazfoo' starts with 'foo'."
                )
        );
    }

    /**
     * @since  1.1.0
     */
    public function testAssertStringStartsNotWithSuccess()
    {
        $this->assertStringStartsNotWith('foo', 'barbazfoo');
    }

    /**
     * @since  1.1.0
     */
    public function testAssertStringStartsNotWithFailure()
    {
        assert(
                function() { $this->assertStringStartsNotWith('foo', 'foobarbaz'); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that 'foobarbaz' does not start with 'foo'."
                )
        );
    }

    /**
     * @since  1.1.0
     */
    public function testAssertStringEndsWithSuccess()
    {
        $this->assertStringEndsWith('foo', 'barbazfoo');
    }

    /**
     * @since  1.1.0
     */
    public function testAssertStringEndsWithFailure()
    {
        assert(
                function() { $this->assertStringEndsWith('foo', 'foobarbaz'); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that 'foobarbaz' ends with 'foo'."
                )
        );
    }

    /**
     * @since  1.1.0
     */
    public function testAssertStringEndsNotWithSuccess()
    {
        $this->assertStringEndsNotWith('foo', 'foobarbaz');
    }

    /**
     * @since  1.1.0
     */
    public function testAssertStringEndsNotWithFailure()
    {
        assert(
                function() { $this->assertStringEndsNotWith('foo', 'barbazfoo'); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that 'barbazfoo' does not end with 'foo'."
                )
        );
    }
}
