<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Predicate to test that a string denotes an existing directory.
 */
class IsExistingDirectory extends FilesystemPredicate
{
    /**
     * checks if given path exists and is a directory
     */
    protected function fileExists(string $path): bool
    {
        return file_exists($path . '/.') && filetype($path) === 'dir';
    }

    /**
     * returns file system type which is checked
     */
    protected function type(): string
    {
        return 'directory';
    }
}
