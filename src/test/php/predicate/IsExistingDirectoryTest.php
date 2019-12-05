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
    /**
     * set up test environment
     */
    public function setUp(): void
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
    public function evaluatesToFalseForNull()
    {
        assertFalse(isExistingDirectory()->test(null));
    }

    /**
     * @test
     */
    public function evaluatesToFalseForEmptyString()
    {
        assertFalse(isExistingDirectory()->test(''));
    }

    /**
     * @test
     */
    public function evaluatesToTrueForRelativePath()
    {
        assertTrue(
                isExistingDirectory(vfsStream::url('root/basic'))
                        ->test('../other')
        );
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfDirDoesNotExistRelatively()
    {
        assertFalse(
                isExistingDirectory(vfsStream::url('root/basic'))
                        ->test('other')
        );
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfDirDoesNotExistGlobally()
    {
        assertFalse(isExistingDirectory()->test(__DIR__ . '/../doesNotExist'));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfDirDoesExistRelatively()
    {
        assertTrue(
                isExistingDirectory(vfsStream::url('root/basic'))->test('bar')
        );
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfDirDoesExistGlobally()
    {
        assertTrue(isExistingDirectory()->test(__DIR__));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfIsRelativeFile()
    {
        assertFalse(
                isExistingDirectory(vfsStream::url('root'))->test('foo.txt')
        );
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfIsGlobalFile()
    {
        assertFalse(isExistingDirectory()->test(__FILE__));
    }

    /**
     * @return  array
     */
    public function instances(): array
    {
        return [
                [new IsExistingDirectory(), 'is a existing directory'],
                [new IsExistingDirectory(vfsStream::url('root')), 'is a existing directory in basepath ' . vfsStream::url('root')]
        ];
    }

    /**
     * @param  \bovigo\assert\predicate\IsExistingDirectory  $instance
     * @param  string                                        $message
     * @test
     * @dataProvider  instances
     */
    public function hasStringRepresentation(IsExistingDirectory $instance, $message)
    {
        assertThat((string) $instance, equals($message));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        expect(function() {
            assertThat(vfsStream::url('root/baz'), isExistingDirectory());
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                "Failed asserting that 'vfs://root/baz' is a existing directory."
        );
    }
}
