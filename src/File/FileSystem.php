<?php

namespace CPSIT\SetupHelper\File;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Dirk Wenzel
 *  All rights reserved
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the text file GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use Composer\Util\ProcessExecutor;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem as SymfonyFileSystem;

/**
 * Class FileSystem
 * We extend the parent in order to introduce the missing interface!
 * Some methods are redirected to symfony file system.
 */
class FileSystem extends \Composer\Util\Filesystem implements FileSystemInterface
{
    /**
     * @var SymfonyFileSystem
     */
    protected $symfonyFileSystem;

    public function __construct(ProcessExecutor $executor = null, SymfonyFileSystem $filesystem = null)
    {
        parent::__construct($executor);
        $this->symfonyFileSystem = $filesystem ? :new SymfonyFileSystem();
    }

    /**
     * @return SymfonyFileSystem
     */
    public function getSymfonyFileSystem(): SymfonyFileSystem
    {
        return $this->symfonyFileSystem;
    }

    /**
     * Copies a file.
     *
     * If the target file is older than the origin file, it's always overwritten.
     * If the target file is newer, it is overwritten only when the
     * $overwriteNewerFiles option is set to true.
     *
     * @param string $originFile The original filename
     * @param string $targetFile The target filename
     * @return bool
     */
    public function copy($originFile, $targetFile): bool {
        return parent::copy($originFile, $targetFile);
    }

    /**
     * Creates a directory recursively.
     *
     * @param iterable|string $dirs
     * @param int $mode
     */
    public function makeDirectories($dirs, $mode = 0777): void
    {
        $this->symfonyFileSystem->mkdir($dirs, $mode);
    }

    /**
     * @param iterable|string $files
     * @return bool
     */
    public function exists($files): bool
    {
        return $this->symfonyFileSystem->exists($files);
    }

    /**
     * @param iterable|string $files A filename, an array of files, or a \Traversable instance to create
     * @param null $time The touch time as a Unix timestamp, if not supplied the current system time is used
     * @param null $accessTime The access time as a Unix timestamp, if not supplied the current system time is used
     * @throws IOException
     */
    public function touch($files, $time = null, $accessTime = null): void
    {
        $this->symfonyFileSystem->touch($files, $time, $accessTime);
    }

    /**
     * Change mode for an array of files or directories.
     *
     * @param string|iterable $files A filename, an array of files, or a \Traversable instance to change mode
     * @param int $mode The new mode (octal)
     * @param int $umask The mode mask (octal)
     * @param bool $recursive Whether change the mod recursively or not
     *
     * @throws IOException When the change fails
     */
    public function changeMode($files, $mode, $umask = 0000, $recursive = false): void
    {
        $this->symfonyFileSystem->chmod($files, $mode, $umask, $recursive);
    }

    /**
     * Change the owner of an array of files or directories.
     *
     * @param string|iterable $files A filename, an array of files, or a \Traversable instance to change owner
     * @param string $user The new owner user name
     * @param bool $recursive Whether change the owner recursively or not
     *
     * @throws IOException When the change fails
     */
    public function changeOwner($files, $user, $recursive = false): void
    {
        $this->symfonyFileSystem->chown($files, $user, $recursive);
    }

    /**
     * Change the group of an array of files or directories.
     *
     * @param string|iterable $files A filename, an array of files, or a \Traversable instance to change group
     * @param string $group The group name
     * @param bool $recursive Whether change the group recursively or not
     *
     * @throws IOException When the change fails
     */
    public function changeGroup($files, $group, $recursive = false): void
    {
        $this->symfonyFileSystem->chgrp($files, $group, $recursive);
    }

    /**
     * Creates a symbolic link or copy a directory.
     *
     * @param string $originDir The origin directory path
     * @param string $targetDir The symbolic link name
     * @param bool $copyOnWindows Whether to copy files if on Windows
     *
     * @throws IOException When symlink fails
     */
    public function symlink($originDir, $targetDir, $copyOnWindows = false): void
    {
        $this->symfonyFileSystem->symlink($originDir, $targetDir, $copyOnWindows);
    }

    /**
     * Creates a hard link, or several hard links to a file.
     *
     * @param string $originFile The original file
     * @param string|string[] $targetFiles The target file(s)
     *
     * @throws FileNotFoundException When original file is missing or not a file
     * @throws IOException When link fails, including if link already exists
     */
    public function hardlink($originFile, $targetFiles): void
    {
        $this->symfonyFileSystem->hardlink($originFile, $targetFiles);
    }

    /**
     * Resolves links in paths.
     *
     * With $canonical = false (default)
     *      - if $path does not exist or is not a link, returns null
     *      - if $path is a link, returns the next direct target of the link without considering the existence of the target
     *
     * With $canonical = true
     *      - if $path does not exist, returns null
     *      - if $path exists, returns its absolute fully resolved final version
     *
     * @param string $path A filesystem path
     * @param bool $canonical Whether or not to return a canonical path
     *
     * @return string|null
     */
    public function readlink($path, $canonical = false): ?string
    {
        return $this->symfonyFileSystem->readlink($path, $canonical);
    }
}
