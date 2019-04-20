<?php

namespace CPSIT\SetupHelper\Task;

use Composer\IO\IOInterface;
use CPSIT\SetupHelper\Common\FileSystemTrait;
use CPSIT\SetupHelper\Common\IOTrait;
use CPSIT\SetupHelper\File\FileSystemInterface;
use Naucon\File\File;
use CPSIT\SetupHelper\File\FileSystem;

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
class AbstractTask
{
    use IOTrait, FileSystemTrait;

    /**
     * @var array
     */
    protected $config;

    /**
     * AbstractTask constructor.
     * @param IOInterface $IO
     * @param array $config
     * @param FilesystemInterface $fileSystem
     */
    public function __construct(IOInterface $IO, array $config = [], FileSystemInterface $fileSystem = null)
    {
        $this->io = $IO;
        $this->config = $config;
        $this->fileSystem = $fileSystem ? : new FileSystem();
    }

    /**
     * Get the config
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Set the config
     *
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get the working directory of the current script
     * (This should always be the composer root path)
     * @return string
     */
    public function getWorkingDirectory(): string
    {
        return getcwd() . File::PATH_SEPARATOR;
    }

}
