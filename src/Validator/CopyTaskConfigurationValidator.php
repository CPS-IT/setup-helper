<?php

namespace CPSIT\SetupHelper\Validator;

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

use CPSIT\SetupHelper\Task\TaskInterface;

/**
 * Class CopyTaskConfigurationValidator
 */
class CopyTaskConfigurationValidator extends AbstractConfigurationValidator implements ConfigurationValidatorInterface
{
    /**
     * @param array $configuration
     * @return bool
     */
    public function validate(array $configuration): bool
    {
        $message = \get_class($this) . ': ';

        if (empty($configuration)) {
            $this->io->write(
                $message . TaskInterface::MESSAGE_EMPTY_CONFIGURATION
            );

            return false;
        }

        foreach ($configuration as $source => $target) {
            if (empty($source)) {
                $this->io->write(
                    $message . TaskInterface::MESSAGE_SOURCE_PATH_MUST_NOT_BE_EMPTY
                );

                return false;
            }
            if (empty($target)) {
                $this->io->write(
                    $message . TaskInterface::MESSAGE_TARGET_PATH_MUST_NOT_BE_EMPTY
                );

                return false;
            }
        }

        return true;
    }


}