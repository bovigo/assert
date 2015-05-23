<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
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
        $isExistingDirectory = new IsExistingDirectory();
        assertFalse($isExistingDirectory(null));
    }

    /**
     * @test
     */
    public function evaluatesToFalseForEmptyString()
    {
        $isExistingDirectory = new IsExistingDirectory();
        assertFalse($isExistingDirectory(''));
    }

    /**
     * @test
     */
    public function evaluatesToTrueForRelativePath()
    {
        $isExistingDirectory = new IsExistingDirectory(vfsStream::url('root/basic'));
        assertTrue($isExistingDirectory('../other'));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfDirDoesNotExistRelatively()
    {
        $isExistingDirectory = new IsExistingDirectory(vfsStream::url('root/basic'));
        assertFalse($isExistingDirectory('other'));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfDirDoesNotExistGlobally()
    {
        $isExistingDirectory = new IsExistingDirectory();
        assertFalse($isExistingDirectory(__DIR__ . '/../doesNotExist'));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfDirDoesExistRelatively()
    {
        $isExistingDirectory = new IsExistingDirectory(vfsStream::url('root/basic'));
        assertTrue($isExistingDirectory('bar'));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfDirDoesExistGlobally()
    {
        $isExistingDirectory = new IsExistingDirectory();
        assertTrue($isExistingDirectory(__DIR__));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfIsRelativeFile()
    {
        $isExistingDirectory = new IsExistingDirectory(vfsStream::url('root'));
        assertFalse($isExistingDirectory('foo.txt'));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfIsGlobalFile()
    {
        $isExistingDirectory = new IsExistingDirectory();
        assertFalse($isExistingDirectory(__FILE__));
    }
}
