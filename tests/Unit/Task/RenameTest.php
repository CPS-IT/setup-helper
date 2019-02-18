<?php
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

namespace Fr\ProjectBuilder\Tests\Unit\Task;

use Composer\IO\IOInterface;
use Fr\ProjectBuilder\Task\Rename;
use Fr\ProjectBuilder\Task\TaskInterface;
use Naucon\File\File;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RenameTest extends TestCase
{
    const  FIXTURE_PATH = 'Unit/Fixtures/';

    /**
     * @var Rename
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
        $this->subject = new Rename($this->io);
    }

    public function testPerformWritesMessageForEmptyConfiguration()
    {
        $expectedMessage = get_class($this->subject) . ': ' .
            TaskInterface::MESSAGE_EMPTY_CONFIGURATION;
        $this->io->expects($this->once())
            ->method('write')
            ->with($expectedMessage);

        $this->subject->perform();
    }

    public function testPerformWritesMessageForMissingFile()
    {
        $invalidFilePath = 'foo-bar';
        $newName = 'baz';
        $config = [$invalidFilePath => $newName];
        $this->subject = new Rename($this->io, $config);

        $expectedMessage = sprintf(
            TaskInterface::MESSAGE_FILE_NOT_FOUND,
            $invalidFilePath
        );
        $this->io->expects($this->once())
            ->method('writeError')
            ->with($expectedMessage);

        $this->subject->perform();
    }

    /**
     * @throws \Naucon\File\Exception\FileException
     */
    public function testPerformWritesMessageForSuccess()
    {
        $source = self::FIXTURE_PATH . 'foo.txt';
        $newName = 'bar.boom';

        $this->prepareFileFixtures($source, $newName);

        $config = [
            $source => $newName
        ];
        $this->subject = new Rename($this->io, $config);

        $expectedMessage = sprintf(
            TaskInterface::MESSAGE_FILE_RENAMED,
            $source, $newName
        );
        $this->io->expects($this->once())
            ->method('write')
            ->with($expectedMessage);
        $this->io->expects($this->never())
            ->method('writeError');

        $this->subject->perform();

        $this->cleanUpFileFixtures($source, $newName);
    }

    /**
     * @param $source
     * @param $newName
     * @throws \Naucon\File\Exception\FileException
     * @return void
     */
    public function prepareFileFixtures($source, $newName)
    {
        $workingDirectory = getcwd() . File::PATH_SEPARATOR;
        if (!file_exists($workingDirectory . $source)) {

            $sourceFile = new File($workingDirectory . $source);
            $sourceFile->createNewFile();
        }
    }

    protected function cleanUpFileFixtures($source, $newName)
    {
        $workingDirectory = getcwd() . File::PATH_SEPARATOR;
        $fixturePath = $workingDirectory . self::FIXTURE_PATH;

        if (file_exists($fixturePath . $newName)) {
            unlink($fixturePath. $newName);
        }
    }
}
