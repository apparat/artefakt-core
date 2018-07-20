<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Tests
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

namespace Artefakt\Core\Tests;

use Cocur\Slugify\Slugify;
use PHPUnit\Framework\TestCase;

/**
 * Abstract test base
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Tests
 */
abstract class AbstractTestBase extends TestCase
{
    /**
     * Slugify instance
     *
     * @var Slugify
     */
    protected static $slugify;

    /**
     * Set up before class
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$slugify = new Slugify();
    }

    /**
     * Recursively copy a directory
     *
     * @param string $source Source directory
     * @param string $target Target directory
     */
    protected static function copyRecursive($source, $target): void
    {
        if (!is_dir($target)) {
            mkdir($target, 0755, true);
        }
        foreach (
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if ($item->isDir()) {
                if (!is_dir($target.DIRECTORY_SEPARATOR.$iterator->getSubPathName())) {
                    mkdir($target.DIRECTORY_SEPARATOR.$iterator->getSubPathName());
                }
            } elseif (!is_file($target.DIRECTORY_SEPARATOR.$iterator->getSubPathName())) {
                copy($item, $target.DIRECTORY_SEPARATOR.$iterator->getSubPathName());
            }
        }
    }
}
