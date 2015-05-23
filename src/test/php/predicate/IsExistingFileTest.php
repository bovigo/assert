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
        $isExistingFile = new IsExistingFile();
        assertFalse($isExistingFile(null));
    }

    /**
     * @test
     */
    public function evaluatesToFalseForEmptyString()
    {
        $isExistingFile = new IsExistingFile();
        assertFalse($isExistingFile(''));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfRelativePathExists()
    {
        $isExistingFile = new IsExistingFile(vfsStream::url('root/basic'));
        assertTrue($isExistingFile('../foo.txt'));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfFileDoesNotExistRelatively()
    {
        $isExistingFile = new IsExistingFile(vfsStream::url('root/basic'));
        assertFalse($isExistingFile('foo.txt'));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfFileDoesNotExistGlobally()
    {
        $isExistingFile = new IsExistingFile();
        assertFalse($isExistingFile(__DIR__ . '/doesNotExist.txt'));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfFileDoesExistRelatively()
    {
        $isExistingFile = new IsExistingFile(vfsStream::url('root/basic'));
        assertTrue($isExistingFile('bar.txt'));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfFileDoesExistGlobally()
    {
        $isExistingFile = new IsExistingFile();
        assertTrue($isExistingFile(__FILE__));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfIsRelativeDir()
    {
        $isExistingFile = new IsExistingFile(vfsStream::url('root'));
        assertFalse($isExistingFile('basic'));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfIsGlobalDir()
    {
        $isExistingFile = new IsExistingFile();
        assertFalse($isExistingFile(__DIR__));
    }
}
