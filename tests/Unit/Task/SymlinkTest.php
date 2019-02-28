<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Michael Scheppat
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

namespace CPSIT\ProjectBuilder\Tests\Unit\Task;

use Composer\IO\IOInterface;
use CPSIT\ProjectBuilder\Task\Symlink;
use Naucon\File\File;
use CPSIT\ProjectBuilder\Task\TaskInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class SymlinkTest
 */
class SymlinkTest extends TestCase
{
    /**
     * @var Symlink
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

        $this->subject = new Symlink($this->io);
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


    public function testPerformWritesMessageForSuccess()
    {
        $base_path="";
        $source_path= $base_path . "tmp1/tmp11/tmp111";
        mkdir($source_path,0777,true);
        $target_path= $base_path . "tmp1/tmp12/tmp121";
        mkdir($target_path,0777,true);
        $filename = 'file.txt';
        $fileHandle = fopen($source_path.File::PATH_SEPARATOR.$filename, 'ab');
        fwrite($fileHandle, 'foo');
        fclose($fileHandle);
        $target_name='a_symlink_to_a_file';
        $config = [
            $source_path.File::PATH_SEPARATOR.$filename => $target_path.File::PATH_SEPARATOR.$target_name
        ];
        $this->subject = new Symlink($this->io, $config);

        $expectedMessage = sprintf(
            TaskInterface::MESSAGE_SYMLINK_CREATED,
            $source_path. File::PATH_SEPARATOR .$filename,
            $target_path. File::PATH_SEPARATOR .$target_name
        );
        $this->io->expects($this->once())
            ->method('write')
            ->with($expectedMessage);
        $this->io->expects($this->never())
            ->method('writeError');

        $this->subject->perform();
    }

    public function testPerformWritesErrorForFailure()
    {
        $target_path= ".";
        $source_path="gdfgdfg";
        $filename = 'unfound.txt';

        $target_name='a_symlink_to_fail';
        $config = [
            $source_path.File::PATH_SEPARATOR.$filename => $target_path.File::PATH_SEPARATOR.$target_name
        ];
        $this->subject = new Symlink($this->io, $config);

        $expectedMessage = sprintf(
            TaskInterface::MESSAGE_FILE_NOT_FOUND,
            getcwd().File::PATH_SEPARATOR.$source_path.File::PATH_SEPARATOR.$filename
        );
        $this->io->expects($this->atLeastOnce())
            ->method('writeError')
            ->with($expectedMessage);
        $this->io->expects($this->never())
            ->method('write');

        $this->subject->perform();
    }
}
