<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use function bovigo\assert\assert;
use org\bovigo\vfs\vfsStream;
/**
 * Tests for bovigo\assert\predicate\IsExistingDirectory.
 *
 * @group  filesystem
 * @group  predicate
 */
class IsExistingDirectoryTest extends \PHPUnit_Framework_TestCase
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
        vfsStream::newDirectory('other')
                 ->at($root);
        vfsStream::newDirectory('bar')
                 ->at($root->getChild('basic'));
    }

    /**
     * @test
     */
    public function evaluatesToFalseForNull()
    {
        assert(isExistingDirectory()->test(null), isFalse());
    }

    /**
     * @test
     */
    public function evaluatesToFalseForEmptyString()
    {
        assert(isExistingDirectory()->test(''), isFalse());
    }

    /**
     * @test
     */
    public function evaluatesToTrueForRelativePath()
    {
        assert(
                isExistingDirectory(vfsStream::url('root/basic'))
                        ->test('../other'),
                isTrue()
        );
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfDirDoesNotExistRelatively()
    {
        assert(
                isExistingDirectory(vfsStream::url('root/basic'))
                        ->test('other'),
                isFalse()
        );
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfDirDoesNotExistGlobally()
    {
        assert(isExistingDirectory()->test(__DIR__ . '/../doesNotExist'), isFalse());
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfDirDoesExistRelatively()
    {
        assert(
                isExistingDirectory(vfsStream::url('root/basic'))->test('bar'),
                isTrue()
        );
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfDirDoesExistGlobally()
    {
        assert(isExistingDirectory()->test(__DIR__), isTrue());
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfIsRelativeFile()
    {
        assert(
                isExistingDirectory(vfsStream::url('root'))->test('foo.txt'),
                isFalse()
        );
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfIsGlobalFile()
    {
        assert(
                isExistingDirectory()->test(__FILE__),
                isFalse()
        );
    }

    /**
     * @return  array
     */
    public function instances()
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
        assert((string) $instance, equals($message));
    }
}
