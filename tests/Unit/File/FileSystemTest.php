<?php

namespace CPSIT\SetupHelper\Tests\Unit\File;

use CPSIT\SetupHelper\File\FileSystem;
use CPSIT\SetupHelper\File\FileSystemInterface;
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
 * Class FileSystemTest
 */
class FileSystemTest extends TestCase
{
    /**
     * @var FileSystem
     */
    protected $subject;

    public function setUp()
    {
        parent::setUp();
        $this->subject = new FileSystem();
    }

    public function testSubjectImplementsFileSystemInterface()
    {
        $this->assertInstanceOf(
            FileSystemInterface::class,
            $this->subject
        );
    }
}