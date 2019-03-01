<?php

namespace CPSIT\SetupHelper\Task;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Michael Scheppat
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
 * Class Symlink
 */
class Symlink extends AbstractTask implements TaskInterface
{
    /**
     * performs the task and returns status messages
     */
    public function perform()
    {
        $config = $this->getConfig();
        if (empty($config)) {
            $this->io->write(
                get_class($this) . ': ' . TaskInterface::MESSAGE_EMPTY_CONFIGURATION
            );
        }
        foreach ($config as $source => $target) {
            try {
                $this->symlink($source, $target);
            } catch (\Exception $exception) {
                $this->io->writeError($exception->getMessage());
            }
        }
    }

    /**
     * Generates a symlink source to target
     * All operations are performed relative to the
     * current working directory
     *
     * @param string $source
     * @param string $target
     * @return void
     * @throws \Exception
     */
    protected function symlink(string $source, string $target)
    {
        $sourceFile = new File(getcwd() . File::PATH_SEPARATOR . $source);

        if (!$sourceFile->exists()) {
            $this->io->writeError(
                sprintf(
                    TaskInterface::MESSAGE_FILE_NOT_FOUND,
                    getcwd() . File::PATH_SEPARATOR . $source
                )
            );
            return;
        }

        $targetFile = new File(getcwd() . File::PATH_SEPARATOR . $target);
        if ($targetFile->exists()) {
            $explanation = $targetFile->isLink() ? ', referring to ' . $targetFile->getLinkTarget() : ' as a file.';
            $this->io->writeError(
                sprintf(
                    TaskInterface::MESSAGE_SYMLINK_ALREADY_EXISTS,
                    $target, $explanation
                )
            );
            return;
        }

        $result = symlink($source, $target);
        if ($result) {
            $this->io->write(
                sprintf(
                    TaskInterface::MESSAGE_SYMLINK_CREATED,
                    $source, $target
                )
            );
        }
    }

}
