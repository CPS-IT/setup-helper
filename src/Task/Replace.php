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

use CPSIT\SetupHelper\SettingsInterface;
use Naucon\File\FileWriter;

/**
 * Class Replace
 */
class Replace extends AbstractTask implements TaskInterface
{
    static protected $requiredKeys = [
        TaskInterface::KEY_PATH,
        TaskInterface::KEY_SEARCH
    ];

    public function perform()
    {
        $config = $this->getConfig();
        if (empty($config)) {
            $this->io->write(

                get_class($this) . ': ' . TaskInterface::MESSAGE_EMPTY_CONFIGURATION
            );
        }

        foreach ($config as $singleConfig) {
            if (!$this->isConfigurationValid($singleConfig)) {
                continue;
            }

            try {
                $this->replace($singleConfig);
            } catch (\Exception $exception) {
                $this->io->writeError($exception->getMessage());
            }

        }
    }

    /**
     * @param array $configuration
     * @return bool
     */
    protected function isConfigurationValid(array $configuration)
    {

        $requiredKeys = static::$requiredKeys;

        if (empty($configuration[TaskInterface::KEY_ASK])) {
            $requiredKeys[] = TaskInterface::KEY_REPLACE;
        }

        foreach ($requiredKeys as $key) {
            if (empty($configuration[$key])) {
                $this->io->writeError(
                    sprintf(
                        TaskInterface::MESSAGE_EMPTY_KEY,
                        $key,
                        SettingsInterface::REPLACE_TASK_KEY
                    )
                );

                return false;
            }
        }

        if (
            !empty($configuration[TaskInterface::KEY_REPLACE])
            && !empty($configuration[TaskInterface::KEY_ASK])
        ) {
            $this->io->writeError(
                sprintf(
                    TaskInterface::MESSAGE_CONFLICTING_KEYS,
                    TaskInterface::KEY_ASK,
                    TaskInterface::KEY_REPLACE,
                    SettingsInterface::REPLACE_TASK_KEY
                )
            );

            return false;
        }

        return true;
    }

    /**
     * @param array $configuration
     * @throws \Naucon\File\Exception\FileException
     * @throws \Naucon\File\Exception\FileWriterException
     */
    protected function replace(array $configuration)
    {
        $path = $configuration[TaskInterface::KEY_PATH];
        $search = $configuration[TaskInterface::KEY_SEARCH];
        $replace = '';
        if (
        !empty($configuration[TaskInterface::KEY_REPLACE])) {
            $replace = $configuration[TaskInterface::KEY_REPLACE];
        }

        if (!empty($configuration[TaskInterface::KEY_ASK])) {
            $replace = $this->io->ask($configuration[TaskInterface::KEY_ASK]);
        }

        $file = new FileWriter($this->getWorkingDirectory() . $path, 'r+');
        if (!$file->exists()) {
            $this->io->writeError(
                sprintf(
                    TaskInterface::MESSAGE_FILE_NOT_FOUND,
                    $path
                )
            );

            return;
        }

        $content = $file->read();
        $file->clear();
        $file->write(str_replace(
                $search, $replace, $content)
        );

    }
}