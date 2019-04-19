<?php

namespace CPSIT\SetupHelper\Task;

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
    const MESSAGE_SYMLINK_CREATED = 'Symlink %s to %s created.';
    const MESSAGE_SYMLINK_ALREADY_EXISTS = 'Symlink target %s already exists%s';
    const MESSAGE_REPLACED_IN_FILE = 'Searched pattern "%s" in file %s and replaced %b occurrences by "%s"';
    const MESSAGE_EMPTY_KEY = 'Required configuration key "%s" for task "%s" is not set or empty.';
    const MESSAGE_CONFLICTING_KEYS = 'Keys "%s" and "%s" can not be set both for task "%s.';
    const MESSAGE_SOURCE_PATH_MUST_NOT_BE_EMPTY = 'Source path must not be empty';
    const MESSAGE_TARGET_PATH_MUST_NOT_BE_EMPTY = 'Target path must not be empty';
    const KEY_PATH = 'path';
    const KEY_SEARCH = 'search';
    const KEY_REPLACE = 'replace';
    const KEY_ASK = 'ask';

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
