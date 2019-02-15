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

use Fr\ProjectBuilder\Report\Notice;
use Fr\ProjectBuilder\Report\ResultInterface;
use Fr\ProjectBuilder\Task\TaskInterface;
use Fr\ProjectBuilder\Task\Unlink;
use PHPUnit\Framework\TestCase;

class UnlinkTest extends TestCase
{
    /**
     * @var Unlink
     */
    protected $subject;

    public function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->subject = new Unlink();
    }

    public function testPerformReturnsResult()
    {
        $result = $this->subject->perform([]);
        $this->assertInstanceOf(
            ResultInterface::class,
            $result
        );
    }

    public function testPerformReturnsNoticeForEmptyConfiguration()
    {
        $result = $this->subject->perform([]);
        $this->assertInstanceOf(
            Notice::class,
            $result
        );
    }

    public function testResultForEmptyConfigReturnsMessage()
    {
        $expectedMessage = get_class($this->subject) . ': ' . TaskInterface::MESSAGE_EMPTY_CONFIGURATION;
        $result = $this->subject->perform([]);
        $this->assertSame(
            $expectedMessage,
            $result->getMessage()
        );
    }
}
