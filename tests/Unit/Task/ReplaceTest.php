<?php

namespace CPSIT\SetupHelper\Tests\Unit\Task;

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
use CPSIT\SetupHelper\SettingsInterface;
use CPSIT\SetupHelper\Task\Replace;
use CPSIT\SetupHelper\Task\TaskInterface;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;
use PHPUnit\Framework\TestCase;

class ReplaceTest extends TestCase
{
    /**
     * @var Replace
     */
    protected $subject;

    /**
     * @var IOInterface|MockObject
     */
    protected $io;

    public function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->io = $this->getMockBuilder(IOInterface::class)
            ->setMethods(['write', 'writeError'])
            ->getMockForAbstractClass();

        $this->subject = new Replace($this->io);
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
        $config = [
            [
                TaskInterface::KEY_PATH => $invalidFilePath,
                TaskInterface::KEY_SEARCH => 'foo',
                TaskInterface::KEY_REPLACE => 'bar'
            ]
        ];
        $this->subject = new Replace($this->io, $config);

        $expectedMessage = sprintf(
            TaskInterface::MESSAGE_FILE_NOT_FOUND,
            $invalidFilePath
        );
        $this->io->expects($this->once())
            ->method('writeError')
            ->with($expectedMessage);

        $this->subject->perform();
    }

    public function testPerformWritesMessageForEmptyPath()
    {
        $emptyFilePath = '';
        $config = [
            [
                TaskInterface::KEY_PATH => $emptyFilePath,
                TaskInterface::KEY_SEARCH => 'foo',
                TaskInterface::KEY_REPLACE => 'bar'
            ]
        ];
        $this->subject = new Replace($this->io, $config);

        $expectedMessage = sprintf(
            TaskInterface::MESSAGE_EMPTY_KEY,
            TaskInterface::KEY_PATH,
            SettingsInterface::REPLACE_TASK_KEY
        );
        $this->io->expects($this->once())
            ->method('writeError')
            ->with($expectedMessage);

        $this->subject->perform();
    }

    public function testPerformWritesMessageForEmptySearchPattern()
    {
        $emptySearchPattern = '';
        $config = [
            [
                TaskInterface::KEY_SEARCH => $emptySearchPattern,
                TaskInterface::KEY_PATH => 'foo',
                TaskInterface::KEY_REPLACE => 'bar'
            ]
        ];
        $this->subject = new Replace($this->io, $config);

        $expectedMessage = sprintf(
            TaskInterface::MESSAGE_EMPTY_KEY,
            TaskInterface::KEY_SEARCH,
            SettingsInterface::REPLACE_TASK_KEY
        );
        $this->io->expects($this->once())
            ->method('writeError')
            ->with($expectedMessage);

        $this->subject->perform();
    }

    public function testPerformWritesMessageIfKeyAskAndReplaceAreSet()
    {
        $config = [
            [
                TaskInterface::KEY_ASK => 'boom',
                TaskInterface::KEY_REPLACE => 'moo',
                TaskInterface::KEY_PATH => 'foo',
                TaskInterface::KEY_SEARCH => 'bar'
            ]
        ];
        $this->subject = new Replace($this->io, $config);

        $expectedMessage = sprintf(
            TaskInterface::MESSAGE_CONFLICTING_KEYS,
            TaskInterface::KEY_ASK,
            TaskInterface::KEY_REPLACE,
            SettingsInterface::REPLACE_TASK_KEY
        );

        $this->io->expects($this->once())
            ->method('writeError')
            ->with($expectedMessage);

        $this->subject->perform();
    }

    /**
     * @throws \org\bovigo\vfs\vfsStreamException
     */
    public function testPerformReplacesStringInFileContent()
    {
        vfsStreamWrapper::register();

        $search = '{{boom}}';
        $initialContent = 'foo-{{boom}}';
        $replace = 'bar';

        $fileDirectory = 'boom';
        $fileName = 'foo.csv';

        $configuration = [
            [
                TaskInterface::KEY_PATH => $fileName,
                TaskInterface::KEY_SEARCH => $search,
                TaskInterface::KEY_REPLACE => $replace
            ],
        ];
        $this->subject = $this->getMockBuilder(Replace::class)
            ->setConstructorArgs([$this->io, $configuration])
            ->setMethods(['getWorkingDirectory'])
            ->getMock();

        vfsStream::setup($fileDirectory);
        $mockFile = vfsStream::newFile($fileName);
        $mockFile->setContent($initialContent);
        vfsStreamWrapper::getRoot()->addChild($mockFile);

        $this->subject->expects($this->once())
            ->method('getWorkingDirectory')
            ->willReturn(vfsStream::url($fileDirectory . '/'));

        $expectedMessage = sprintf(
            TaskInterface::MESSAGE_REPLACED_IN_FILE,
            $search,
            $mockFile->url(),
            1,
            $replace
        );

        $this->io->expects($this->once())
            ->method('write')
            ->with($expectedMessage);

        $expectedContent = str_replace($search, $replace, $initialContent);

        $this->subject->setConfig($configuration);
        $this->subject->perform();
        $this->assertSame(
            $expectedContent,
            $mockFile->getContent()
        );

    }

}
