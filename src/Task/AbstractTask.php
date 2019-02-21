<?php

namespace CPSIT\ProjectBuilder\Task;
use Composer\IO\IOInterface;
use Naucon\File\File;


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
    /**
     * @var IOInterface
     */
    protected $io;

    /**
     * @var array
     */
    protected $config;

    /**
     * AbstractTask constructor.
     * @param IOInterface $IO
     * @param $config
     */
    public function __construct(IOInterface $IO, array $config = [])
    {
        $this->io = $IO;
        $this->config = $config;
    }

    /**
     * @return IOInterface
     */
    public function getIo():IOInterface
    {
        return $this->io;
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
     * Get the working dirctory of the current script
     * (This should always be the composer root path)
     * @return string
     */
    public function getWorkingDirectory(): string
    {
        return getcwd() . File::PATH_SEPARATOR;
    }
}
