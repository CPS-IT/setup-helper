<?php

namespace Fr\ProjectBuilder\Tests\Unit\Report;
use Fr\ProjectBuilder\Report\Result;
use Fr\ProjectBuilder\Report\ResultInterface;
use PHPUnit\Framework\TestCase;

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

class ResultTest extends TestCase
{
    /**
     * @var Result
     */
    protected $subject;

    public function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->subject = new Result();
    }

    /**
     * @test
     */
    public function getStatusInitiallyReturnsUnknownStatus()
    {
        $this->assertSame(
            ResultInterface::STATUS_UNKNOWN,
            $this->subject->getStatus()
        );
    }

    /**
     * @test
     */
    public function getMessageInitiallyReturnsMessageUndefined()
    {
        $this->assertSame(
            ResultInterface::MESSAGE_UNDEFINED,
            $this->subject->getMessage()
        );
    }

    public function testConstructorSetsMessage()
    {
        $message = 'foo';
        $this->subject = new Result($message);
        $this->assertSame(
            $message,
            $this->subject->getMessage()
        );
    }

    public function testGetIdInitiallyReturnsDefaultIdZero()
    {
        $this->assertSame(
            ResultInterface::DEFAULT_MESSAGE_ID,
            $this->subject->getId()
        );
    }

    public function testConstructorSetsId()
    {
        $expectedId = 123454321;
        $this->subject = new Result('foo', $expectedId);

        $this->assertSame(
            $expectedId,
            $this->subject->getId()
        );
    }
}
