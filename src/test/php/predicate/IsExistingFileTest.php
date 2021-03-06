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
 * Tests for bovigo\assert\predicate\IsExistingFile.
 *
 * @group  filesystem
 * @group  predicate
 */
class IsExistingFileTest extends TestCase
{
    protected function setUp(): void
    {
        $root  = vfsStream::setup();
        $basic = vfsStream::newDirectory('basic')->at($root);
        vfsStream::newFile('foo.txt')->at($root);
        vfsStream::newFile('bar.txt')->at($basic);
    }

    /**
     * @test
     */
    public function evaluatesToFalseForNull(): void
    {
        assertFalse(isExistingFile()->test(null));
    }

    /**
     * @test
     */
    public function evaluatesToFalseForEmptyString(): void
    {
        assertFalse(isExistingFile()->test(''));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfRelativePathExists(): void
    {
        assertTrue(
                isExistingFile(vfsStream::url('root/basic'))->test('../foo.txt')
        );
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfFileDoesNotExistRelatively(): void
    {
        assertFalse(
                isExistingFile(vfsStream::url('root/basic'))->test('foo.txt')
        );
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfFileDoesNotExistGlobally(): void
    {
        assertFalse(isExistingFile()->test(__DIR__ . '/doesNotExist.txt'));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfFileDoesExistRelatively(): void
    {
        assertTrue(
                isExistingFile(vfsStream::url('root/basic'))->test('bar.txt')
        );
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfFileDoesExistGlobally(): void
    {
        assertTrue(isExistingFile()->test(__FILE__));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfIsRelativeDir(): void
    {
        assertFalse(isExistingFile(vfsStream::url('root'))->test('basic'));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfIsGlobalDir(): void
    {
        assertFalse(isExistingFile()->test(__DIR__));
    }

    /**
     * @return  array<array<mixed>>
     */
    public function instances(): array
    {
        return [
                [new IsExistingFile(), 'is a existing file'],
                [new IsExistingFile(vfsStream::url('root')), 'is a existing file in basepath ' . vfsStream::url('root')]
        ];
    }

    /**
     * @param  \bovigo\assert\predicate\IsExistingFile  $instance
     * @param  string                                   $message
     * @test
     * @dataProvider  instances
     */
    public function hasStringRepresentation(IsExistingFile $instance, $message): void
    {
        assertThat((string) $instance, equals($message));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(function() {
            assertThat(vfsStream::url('root/doesNotExist.txt'), isExistingFile());
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                "Failed asserting that 'vfs://root/doesNotExist.txt' is a existing file."
        );
    }
}
