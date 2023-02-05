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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\IsExistingDirectory.
 *
 * @group filesystem
 * @group predicate
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

    #[Test]
    public function evaluatesToFalseForNull(): void
    {
        assertFalse(isExistingDirectory()->test(null));
    }

    #[Test]
    public function evaluatesToFalseForEmptyString(): void
    {
        assertFalse(isExistingDirectory()->test(''));
    }

    #[Test]
    public function evaluatesToTrueForRelativePath(): void
    {
        assertTrue(
            isExistingDirectory(vfsStream::url('root/basic'))
                ->test('../other')
        );
    }

    #[Test]
    public function evaluatesToFalseIfDirDoesNotExistRelatively(): void
    {
        assertFalse(
            isExistingDirectory(vfsStream::url('root/basic'))
                ->test('other')
        );
    }

    #[Test]
    public function evaluatesToFalseIfDirDoesNotExistGlobally(): void
    {
        assertFalse(isExistingDirectory()->test(__DIR__ . '/../doesNotExist'));
    }

    #[Test]
    public function evaluatesToTrueIfDirDoesExistRelatively(): void
    {
        assertTrue(
            isExistingDirectory(vfsStream::url('root/basic'))->test('bar')
        );
    }

    #[Test]
    public function evaluatesToTrueIfDirDoesExistGlobally(): void
    {
        assertTrue(isExistingDirectory()->test(__DIR__));
    }

    #[Test]
    public function evaluatesToFalseIfIsRelativeFile(): void
    {
        assertFalse(
            isExistingDirectory(vfsStream::url('root'))->test('foo.txt')
        );
    }

    #[Test]
    public function evaluatesToFalseIfIsGlobalFile(): void
    {
        assertFalse(isExistingDirectory()->test(__FILE__));
    }

    public static function instances(): Generator
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

    #[Test]
    #[DataProvider('instances')]
    public function hasStringRepresentation(IsExistingDirectory $instance, string $message): void
    {
        assertThat((string) $instance, equals($message));
    }

    #[Test]
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat(vfsStream::url('root/baz'), isExistingDirectory()))
            ->throws(AssertionFailure::class)
            ->withMessage(
                "Failed asserting that 'vfs://root/baz' is a existing directory."
            );
    }
}
