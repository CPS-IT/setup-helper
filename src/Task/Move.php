<?php

namespace CPSIT\ProjectBuilder\Task;

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

class Move extends AbstractTask implements TaskInterface
{
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
                $this->move($source, $target);
            } catch (\Exception $exception) {
                $this->io->writeError($exception->getMessage());
            }
        }
    }

    /**
     * @param string $source
     * @param string $target
     * @return string
     * @throws \Exception
     */
    protected function move(string $source, string $target)
    {
        $sourceFile = new File($source);

        if (!$sourceFile->exists()) {
            $this->io->write(
                sprintf(
                    TaskInterface::MESSAGE_FILE_NOT_FOUND,
                    $sourceFile->getAbsolutePath()
                )
            );
        }

        $sourceFile->move($target);
        $this->io->write(
            sprintf(
                TaskInterface::MESSAGE_FILE_MOVED,
                $source, $target
            )
        );
    }

}
