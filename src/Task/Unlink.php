<?php

namespace CPSIT\SetupHelper\Task;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Dirk Wenzel <wenzel@cps-it.de>
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

use Naucon\File\File;
use Naucon\File\FileInterface;

/**
 * Class Unlink
 *
 * unlink given files and folders
 */
class Unlink extends AbstractTask implements TaskInterface
{
    /**
     * @return void
     */
    public function perform(): void
    {
        $config = $this->getConfig();
        if (empty($config)) {
            $this->io->write(
                get_class($this) . ': ' . TaskInterface::MESSAGE_EMPTY_CONFIGURATION
            );
        }

        foreach ($config as $filePath) {
            try {
                $this->process($filePath);
            } catch (\Exception $exception) {
                $this->io->writeError($exception->getMessage());
            }
        }
    }

    /**
     * @param string $filePath
     * @throws \Exception
     */
    protected function process(string $filePath): void
    {
        $workingDirectory = $this->getWorkingDirectory();
        $absoluteFilePath = $workingDirectory . $filePath;
        if (!$this->fileSystem->exists($absoluteFilePath)) {
            $this->io->writeError(
                sprintf(
                    TaskInterface::MESSAGE_FILE_NOT_FOUND,
                    $filePath
                )
            );

            return;
        }

        $this->fileSystem->remove($absoluteFilePath);
        $this->io->write(sprintf(TaskInterface::MESSAGE_FILE_DELETED, $filePath));
    }
}
