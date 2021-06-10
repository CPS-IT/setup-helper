<?php

namespace CPSIT\SetupHelper\Task;

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

use Naucon\File\File;

/**
 * Class Rename
 */
class Rename extends AbstractTask implements TaskInterface
{
    public function perform(): void
    {
        $config = $this->getConfig();
        if (empty($config)) {
            $this->io->write(

                get_class($this) . ': ' . TaskInterface::MESSAGE_EMPTY_CONFIGURATION
            );
        }

        foreach ($config as $oldName => $newName) {
            try {
                $this->process($oldName, $newName);
            } catch (\Exception $exception) {
                $this->io->writeError($exception->getMessage());
            }
        }
    }

    /**
     * @param string $oldName
     * @param string $newName
     * @throws \Naucon\File\Exception\FileException
     */
    protected function process(string $oldName, string $newName): void
    {
        $file = new  File($this->getWorkingDirectory() . $oldName);
        if (!$file->exists()) {
            $this->io->writeError(
                sprintf(
                    TaskInterface::MESSAGE_FILE_NOT_FOUND,
                    $oldName
                )
            );

            return;
        }

        $file->rename($newName);
        $this->io->write(
            sprintf(
                TaskInterface::MESSAGE_FILE_RENAMED,
                $oldName,
                $newName
            )
        );

    }

}
