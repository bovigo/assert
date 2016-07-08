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

use function bovigo\assert\assert;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\IsExistingFile.
 *
 * @group  filesystem
 * @group  predicate
 */
class IsExistingFileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * set up test environment
     */
    public function setUp()
    {
        $root = vfsStream::setup();
        vfsStream::newDirectory('basic')
                 ->at($root);
        vfsStream::newFile('foo.txt')
                 ->at($root);
        vfsStream::newFile('bar.txt')
                 ->at($root->getChild('basic'));
    }

    /**
     * @test
     */
    public function evaluatesToFalseForNull()
    {
        assert(isExistingFile()->test(null), isFalse());
    }

    /**
     * @test
     */
    public function evaluatesToFalseForEmptyString()
    {
        assert(isExistingFile()->test(''), isFalse());
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfRelativePathExists()
    {
        assert(
                isExistingFile(vfsStream::url('root/basic'))->test('../foo.txt'),
                isTrue()
        );
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfFileDoesNotExistRelatively()
    {
        assert(
                isExistingFile(vfsStream::url('root/basic'))->test('foo.txt'),
                isFalse()
        );
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfFileDoesNotExistGlobally()
    {
        assert(
                isExistingFile()->test(__DIR__ . '/doesNotExist.txt'),
                isFalse()
        );
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfFileDoesExistRelatively()
    {
        assert(
                isExistingFile(vfsStream::url('root/basic'))->test('bar.txt'),
                isTrue()
        );
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfFileDoesExistGlobally()
    {
        assert(isExistingFile()->test(__FILE__), isTrue());
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfIsRelativeDir()
    {
        assert(isExistingFile(vfsStream::url('root'))->test('basic'), isFalse());
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfIsGlobalDir()
    {
        assert(isExistingFile()->test(__DIR__), isFalse());
    }

    /**
     * @return  array
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
    public function hasStringRepresentation(IsExistingFile $instance, $message)
    {
        assert((string) $instance, equals($message));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        expect(function() {
            assert(vfsStream::url('root/doesNotExist.txt'), isExistingFile());
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                "Failed asserting that 'vfs://root/doesNotExist.txt' is a existing file."
        );
    }
}
