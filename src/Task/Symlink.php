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

/**
 * Class Symlink
 */
class Symlink extends AbstractTask implements TaskInterface
{
    /**
     * performs the task
     */
    public function perform(): void
    {
        $config = $this->getConfig();
        if (empty($config)) {
            $this->io->write(
                get_class($this) . ': ' . TaskInterface::MESSAGE_EMPTY_CONFIGURATION
            );
        }
        foreach ($config as $source => $target) {
            try {
                $this->process($source, $target);
            } catch (\Exception $exception) {
                $this->io->writeError($exception->getMessage());
            }
        }
    }

    /**
     * Generates a symlink to target
     * All operations are performed relative to the
     * current working directory
     *
     * @param string $target
     * @param string $link
     * @return void
     * @throws \Exception
     */
    protected function process(string $target, string $link): void
    {
        if (!$this->fileSystem->exists($target)) {
            $message = sprintf(
                TaskInterface::MESSAGE_FILE_NOT_FOUND,
                $this->getWorkingDirectory() . $target
            );

            $this->io->writeError($message);
            return;
        }

        if ($this->fileSystem->exists($link)) {
            $explanation = is_link($link) ? ', referring to ' . $this->fileSystem->readlink($link) : ' as a file.';
            $message = sprintf(
                TaskInterface::MESSAGE_SYMLINK_ALREADY_EXISTS,
                $link, $explanation
            );
            $this->io->writeError($message);
            return;
        }

        $this->fileSystem->symlink($target, $link);
        $message = sprintf(
            TaskInterface::MESSAGE_SYMLINK_CREATED,
            $target, $link
        );
        $this->io->write($message);
    }

}
