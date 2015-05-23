<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Tests for bovigo\assert\predicate\IsIpV4Address.
 *
 * @group  predicate
 */
class IsIpV4AddressTest extends \PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @type  IsIpV4Address
     */
    private $isIpV4Address;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->isIpV4Address = new IsIpV4Address();
    }

    /**
     * @test
     */
    public function stringIsNoIpAndEvaluatesToFalse()
    {
        assertFalse($this->isIpV4Address->test('foo'));
    }

    /**
     * @test
     */
    public function nullIsNoIpAndEvaluatesToFalse()
    {
        assertFalse($this->isIpV4Address->test(null));
    }

    /**
     * @test
     */
    public function emptyStringIsNoIpAndEvaluatesToFalse()
    {
        assertFalse($this->isIpV4Address->test(''));
    }

    /**
     * @test
     */
    public function booleansAreNoIpAndResultInFalse()
    {
        assertFalse($this->isIpV4Address->test(true));
        assertFalse($this->isIpV4Address->test(false));
    }

    /**
     * @test
     */
    public function singleNumbersAreNoIpAndResultInFalse()
    {
        assertFalse($this->isIpV4Address->test(4));
        assertFalse($this->isIpV4Address->test(6));
    }

    /**
     * @test
     */
    public function invalidIpWithMissingPartEvaluatesToFalse()
    {
        assertFalse($this->isIpV4Address->test('255.55.55'));
    }

    /**
     * @test
     */
    public function invalidIpWithSuperflousPartEvaluatesToFalse()
    {
        assertFalse($this->isIpV4Address->test('111.222.333.444.555'));
    }

    /**
     * @test
     */
    public function invalidIpWithMissingNumberEvaluatesToFalse()
    {
        assertFalse($this->isIpV4Address->test('1..3.4'));
    }

    /**
     * @test
     */
    public function greatestIpV4EvaluatesToTrue()
    {
        assertTrue($this->isIpV4Address->test('255.255.255.255'));
    }

    /**
     * @test
     */
    public function lowestIpV4EvaluatesToTrue()
    {
        assertTrue($this->isIpV4Address->test('0.0.0.0'));
    }

    /**
     * @test
     */
    public function correctIpEvaluatesToTrue()
    {
        assertTrue($this->isIpV4Address->test('1.2.3.4'));
    }
}
