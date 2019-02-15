<?php

namespace Fr\ProjectBuilder;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2018 Dirk Wenzel <wenzel@cps-it.de>
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

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Fr\ProjectBuilder\Report\Error;
use Fr\ProjectBuilder\Report\ResultInterface;
use Fr\ProjectBuilder\SettingsInterface as SI;
use Fr\ProjectBuilder\Task\Rename;
use Fr\ProjectBuilder\Task\TaskInterface;
use Fr\ProjectBuilder\Task\Unlink;

/**
 * Class Installer
 */
final class Installer implements PluginInterface, EventSubscriberInterface
{

    const ENTRY_METHOD_NAME = 'performTasks';
    const TASKS_TO_PERFORM = [
        SI::UNLINK_TASK_KEY => Unlink::class,
        SI::RENAME_TASK_KEY => Rename::class
    ];
    const MESSAGE_NO_CONFIGURATION = '<info>No configuration found for project-builder in extra section of composer.json</info>';

    const MESSAGE_INVALID_TASK_KEY = 'Invalid key %s for task in extra.' . SI::INSTALLER_EXTRA_KEY;

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ScriptEvents::POST_INSTALL_CMD => self::ENTRY_METHOD_NAME,
            ScriptEvents::POST_UPDATE_CMD => self::ENTRY_METHOD_NAME,
        ];
    }

    /**
     * @param Event $composerEvent
     */
    public static function performTasks(Event $composerEvent)
    {
        $composer = $composerEvent->getComposer();
        $extra = $composer->getPackage()->getExtra();
        $io = $composerEvent->getIO();

        if (empty($extra[SI::INSTALLER_EXTRA_KEY])) {
            $io->write(self::MESSAGE_NO_CONFIGURATION);
            return;
        }

        foreach ($extra[SI::INSTALLER_EXTRA_KEY] as $entry) {
            if (!\is_array($entry)) {
                continue;
            }
            foreach ($entry as $taskName => $config) {
                if (\is_array($config)) {
                    $result = static::performSingleTask($taskName, $config);
                    $io->write($result->getMessage());
                }
            }
        }
    }

    /**
     * Performs a single task determined by name
     *
     * @param string $taskName
     * @param array $config
     * @return ResultInterface
     */
    private static function performSingleTask(string $taskName, array $config)
    {
        if (!\array_key_exists($taskName, static::TASKS_TO_PERFORM)) {
            return new Error(
                sprintf(static::MESSAGE_INVALID_TASK_KEY, $taskName),
                1550161722
            );
        }
        $taskClass = self::TASKS_TO_PERFORM[$taskName];

        /** @var TaskInterface $task */
        $task = new $taskClass();
        return $task->perform($config);
    }

    /**
     * {@inheritDoc}
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        // Nothing to do here, as all features are provided through event listeners
    }
}
