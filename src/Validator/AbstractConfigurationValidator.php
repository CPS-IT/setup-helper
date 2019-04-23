<?php

namespace CPSIT\SetupHelper\Validator;

use Composer\IO\IOInterface;
use CPSIT\SetupHelper\Common\FileSystemTrait;
use CPSIT\SetupHelper\Common\IOTrait;
use CPSIT\SetupHelper\File\FileSystemInterface;
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

/**
 * Class AbstractConfigurationValidator
 */
abstract class AbstractConfigurationValidator
{
    use IOTrait, FileSystemTrait;

    /**
     * AbstractConfigurationValidator constructor.
     * @param IOInterface $IO
     * @param FileSystemInterface $fileSystem
     */
    public function __construct(IOInterface $IO, FileSystemInterface $fileSystem = null)
    {
        $this->io = $IO;
        if (null === $fileSystem) {
            $fileSystem =  new FileSystem();
        }
        $this->fileSystem = $fileSystem;
    }
}