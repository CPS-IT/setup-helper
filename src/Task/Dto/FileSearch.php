<?php

namespace CPSIT\SetupHelper\Task\Dto;

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
 * Class FileSearch
 */
class FileSearch implements SearchInterface
{

    /**
     * @var string
     */
    protected $path = '';

    /**
     * @var string
     */
    protected $search = '';

    /**
     * @var string
     */
    protected $replace = '';

    /**
     * Get the path pattern
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Set the path pattern
     *
     * @param string $path
     * @return FileSearch
     */
    public function setPath(string $path): FileSearch
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the search pattern
     * @return string
     */
    public function getSearch(): string
    {
        return $this->search;
    }

    /**
     * Set the search pattern
     *
     * @param string $search
     * @return FileSearch
     */
    public function setSearch(string $search): FileSearch
    {
        $this->search = $search;
        return $this;
    }

    /**
     * Get the replace string
     *
     * @return string
     */
    public function getReplace(): string
    {
        return $this->replace;
    }

    /**
     * Set the replace string
     *
     * @param string $replace
     * @return FileSearch
     */
    public function setReplace(string $replace): FileSearch
    {
        $this->replace = $replace;

        return $this;
    }
}