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

use Composer\IO\IOInterface;
use CPSIT\SetupHelper\File\GlobResolver;
use CPSIT\SetupHelper\Processor\SearchReplaceFile;
use CPSIT\SetupHelper\SettingsInterface;
use CPSIT\SetupHelper\Task\Dto\FileSearch;

/**
 * Class Replace
 */
class Replace extends AbstractTask implements TaskInterface
{
    static protected $requiredKeys = [
        TaskInterface::KEY_PATH,
        TaskInterface::KEY_SEARCH
    ];

    protected $resolver;

    /**
     * AbstractTask constructor.
     * @param IOInterface $IO
     * @param $config
     */
    public function __construct(IOInterface $IO, array $config = [])
    {
        $this->resolver = new GlobResolver();
        $this->io = $IO;
        $this->config = $config;
    }


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
                $this->process($singleConfig);
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
    protected function process(array $configuration)
    {
        $resolver_pattern = $this->getWorkingDirectory() . $configuration[TaskInterface::KEY_PATH];

        $replace = "";
        if (!empty($configuration[TaskInterface::KEY_ASK])) {
            $replace = $this->io->ask($configuration[TaskInterface::KEY_ASK]);
        }
        if (!empty($configuration[TaskInterface::KEY_REPLACE])) {
            $replace = $configuration[TaskInterface::KEY_REPLACE];
        }

        $resolver = $this->resolver;
        $resolver->setPattern($resolver_pattern);
        $resolvedFiles = $resolver->resolve();

        if (empty($resolvedFiles)) {
            $this->io->writeError(
                sprintf(
                    TaskInterface::MESSAGE_FILE_NOT_FOUND,
                    $resolver_pattern
                )
            );
        }

        foreach ($resolvedFiles as $filepath) {
            $fileSearch = new FileSearch();

            $fileSearch->setPath(
                $filepath
            )->setSearch($configuration[TaskInterface::KEY_SEARCH]);

            $fileSearch->setReplace(
                $replace
            );

            $processor = new SearchReplaceFile($this->io, $fileSearch);
            $processor->process();
        }

    }
}
