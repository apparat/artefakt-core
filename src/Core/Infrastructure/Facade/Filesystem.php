<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure
 * @author     Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @copyright  Copyright © 2018 Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2018 tollwerk GmbH <info@tollwerk.de>
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of
 *  this software and associated documentation files (the "Software"), to deal in
 *  the Software without restriction, including without limitation the rights to
 *  use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 *  the Software, and to permit persons to whom the Software is furnished to do so,
 *  subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 *  FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 *  COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 *  IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 *  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 ***********************************************************************************/

namespace Artefakt\Core\Infrastructure\Facade;

use Artefakt\Core\Infrastructure\Exceptions\RuntimeException;

/**
 * Filesystem Helper
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure
 */
class Filesystem
{
    /**
     * Find the closest composer root directory for a given file / directory path
     *
     * @param string $path File / directory path
     *
     * @return string Closest composer root directory
     */
    public static function findComposerRootDirectory(string $path): ?string
    {
        $lastPath = null;
        while ($path && ($lastPath !== $path) && !is_dir($path.DIRECTORY_SEPARATOR.'vendor')) {
            $lastPath = $path;
            $path     = dirname($path);
        }

        return (strlen($path) && ($lastPath !== $path)) ? $path : null;
    }

    /**
     * Load JSON data from the file system
     *
     * @param string $path File path
     *
     * @return array JSON data
     * @throws RuntimeException If the file is empty or invalid
     */
    public static function loadJSON(string $path): array
    {
        // If the file is empty or invalid
        $json = is_file($path) ? trim(file_get_contents($path)) : '';
        $data = strlen($json) ? json_decode($json, true) : null;
        if (is_array($data)) {
            return $data;
        }

        throw new RuntimeException(
            sprintf(RuntimeException::INVALID_FILE_STR, $path),
            RuntimeException::INVALID_FILE
        );
    }
}
