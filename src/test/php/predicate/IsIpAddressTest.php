<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Tests for bovigo\assert\predicate\IsIpAddress.
 *
 * @group  predicate
 */
class IsIpAddressTest extends \PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @type  IsIpAddress
     */
    protected $isIpAddress;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->isIpAddress = new IsIpAddress();
    }

    /**
     * @test
     */
    public function stringIsNoIpAndEvaluatesToFalse()
    {
        assertFalse($this->isIpAddress->test('foo'));
    }

    /**
     * @test
     */
    public function nullIsNoIpAndEvaluatesToFalse()
    {
        assertFalse($this->isIpAddress->test(null));
    }

    /**
     * @test
     */
    public function emptyStringIsNoIpAndEvaluatesToFalse()
    {
        assertFalse($this->isIpAddress->test(''));
    }

    /**
     * @test
     */
    public function booleansAreNoIpAndResultInFalse()
    {
        assertFalse($this->isIpAddress->test(true));
        assertFalse($this->isIpAddress->test(false));
    }

    /**
     * @test
     */
    public function singleNumbersAreNoIpAndResultInFalse()
    {
        assertFalse($this->isIpAddress->test(4));
        assertFalse($this->isIpAddress->test(6));
    }

    /**
     * @test
     */
    public function invalidIpV4WithMissingPartEvaluatesToFalse()
    {
        assertFalse($this->isIpAddress->test('255.55.55'));
    }

    /**
     * @test
     */
    public function invalidIpV4WithSuperflousPartEvaluatesToFalse()
    {
        assertFalse($this->isIpAddress->test('111.222.333.444.555'));
    }

    /**
     * @test
     */
    public function invalidIpV4WithMissingNumberEvaluatesToFalse()
    {
        assertFalse($this->isIpAddress->test('1..3.4'));
    }

    /**
     * @test
     */
    public function greatestIpV4EvaluatesToTrue()
    {
        assertTrue($this->isIpAddress->test('255.255.255.255'));
    }

    /**
     * @test
     */
    public function lowestIpV4EvaluatesToTrue()
    {
        assertTrue($this->isIpAddress->test('0.0.0.0'));
    }

    /**
     * @test
     */
    public function correctIpV4EvaluatesToTrue()
    {
        assertTrue($this->isIpAddress->test('1.2.3.4'));
    }

    /**
     * @test
     */
    public function invalidIpV6WithMissingPartEvaluatesToFalse()
    {
        assertFalse($this->isIpAddress->test(':1'));
    }

    /**
     * @test
     */
    public function invalidIpV6EvaluatesToFalse()
    {
        assertFalse($this->isIpAddress->test('::ffffff:::::a'));
    }

    /**
     * @test
     */
    public function invalidIpV6WithHexquadAtStartEvaluatesToFalse()
    {
        assertFalse($this->isIpAddress->test('XXXX::a574:382b:23c1:aa49:4592:4efe:9982'));
    }

    /**
     * @test
     */
    public function invalidIpV6WithHexquadAtEndEvaluatesToFalse()
    {
        assertFalse($this->isIpAddress->test('9982::a574:382b:23c1:aa49:4592:4efe:XXXX'));
    }

    /**
     * @test
     */
    public function invalidIpV6WithHexquadEvaluatesToFalse()
    {
        assertFalse($this->isIpAddress->test('a574::XXXX:382b:23c1:aa49:4592:4efe:9982'));
    }

    /**
     * @test
     */
    public function invalidIpV6WithHexDigitEvaluatesToFalse()
    {
        assertFalse($this->isIpAddress->test('a574::382X:382b:23c1:aa49:4592:4efe:9982'));
    }

    /**
     * @test
     */
    public function correctIpV6EvaluatesToTrue()
    {
        assertTrue($this->isIpAddress->test('febc:a574:382b:23c1:aa49:4592:4efe:9982'));
    }

    /**
     * @test
     */
    public function localhostIpV6EvaluatesToTrue()
    {
        assertTrue($this->isIpAddress->test('::1'));
    }

    /**
     * @test
     */
    public function shortenedIpV6EvaluatesToTrue()
    {
        assertTrue($this->isIpAddress->test('febc:a574:382b::4592:4efe:9982'));
    }

    /**
     * @test
     */
    public function evenMoreShortenedIpV6EvaluatesToTrue()
    {
        assertTrue($this->isIpAddress->test('febc::23c1:aa49:0:0:9982'));
    }

    /**
     * @test
     */
    public function singleShortenedIpV6EvaluatesToTrue()
    {
        assertTrue($this->isIpAddress->test('febc:a574:2b:23c1:aa49:4592:4efe:9982'));
    }

    /**
     * @test
     */
    public function shortenedPrefixIpV6EvaluatesToTrue()
    {
        assertTrue($this->isIpAddress->test('::382b:23c1:aa49:4592:4efe:9982'));
    }

    /**
     * @test
     */
    public function shortenedPostfixIpV6EvaluatesToTrue()
    {
        assertTrue($this->isIpAddress->test('febc:a574:382b:23c1:aa49::'));
    }
}
