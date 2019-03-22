<?php

namespace CPSIT\SetupHelper\Tests\Unit\Task\Dto;

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

use CPSIT\SetupHelper\Task\Dto\FileSearch;
use PHPUnit\Framework\TestCase;

/**
 * Class SearchTest
 */
class FileSearchTest extends TestCase
{
    /**
     * @var FileSearch
     */
    protected $subject;

    /**
     * {@inheritdoc}
     */
    public function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();
        $this->subject = new FileSearch();
    }

    public function testGetSearchInitiallyReturnsEmptyString()
    {
        $this->assertSame(
            '',
            $this->subject->getSearch()
        );
    }

    public function testSearchCanBeSet()
    {
        $value = 'foo';
        $this->subject->setSearch($value);

        $this->assertSame(
            $value,
            $this->subject->getSearch()
        );
    }
     public function testGetReplaceInitiallyReturnsEmptyString()
    {
        $this->assertSame(
            '',
            $this->subject->getReplace()
        );
    }

    public function testReplaceCanBeSet()
    {
        $value = 'foo';
        $this->subject->setReplace($value);

        $this->assertSame(
            $value,
            $this->subject->getReplace()
        );
    }

    public function testGetPathInitiallyReturnsEmptyString()
    {
        $this->assertSame(
            '',
            $this->subject->getPath()
        );
    }

    public function testPathCanBeSet()
    {
        $value = 'foo';
        $this->subject->setPath($value);

        $this->assertSame(
            $value,
            $this->subject->getPath()
        );
    }
}
