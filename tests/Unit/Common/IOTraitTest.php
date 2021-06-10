<?php
namespace CPSIT\SetupHelper\Tests\Unit\Common;

use Composer\IO\IOInterface;
use CPSIT\SetupHelper\Common\IOTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

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

/**
 * Class IOTraitTest
 */
class IOTraitTest extends TestCase
{
    /**
     * @var IOTrait | MockObject
     */
    protected $subject;

    public function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(IOTrait::class)
            ->getMockForTrait();
    }

    public function testIOIsInitiallyNull()
    {
        $this->assertNull(
            $this->subject->getIo()
        );
    }

    public function testIoCanBeSet()
    {
        $io = $this->getMockBuilder(IOInterface::class)
            ->getMockForAbstractClass();

        $this->subject->setIo($io);
        $this->assertSame(
            $io,
            $this->subject->getIo()
        );
    }
}
