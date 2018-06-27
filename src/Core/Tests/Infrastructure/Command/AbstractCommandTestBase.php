<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Tests\Infrastructure\Command
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

namespace Artefakt\Core\Tests\Infrastructure\Command;

use Artefakt\Core\Infrastructure\Facade\Environment;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Webmozart\PathUtil\Path;

/**
 * Abstract Command Test Base
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Tests\Infrastructure\Command
 */
class AbstractCommandTestBase extends KernelTestCase
{
    /**
     * Root directory
     *
     * @var string
     */
    protected static $rootDirectory;

    /**
     * Set up
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$rootDirectory = Environment::get(Environment::ROOT);
        self::cleanUp();
    }

    /**
     * Cleanup tasks
     */
    protected static function cleanUp()
    {
        // Run through all application directories
        foreach (['cmp', 'doc', 'cac'] as $key) {
            $directory = Path::makeAbsolute("build/$key", self::$rootDirectory);
            if (is_dir($directory)) {
                rmrdir($directory);
            }
        }

        if (file_exists(Path::makeAbsolute('.env', self::$rootDirectory))) {
            unlink(Path::makeAbsolute('.env', self::$rootDirectory));
        }
    }

    /**
     * Tear down
     */
    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        self::cleanUp();
    }
}