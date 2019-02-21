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

namespace CPSIT\ProjectBuilder\Tests\Unit\Task;

use Composer\IO\IOInterface;
use CPSIT\ProjectBuilder\Task\TaskInterface;
use CPSIT\ProjectBuilder\Task\Unlink;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UnlinkTest extends TestCase
{
    /**
     * @var Unlink
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

        $this->subject = new Unlink($this->io);
    }

    public function testPerformWritesMessageForEmptyConfiguration()
    {
        $this->io->expects($this->once())
            ->method('write')
            ->with(\get_class($this->subject) . ': ' . TaskInterface::MESSAGE_EMPTY_CONFIGURATION);

        $this->subject->perform();
    }

    public function testWritesMessageForMissingFile()
    {
        $invalidFilePath = 'foo-bar';
        $config = [$invalidFilePath];
        $this->subject = new Unlink($this->io, $config);

        $expectedMessage = sprintf(
            TaskInterface::MESSAGE_FILE_NOT_FOUND,
            $invalidFilePath
        );
        $this->io->expects($this->once())
            ->method('writeError')
            ->with($expectedMessage);

        $this->subject->perform();
    }
}
