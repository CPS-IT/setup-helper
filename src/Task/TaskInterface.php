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
use Composer\IO\IOInterface;

/**
 * Interface TaskInterface
 */
interface TaskInterface
{
    const MESSAGE_EMPTY_CONFIGURATION = 'The given configuration is empty';
    const MESSAGE_RESOURCE_UNAVAILABLE = 'Could not delete file: %s. Please close all applications that are using it.';
    const MESSAGE_FILE_NOT_FOUND = 'File or folder %s not found';
    const MESSAGE_FILE_DELETED = 'File %s deleted';
    const MESSAGE_FILE_MOVED = 'File moved from %s to %s';
    const MESSAGE_FILE_RENAMED = 'File or folder renamed from %s to %s';
    const MESSAGE_FOLDER_CREATED = 'Folder %s created';
    const MESSAGE_FOLDER_ALREADY_EXISTS = 'Folder %s already exists';

    /**
     * Constructor for Tasks
     *
     * TaskInterface constructor.
     * @param IOInterface $IO
     * @param array $config
     */
    public function __construct(IOInterface $IO, array $config = []);

    /**
     * @return void
     */
    public function perform();
}
