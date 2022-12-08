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
use PHPUnit\Framework\TestCase;
use stdClass;

use function bovigo\assert\assertThat;
use function bovigo\assert\expect;
use function bovigo\assert\fail;
use function bovigo\assert\predicate\isResource;
use function bovigo\assert\predicate\isNotAResource;
/**
 * Test for bovigo\assert\assert\predicate\isResource() and bovigo\assert\assert\predicate\isNotAResource().
 *
 * @group  predicate
 */
class IsResourceTest extends TestCase
{
    /**
     * @var  resource
     */
    private $resource;

    protected function setup(): void
    {
        $resource = fopen(__FILE__, 'r');
        if (false === $resource) {
            fail('Could not open file to create a resource');
        }

        $this->resource = $resource;
    }

    protected function teardown(): void
    {
        fclose($this->resource);
    }

    public function invalidResources(): Generator
    {
        yield 'string' => ['foo'];
        yield 'float'  => [30.3];
        yield 'object' => [new stdClass()];
    }

    /**
     * @test
     */
    public function validResourcesAreRecognized(): void
    {
        assertThat($this->resource, isResource());
    }

    /**
     * @test
     * @dataProvider  invalidResources
     */
    public function invalidResourcesAreRejected(mixed $value): void
    {
        expect(fn() => assertThat($value, isResource()))
            ->throws(AssertionFailure::class);
    }

    /**
     * @test
     * @dataProvider  invalidResources
     */
    public function invalidResourcesAreRecognizedOnNegation(mixed $value): void
    {
        assertThat($value, isNotAResource());
    }

    /**
     * @test
     */
    public function validObjectsAreRejectedOnNegation(): void
    {
        expect(fn() => assertThat($this->resource, isNotAResource()))
            ->throws(AssertionFailure::class);
    }
}