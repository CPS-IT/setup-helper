<?php

namespace Fr\ProjectBuilder\Task;

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

use Fr\ProjectBuilder\Report\Error;
use Fr\ProjectBuilder\Report\Notice;
use Fr\ProjectBuilder\Report\ResultInterface;
use Fr\ProjectBuilder\Report\Success;

/**
 * Class Unlink
 *
 * unlink given files and folders
 */
class Unlink implements TaskInterface
{
    const MESSAGE_RESOURCE_UNAVAILABLE = 'Could not delete file: %s. Please close all applications that are using it.';
    const MESSAGE_FILE_DELETED = 'File %s deleted';
    const MESSAGE_FILE_NOT_FOUND = 'File %s not found';

    /**
     * @param array $config
     * @return ResultInterface
     */
    public function perform(array $config)
    {
        if (empty($config)) {
            return new Notice(
                get_class($this) . ': ' . TaskInterface::MESSAGE_EMPTY_CONFIGURATION,
                1550153594
            );
        }

        $messages = [];
        foreach ($config as $filePath) {
            try {
                $messages[] = $this->removeFile($filePath);
            } catch (\Exception $exception) {
                return new Error($exception->getMessage(), $exception->getCode());
            }
        }

        return new Success(implode(PHP_EOL, $messages));
    }

    /**
     * @param $filePath
     * @return string
     * @throws \Exception
     */
    protected function removeFile($filePath)
    {
        if (!file_exists($filePath)) {
            return sprintf(self::MESSAGE_FILE_NOT_FOUND, $filePath);
        }

        if (@unlink($filePath) !== true) {
            throw new \Exception(self::MESSAGE_RESOURCE_UNAVAILABLE, 1550176983);
        }

        return sprintf(self::MESSAGE_FILE_DELETED, $filePath);
    }
}
