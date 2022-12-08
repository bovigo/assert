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
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\IsExistingDirectory.
 *
 * @group  filesystem
 * @group  predicate
 */
class IsExistingDirectoryTest extends TestCase
{
    protected function setUp(): void
    {
        $root  = vfsStream::setup();
        $basic = vfsStream::newDirectory('basic')->at($root);
        vfsStream::newFile('foo.txt')->at($root);
        vfsStream::newDirectory('other')->at($root);
        vfsStream::newDirectory('bar')->at($basic);
    }

    /**
     * @test
     */
    public function evaluatesToFalseForNull(): void
    {
        assertFalse(isExistingDirectory()->test(null));
    }

    /**
     * @test
     */
    public function evaluatesToFalseForEmptyString(): void
    {
        assertFalse(isExistingDirectory()->test(''));
    }

    /**
     * @test
     */
    public function evaluatesToTrueForRelativePath(): void
    {
        assertTrue(
            isExistingDirectory(vfsStream::url('root/basic'))
                ->test('../other')
        );
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfDirDoesNotExistRelatively(): void
    {
        assertFalse(
            isExistingDirectory(vfsStream::url('root/basic'))
                ->test('other')
        );
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfDirDoesNotExistGlobally(): void
    {
        assertFalse(isExistingDirectory()->test(__DIR__ . '/../doesNotExist'));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfDirDoesExistRelatively(): void
    {
        assertTrue(
            isExistingDirectory(vfsStream::url('root/basic'))->test('bar')
        );
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfDirDoesExistGlobally(): void
    {
        assertTrue(isExistingDirectory()->test(__DIR__));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfIsRelativeFile(): void
    {
        assertFalse(
            isExistingDirectory(vfsStream::url('root'))->test('foo.txt')
        );
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfIsGlobalFile(): void
    {
        assertFalse(isExistingDirectory()->test(__FILE__));
    }

    public function instances(): Generator
    {
        yield [
            'instance' => new IsExistingDirectory(),
            'message' => 'is a existing directory'
        ];
        yield [
            'instance' => new IsExistingDirectory(vfsStream::url('root')),
            'message' => 'is a existing directory in basepath ' . vfsStream::url('root')
        ];
    }

    /**
     * @test
     * @dataProvider  instances
     */
    public function hasStringRepresentation(IsExistingDirectory $instance, string $message): void
    {
        assertThat((string) $instance, equals($message));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat(vfsStream::url('root/baz'), isExistingDirectory()))
            ->throws(AssertionFailure::class)
            ->withMessage(
                "Failed asserting that 'vfs://root/baz' is a existing directory."
            );
    }
}
