<?php

namespace CPSIT\SetupHelper\File;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Michael Scheppat <m.scheppat@familie-redlich.de>
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

use CPSIT\Glob\Glob;

Class GlobResolver implements ResolverInterface
{
    protected $pattern;

    /**
     * Gets the pattern to be resolved
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * Sets the pattern to be resolved
     * @param string
     */
    public function setPattern(string $pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * Resolves path pattern to
     * return list of file paths
     * @return array
     */
    public function resolve()
    {
        return $this->globRecursive($this->pattern);
    }

    protected function globRecursive($pattern, $flags = 0)
    {
        $files = Glob::glob($pattern, $flags);
        foreach (Glob::glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, $this->globRecursive($dir . '/' . basename($pattern), $flags));
        }
        return $files;
    }
}
