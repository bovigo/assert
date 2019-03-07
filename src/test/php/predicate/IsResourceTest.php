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
use function bovigo\assert\predicate\isResource;
use function bovigo\assert\predicate\isNotAResource;

/**
 * Test for bovigo\assert\assert\predicate\isResource() and bovigo\assert\assert\predicate\isNotAResource().
 *
 * @group  predicate
 */
class IsResourceTest extends TestCase
{
    private $resource;

    public function setup(): void
    {
        $this->resource = fopen(__FILE__, 'r');
    }

    public function teardown(): void
    {
        fclose($this->resource);
    }

    public function invalidResources(): array
    {
        return [
            'string' => ['foo'],
            'float'  => [30.3],
            'object' => [new \stdClass()]
        ];
    }

    /**
     * @test
     */
    public function validResourcesAreRecognized()
    {
        assertThat($this->resource, isResource());
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  invalidResources
     */
    public function invalidResourcesAreRejected($value)
    {
        expect(function() use($value) { assertThat($value, isResource()); })
            ->throws(AssertionFailure::class);
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  invalidResources
     */
    public function invalidResourcesAreRecognizedOnNegation($value)
    {
        assertThat($value, isNotAResource());
    }

    /**
     * @test
     */
    public function validObjectsAreRejectedOnNegation()
    {
        expect(function() { assertThat($this->resource, isNotAResource()); })
            ->throws(AssertionFailure::class);
    }
}