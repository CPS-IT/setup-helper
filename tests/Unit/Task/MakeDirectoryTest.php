<?php
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

namespace Fr\ProjectBuilder\Tests\Unit\Task;

use Composer\IO\IOInterface;
use Fr\ProjectBuilder\Task\MakeDirectory;
use Fr\ProjectBuilder\Task\TaskInterface;
use Naucon\File\File;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MakeDirectoryTest extends TestCase
{
    /**
     * @var MakeDirectory
     */
    protected $subject;

    /**
     * @var IOInterface|MockObject
     */
    protected $io;

    public function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();
        $this->io = $this->getMockBuilder(IOInterface::class)
            ->setMethods(['write', 'writeError'])
            ->getMockForAbstractClass();

        $this->subject = new MakeDirectory($this->io);
    }

    public function testPerformWritesMessageForEmptyConfiguration()
    {
        $this->io->expects($this->once())
            ->method('write')
            ->with(get_class($this->subject) . ': ' . TaskInterface::MESSAGE_EMPTY_CONFIGURATION);

        $this->subject->perform();
    }


    public function testPerformWritesMessageForSuccess()
    {
        $parentDirectory = 'boom/';
        $newDirectory = 'boom/bam';
        $expectedMessage = sprintf(
            TaskInterface::MESSAGE_FOLDER_CREATED,
            $newDirectory
        );

        $this->prepareFixtures($newDirectory, $parentDirectory);

        $config = [$newDirectory];
        $this->subject->setConfig($config);

        $this->io->expects($this->once())
            ->method('write')
            ->with($expectedMessage);

        $this->subject->perform();
    }

    public function testPerformMakesDirectory()
    {
        $parentDirectory = 'boom/';
        $newDirectory = 'boom/bam';

        $this->prepareFixtures($newDirectory, $parentDirectory);

        $config = [$newDirectory];
        $this->subject->setConfig($config);
        $this->subject->perform();

        $this->assertDirectoryExists(
            getcwd() . File::PATH_SEPARATOR . $newDirectory
        );

        $this->prepareFixtures($newDirectory, $parentDirectory);
    }

    public function testPerformWritesMessageForExistingDirectory()
    {
        $newDirectory = 'boom/bam';
        $expectedMessage = sprintf(
            TaskInterface::MESSAGE_FOLDER_ALREADY_EXISTS,
            $newDirectory
        );

        $this->createDirectoryRecursive($newDirectory);

        $config = [$newDirectory];
        $this->subject->setConfig($config);

        $this->io->expects($this->once())
            ->method('write')
            ->with($expectedMessage);

        $this->subject->perform();
    }

    /**
     * Make sure the directories which should be created do not exist
     * @param $newDirectory
     * @param $parentDirectory
     */
    protected function prepareFixtures($newDirectory, $parentDirectory)
    {
        $newDirectoryPath = getcwd() . File::PATH_SEPARATOR .  $newDirectory;
        $parentDirectoryPath = getcwd() . File::PATH_SEPARATOR . $parentDirectory;
        if (is_dir($newDirectoryPath)) {
            rmdir($newDirectoryPath);
            rmdir($parentDirectoryPath);
        }
    }

    /**
     * Make sure the directory does exist
     * @param $newDirectory
     */
    protected function createDirectoryRecursive($newDirectory)
    {
        $newDirectoryPath = getcwd() . File::PATH_SEPARATOR .  $newDirectory;
        if (!is_dir($newDirectoryPath)) {
            mkdir(
                $newDirectoryPath, 0700 , true);
        }
    }
}
