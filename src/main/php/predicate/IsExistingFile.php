<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Predicate to test that a string denotes an existing file.
 */
class IsExistingFile extends FilesystemPredicate
{
    /**
     * checks if given path exists and is a file
     *
     * @param   string  $path
     * @return  bool
     */
    protected function fileExists($path)
    {
        return file_exists($path) && filetype($path) === 'file';
    }

    /**
     * returns file system type which is checked
     *
     * @return  string
     */
    protected function type()
    {
        return 'file';
    }
}
