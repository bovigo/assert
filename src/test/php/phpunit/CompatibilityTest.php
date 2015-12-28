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
use function bovigo\assert\predicate\equals;
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

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that an array has the key 'bar'.
     */
    public function testAssertArrayHasKeyFailure()
    {
        $this->assertArrayHasKey('bar', ['foo' => 303]);
    }

    public function testAssertArrayNotHasKeySuccess()
    {
        $this->assertArrayNotHasKey('bar', ['foo' => 303]);
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that an array does not have the key 'foo'.
     */
    public function testAssertArrayNotHasKeyFailure()
    {
        $this->assertArrayNotHasKey('foo', ['foo' => 303]);
    }

    public function testAssertContainsSuccess()
    {
        $this->assertContains(303, ['foo' => 303]);
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that an array contains 313.
     */
    public function testAssertContainsFailure()
    {
        $this->assertContains(313, ['foo' => 303]);
    }

    public function testAssertNotContainsSuccess()
    {
        $this->assertNotContains(313, ['foo' => 303]);
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that an array does not contain 303.
     */
    public function testAssertNotContainsFailure()
    {
        $this->assertNotContains(303, ['foo' => 303]);
    }

    public function testAssertContainsOnlySuccessForNativeTypes()
    {
        $this->assertContainsOnly('int', [303, 313]);
    }

    public function testAssertContainsOnlySuccessForNonNativeTypes()
    {
        $this->assertContainsOnly(
                'stdClass',
                [new \stdClass(), new \stdClass()]
        );
    }

    public function testAssertContainsOnlyFailure()
    {
        try {
            $this->assertContainsOnly('int', [303, 'foo']);
            $this->fail('Expected ' . AssertionFailure::class . ' but got none.');
        } catch (AssertionFailure $af) {
            assert(
                    $af->getMessage(),
                    equals('Failed asserting that in Array &0 (
    0 => 303
    1 => \'foo\'
) each value is of type "int".')
            );
        }
    }

    public function testAssertContainsOnlyInstancesOfSuccess()
    {
        $this->assertContainsOnlyInstancesOf(
                'stdClass',
                [new \stdClass(), new \stdClass()]
        );
    }

    public function testAssertContainsOnlyInstancesOfFailure()
    {
        try {
            $this->assertContainsOnlyInstancesOf('stdClass', [303, 'foo']);
            $this->fail('Expected ' . AssertionFailure::class . ' but got none.');
        } catch (AssertionFailure $af) {
            assert(
                    $af->getMessage(),
                    equals('Failed asserting that in Array &0 (
    0 => 303
    1 => \'foo\'
) each value is an instance of class "stdClass".')
            );
        }
    }

    public function testAssertNotContainsOnlySuccessForNativeTypes()
    {
        $this->assertNotContainsOnly('int', ['foo', 'bar']);
    }

    public function testAssertNotContainsOnlySuccessForNonNativeTypes()
    {
        $this->assertNotContainsOnly('stdClass', ['foo', 'bar']);
    }

    public function testAssertNotContainsOnlyFailure()
    {
        try {
            $this->assertNotContainsOnly('int', [303, 'foo']);
            $this->fail('Expected ' . AssertionFailure::class . ' but got none.');
        } catch (AssertionFailure $af) {
            assert(
                    $af->getMessage(),
                    equals('Failed asserting that in Array &0 (
    0 => 303
    1 => \'foo\'
) each value is not of type "int".')
            );
        }
    }

    public function testAssertCountSuccess()
    {
        $this->assertCount(1, ['foo' => 303]);
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that array with actual size 1 matches expected size 2.
     */
    public function testAssertCountFailure()
    {
        $this->assertCount(2, ['foo' => 303]);
    }

    public function testAssertNotCountSuccess()
    {
        $this->assertNotCount(2, ['foo' => 303]);
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that array with actual size 1 does not match expected size 1.
     */
    public function testAssertNotCountFailure()
    {
        $this->assertNotCount(1, ['foo' => 303]);
    }

    public function testAssertEqualsSuccess()
    {
        $this->assertEquals(303, 303);
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 303 is equal to 313.
     */
    public function testAssertEqualsFailure()
    {
        $this->assertEquals(313, 303);
    }

    public function testAssertNotEqualsSuccess()
    {
        $this->assertNotEquals(313, 303);
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 303 is not equal to 303.
     */
    public function testAssertNotEqualsFailure()
    {
        $this->assertNotEquals(303, 303);
    }

    public function testAssertEmptySuccess()
    {
        $this->assertEmpty('');
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 'not empty' is empty.
     */
    public function testAssertEmptyFailure()
    {
        $this->assertEmpty('not empty');
    }

    public function testAssertNotEmptySuccess()
    {
        $this->assertNotEmpty('not empty');
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that '' is not empty.
     */
    public function testAssertNotEmptyFailure()
    {
        $this->assertNotEmpty('');
    }

    public function testAssertGreaterThanSuccess()
    {
        $this->assertGreaterThan(3, 4);
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 2 is greater than 3.
     */
    public function testAssertGreaterThanFailure()
    {
        $this->assertGreaterThan(3, 2);
    }

    public function testAssertGreaterThanOrEqualSuccess()
    {
        $this->assertGreaterThanOrEqual(3, 4);
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 2 is equal to 3 or is greater than 3.
     */
    public function testAssertGreaterThanOrEqualFailure()
    {
        $this->assertGreaterThanOrEqual(3, 2);
    }

    public function testAssertLessThanSuccess()
    {
        $this->assertLessThan(4, 3);
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 3 is less than 2.
     */
    public function testAssertLessThanFailure()
    {
        $this->assertLessThan(2, 3);
    }

    public function testAssertLessThanOrEqualSuccess()
    {
        $this->assertLessThanOrEqual(4, 3);
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 3 is equal to 2 or is less than 2.
     */
    public function testAssertLessThanOrEqualFailure()
    {
        $this->assertLessThanOrEqual(2, 3);
    }

    public function testAssertFileExistsSuccess()
    {
        $this->assertFileExists(__FILE__);
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  is a existing file
     */
    public function testAssertFileExistsFailure()
    {
        $this->assertFileExists(__FILE__ . '.bak');
    }

    public function testAssertFileNotExistsSuccess()
    {
        $this->assertFileNotExists(__FILE__ . '.bak');
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  is not a existing file
     */
    public function testAssertFileNotExistsFailure()
    {
        $this->assertFileNotExists(__FILE__);
    }

    public function testAssertTrueSuccess()
    {
        $this->assertTrue(true);
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that false is true.
     */
    public function testAssertTrueFailure()
    {
        $this->assertTrue(false);
    }

    public function testAssertFalseSuccess()
    {
        $this->assertFalse(false);
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that true is false.
     */
    public function testAssertFalseFailure()
    {
        $this->assertFalse(true);
    }

    public function testAssertNotNullSuccess()
    {
        $this->assertNotNull(303);
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that null is not null.
     */
    public function testAssertNotNullFailure()
    {
        $this->assertNotNull(null);
    }

    public function testAssertNullSuccess()
    {
        $this->assertNull(null);
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 303 is null.
     */
    public function testAssertNullFailure()
    {
        $this->assertNull(303);
    }

    public function testAssertSameSuccess()
    {
        $foo = new \stdClass();
        $this->assertSame($foo, $foo);
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that object of type "stdClass" is identical to object of type "stdClass".
     */
    public function testAssertSameFailure()
    {
        $this->assertSame(new \stdClass(), new \stdClass());
    }

    public function testAssertNotSameSuccess()
    {
        $this->assertNotSame(new \stdClass(), new \stdClass());
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that object of type "stdClass" is not identical to object of type "stdClass".
     */
    public function testAssertNotSameFailure()
    {
        $foo = new \stdClass();
        $this->assertNotSame($foo, $foo);
    }

    public function testAssertInstanceOfSuccess()
    {
        $this->assertInstanceOf(\stdClass::class, new \stdClass());
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that stdClass Object () is an instance of class "bovigo\assert\phpunit\CompatibilityTest".
     */
    public function testAsserInstanceOfFailure()
    {
        $this->assertInstanceOf(__CLASS__, new \stdClass());
    }


    public function testAssertNotInstanceOfSuccess()
    {
        $this->assertNotInstanceOf(__CLASS__, new \stdClass());
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that stdClass Object () is not an instance of class "stdClass".
     */
    public function testAssertNotInstanceOfFailure()
    {
        $this->assertNotInstanceOf(\stdClass::class, new \stdClass());
    }

    public function testAssertInternalTypeSuccess()
    {
        $this->assertInternalType('string', 'foo');
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 303 is of type "string".
     */
    public function testAssertInternalTypeFailure()
    {
        $this->assertInternalType('string', 303);
    }

    public function testAssertNotInternalTypeSuccess()
    {
        $this->assertNotInternalType('string', 303);
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 'foo' is not of type "string".
     */
    public function testAssertNotInternalTypeFailure()
    {
        $this->assertNotInternalType('string', 'foo');
    }

    public function testAssertRegExpSuccess()
    {
        $this->assertRegExp('/^([a-z]{3})$/', 'foo');
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 'dummy' matches regular expression "/^([a-z]{3})$/".
     */
    public function testAssertRegExpFailure()
    {
        $this->assertRegExp('/^([a-z]{3})$/', 'dummy');
    }

    public function testAssertNotRegExpSuccess()
    {
        $this->assertNotRegExp('/^([a-z]{3})$/', 'dummy');
    }

    /**
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 'foo' does not match regular expression "/^([a-z]{3})$/".
     */
    public function testAssertNotRegExpFailure()
    {
        $this->assertNotRegExp('/^([a-z]{3})$/', 'foo');
    }
}
