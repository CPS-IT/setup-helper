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
use Fr\ProjectBuilder\Task\AbstractTask;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AbstractTaskTest extends TestCase
{
    /**
     * @var AbstractTask|MockObject
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
            ->getMockForAbstractClass();
        $this->subject = $this->getMockBuilder(AbstractTask::class)
            ->setConstructorArgs([$this->io])
            ->getMockForAbstractClass();
    }

    public function testConstructorSetsIo()
    {
        $this->assertSame(
            $this->io,
            $this->subject->getIo()
        );
    }
}
