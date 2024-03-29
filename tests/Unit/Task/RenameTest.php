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

namespace CPSIT\SetupHelper\Tests\Unit\Task;

use Composer\IO\IOInterface;
use CPSIT\SetupHelper\Task\Rename;
use CPSIT\SetupHelper\Task\TaskInterface;
use Naucon\File\File;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RenameTest extends TestCase
{
    const  FIXTURE_PATH = 'tests/Unit/Fixtures/';

    /**
     * @var Rename
     */
    protected $subject;

    /**
     * @var IOInterface|MockObject
     */
    protected $io;

    public function setUp(): void
    {
        parent::setUp();
        $this->io = $this->getMockBuilder(IOInterface::class)
            ->setMethods(['write', 'writeError'])
            ->getMockForAbstractClass();
        $this->subject = new Rename($this->io);
    }

    public function testPerformWritesMessageForEmptyConfiguration()
    {
        $expectedMessage = \get_class($this->subject) . ': ' .
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

        $this->prepareFileFixtures($source);

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

        $this->cleanUpFileFixtures($newName);
    }

    /**
     * @param $fileName
     * @return void
     * @throws \Naucon\File\Exception\FileException
     */
    public function prepareFileFixtures($fileName)
    {
        $workingDirectory = getcwd() . File::PATH_SEPARATOR;
        if (!file_exists($workingDirectory . $fileName)) {

            $sourceFile = new File($workingDirectory . $fileName);
            $sourceFile->createNewFile();
        }
    }

    /**
     * Removes a file from fixture folder if exists
     *
     * @param $newName
     */
    protected function cleanUpFileFixtures($newName)
    {
        $workingDirectory = getcwd() . File::PATH_SEPARATOR;
        $fixturePath = $workingDirectory . self::FIXTURE_PATH;

        if (file_exists($fixturePath . $newName)) {
            unlink($fixturePath . $newName);
        }
    }

    public function testPerformWritesErrorForException(): void
    {
        $message = 'bar';
        $mockException = new \Exception($message);
        $oldName = 'foo.txt';
        $newName = 'bar.txt';

        $config = [
            $oldName => $newName
        ];
        $this->subject = $this->getMockBuilder(Rename::class)
            ->setMethods(['process'])
            ->setConstructorArgs(
                [$this->io, $config])
            ->getMock();

        $this->subject->method('process')
            ->willThrowException($mockException);

        $this->io->expects($this->once())
            ->method('writeError')
            ->with($message);

        $this->subject->perform();
    }
}
