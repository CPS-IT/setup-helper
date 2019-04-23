<?php

namespace CPSIT\SetupHelper\Task;

use Composer\IO\IOInterface;
use CPSIT\SetupHelper\File\FileSystemInterface;
use CPSIT\SetupHelper\Validator\ConfigurationValidatorInterface;
use CPSIT\SetupHelper\Validator\CopyTaskConfigurationValidator;

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
 * Class Copy
 */
class Copy extends AbstractTask implements TaskInterface
{
    protected $configurationValidator;

    public function __construct(
        IOInterface $IO,
        array $config = [],
        FileSystemInterface $fileSystem = null,
        ConfigurationValidatorInterface $configurationValidator = null)
    {
        parent::__construct($IO, $config, $fileSystem);
        if (null === $configurationValidator) {
            $configurationValidator = new CopyTaskConfigurationValidator($this->io);
        }
        $this->configurationValidator = $configurationValidator;
    }

    /**
     * @return void
     */
    public function perform(): void
    {
        if (!$this->configurationValidator->validate($this->getConfig())) {
            return;
        }
        try {
            $this->process($this->getConfig());
        } catch (\Exception $exception) {
            $this->io->writeError($exception->getMessage());
        }
    }

    /**
     * @return ConfigurationValidatorInterface|CopyTaskConfigurationValidator
     */
    public function getConfigurationValidator()
    {
        return $this->configurationValidator;
    }

    protected function process(array $configuration)
    {
        foreach ($configuration as $source => $target) {
            if ($this->fileSystem->copy($source, $target)) {
                $this->io->write(
                    sprintf(
                        TaskInterface::MESSAGE_FILE_COPIED,
                        $source,
                        $target
                    )
                );
            }
        }
    }

}
