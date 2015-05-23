<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Tests for bovigo\assert\predicate\IsIpV6Address.
 *
 * @group  predicate
 * @since  4.0.0
 */
class IsIpV6AddressTest extends \PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @type  IsIpV6Address
     */
    private $isIpV6Address;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->isIpV6Address = new IsIpV6Address();
    }

    /**
     * @test
     */
    public function stringIsNoIpAndEvaluatesToFalse()
    {
        assertFalse($this->isIpV6Address->test('foo'));
    }

    /**
     * @test
     */
    public function nullIsNoIpAndEvaluatesToFalse()
    {
        assertFalse($this->isIpV6Address->test(null));
    }

    /**
     * @test
     */
    public function emptyStringIsNoIpAndEvaluatesToFalse()
    {
        assertFalse($this->isIpV6Address->test(''));
    }

    /**
     * @test
     */
    public function booleansAreNoIpAndResultInFalse()
    {
        assertFalse($this->isIpV6Address->test(true));
        assertFalse($this->isIpV6Address->test(false));
    }

    /**
     * @test
     */
    public function singleNumbersAreNoIpAndResultInFalse()
    {
        assertFalse($this->isIpV6Address->test(4));
        assertFalse($this->isIpV6Address->test(6));
    }

    /**
     * @test
     */
    public function ipv4EvaluatesToFalse()
    {
        assertFalse($this->isIpV6Address->test('1.2.3.4'));
    }

    /**
     * @test
     */
    public function invalidIpWithMissingPartEvaluatesToFalse()
    {
        assertFalse($this->isIpV6Address->test(':1'));
    }

    /**
     * @test
     */
    public function invalidIpEvaluatesToFalse()
    {
        assertFalse($this->isIpV6Address->test('::ffffff:::::a'));
    }

    /**
     * @test
     */
    public function invalidIpWithHexquadAtStartEvaluatesToFalse()
    {
        assertFalse($this->isIpV6Address->test('XXXX::a574:382b:23c1:aa49:4592:4efe:9982'));
    }

    /**
     * @test
     */
    public function invalidIpWithHexquadAtEndEvaluatesToFalse()
    {
        assertFalse($this->isIpV6Address->test('9982::a574:382b:23c1:aa49:4592:4efe:XXXX'));
    }

    /**
     * @test
     */
    public function invalidIpWithHexquadEvaluatesToFalse()
    {
        assertFalse($this->isIpV6Address->test('a574::XXXX:382b:23c1:aa49:4592:4efe:9982'));
    }

    /**
     * @test
     */
    public function invalidIpWithHexDigitEvaluatesToFalse()
    {
        assertFalse($this->isIpV6Address->test('a574::382X:382b:23c1:aa49:4592:4efe:9982'));
    }

    /**
     * @test
     */
    public function correctIpEvaluatesToTrue()
    {
        assertTrue($this->isIpV6Address->test('febc:a574:382b:23c1:aa49:4592:4efe:9982'));
    }

    /**
     * @test
     */
    public function localhostIpV6EvaluatesToTrue()
    {
        assertTrue($this->isIpV6Address->test('::1'));
    }

    /**
     * @test
     */
    public function shortenedIpEvaluatesToTrue()
    {
        assertTrue($this->isIpV6Address->test('febc:a574:382b::4592:4efe:9982'));
    }

    /**
     * @test
     */
    public function evenMoreShortenedIpEvaluatesToTrue()
    {
        assertTrue($this->isIpV6Address->test('febc::23c1:aa49:0:0:9982'));
    }

    /**
     * @test
     */
    public function singleShortenedIpEvaluatesToTrue()
    {
        assertTrue($this->isIpV6Address->test('febc:a574:2b:23c1:aa49:4592:4efe:9982'));
    }

    /**
     * @test
     */
    public function shortenedPrefixIpEvaluatesToTrue()
    {
        assertTrue($this->isIpV6Address->test('::382b:23c1:aa49:4592:4efe:9982'));
    }

    /**
     * @test
     */
    public function shortenedPostfixIpEvaluatesToTrue()
    {
        assertTrue($this->isIpV6Address->test('febc:a574:382b:23c1:aa49::'));
    }
}
